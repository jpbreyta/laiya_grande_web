<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Customer;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ReservationController extends Controller
{
    public function index()
    {
        return view('user.reserve.reservation');
    }

    public function create()
    {
        $rooms = Room::all();
        return view('user.reservation.create', compact('rooms'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => ['required', 'regex:/^(09|\+639|639)\d{9}$/'],
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
            'guests' => 'required|integer|min:1',
            'special_requests' => 'nullable|string',
            'payment_method' => 'required|in:gcash,bank_transfer',
            'payment_proof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'otp_verified' => 'required|in:1',
        ], [
            'phone.regex' => 'Please enter a valid Philippine mobile number (11 digits starting with 09).',
            'otp_verified.required' => 'Please verify your email with OTP before proceeding.',
            'otp_verified.in' => 'Email verification is required.',
            'payment_proof.required' => 'Payment proof is required.',
        ]);

        // Handle payment proof upload
        $paymentPath = null;
        if ($request->hasFile('payment_proof')) {
            $paymentPath = $request->file('payment_proof')->store('payments', 'public');
        }

        $cart = session('cart', []);
        if (empty($cart)) {
            return response()->json([
                'success' => false,
                'message' => 'No rooms selected for reservation.'
            ]);
        }

        $customer = Customer::firstOrCreate(
            ['email' => $validated['email']],
            [
                'firstname' => $validated['first_name'],
                'lastname' => $validated['last_name'],
                'phone_number' => $validated['phone'],
            ]
        );

        $totalGuests = $validated['guests'];
        $totalCapacity = 0;
        foreach ($cart as $item) {
            $room = Room::find($item['room_id']);
            if (!$room) continue;
            $totalCapacity += $room->capacity * $item['quantity'];
        }

        if ($totalGuests > $totalCapacity) {
            return response()->json([
                'success' => false,
                'message' => "Number of guests ({$totalGuests}) exceeds total room capacity ({$totalCapacity}). Please adjust your guest count or select rooms with higher capacity."
            ]);
        }

        foreach ($cart as $item) {
            $room = Room::find($item['room_id']);
            if (!$room) continue;
            if ($room->availability < $item['quantity']) {
                return response()->json([
                    'success' => false,
                    'message' => "Insufficient availability for {$room->name}. Only {$room->availability} room(s) left."
                ]);
            }
        }

        $reservations = collect();
        // Calculate nights
        $checkIn = Carbon::parse($request->check_in);
        $checkOut = Carbon::parse($request->check_out);
        $nights = max(1, $checkIn->diffInDays($checkOut));

        foreach ($cart as $item) {
            $room = Room::find($item['room_id']);
            if (!$room) continue;

            $totalPrice = $room->price * $item['quantity'] * $nights;

            $reservation = Reservation::create([
                'room_id' => $room->id,
                'customer_id' => $customer->id,
                'check_in' => $validated['check_in'],
                'check_out' => $validated['check_out'],
                'number_of_guests' => $validated['guests'],
                'special_request' => $validated['special_requests'] ?? null,
                'payment_method' => $validated['payment_method'],
                'first_payment' => $paymentPath,
                'total_price' => $totalPrice,
                'status' => 'pending',
                'expires_at' => Carbon::now()->addHours(24),
                'reservation_number' => $this->generateReservationNumber(),
            ]);

            $reservations->push($reservation);

            $room->availability -= $item['quantity'];
            $room->save();
        }

        \App\Models\Notification::create([
            'type' => 'reservation',
            'title' => 'New Reservation Request',
            'message' => "New reservation from {$customer->firstname} {$customer->lastname} for {$reservations->count()} room(s)",
            'data' => [
                'reservation_id' => $reservations->first()->id,
                'name' => "{$customer->firstname} {$customer->lastname}",
                'email' => $customer->email,
                'phone' => $customer->phone_number,
                'check_in' => $validated['check_in'],
                'check_out' => $validated['check_out'],
                'guests' => $validated['guests'],
            ],
            'read' => false,
        ]);

        // Store reservation data in session for review page
        session([
            'reservation_number' => $reservations->first()->reservation_number,
            'reservation_data' => [
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'check_in' => $validated['check_in'],
                'check_out' => $validated['check_out'],
                'guests' => $validated['guests'],
                'special_requests' => $validated['special_requests'],
                'payment_method' => $validated['payment_method'],
                'bank_name' => $request->bank_name ?? null,
                'bank_account_name' => $request->bank_account_name ?? null,
                'bank_account_number' => $request->bank_account_number ?? null,
                'bank_reference' => $request->bank_reference ?? null,
            ]
        ]);

        // Keep cart in session for review page
        $cartForReview = session('cart');
        session()->forget('cart');
        session(['cart' => $cartForReview]);

        return response()->json([
            'success' => true,
            'reservation_id' => $reservations->first()->id,
            'reservation_number' => $reservations->first()->reservation_number,
            'message' => 'Reservation submitted successfully! You have 24 hours to complete payment.',
            'redirect_url' => route('user.reservation.review')
        ]);
    }

    /**
     * Show reservation review page
     */
    public function review()
    {
        // Check if reservation data exists in session
        if (!session()->has('reservation_number')) {
            return redirect()->route('booking.index')->with('error', 'No reservation found.');
        }

        return view('user.reserve.review');
    }

    /**
     * Send OTP to email for verification
     */
    public function sendOTP(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        try {
            $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            
            $expiryTime = now()->addMinute();
            session([
                'reservation_otp_code' => $otp,
                'reservation_otp_email' => $request->email,
                'reservation_otp_expiry' => $expiryTime,
                'reservation_otp_sent_at' => now()->timestamp
            ]);

            Mail::raw("Your OTP code for Laiya Grande reservation is: {$otp}\n\nThis code will expire in 1 minute.", function ($message) use ($request) {
                $message->to($request->email)
                    ->subject('Email Verification - Laiya Grande Resort');
            });

            Log::info('Reservation OTP sent', ['email' => $request->email, 'otp' => $otp, 'expiry' => $expiryTime]);

            return response()->json([
                'success' => true,
                'message' => 'OTP sent successfully to your email',
                'sent_at' => now()->timestamp
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send reservation OTP', ['error' => $e->getMessage()]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to send OTP. Please try again.'
            ], 500);
        }
    }

    /**
     * Verify OTP code
     */
    public function verifyOTP(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|string|size:6'
        ]);

        $sessionOtp = session('reservation_otp_code');
        $sessionEmail = session('reservation_otp_email');
        $otpExpiry = session('reservation_otp_expiry');

        Log::info('Reservation OTP Verification Attempt', [
            'input_email' => $request->email,
            'input_otp' => $request->otp,
            'session_email' => $sessionEmail,
            'session_otp' => $sessionOtp,
            'expiry' => $otpExpiry
        ]);

        if (!$sessionOtp || !$sessionEmail || !$otpExpiry) {
            return response()->json([
                'success' => false,
                'message' => 'No OTP found. Please request a new one.'
            ]);
        }

        if (now()->greaterThan($otpExpiry)) {
            session()->forget(['reservation_otp_code', 'reservation_otp_email', 'reservation_otp_expiry', 'reservation_otp_sent_at']);
            return response()->json([
                'success' => false,
                'message' => 'OTP has expired. Please request a new one.'
            ]);
        }

        if ($sessionEmail !== $request->email) {
            return response()->json([
                'success' => false,
                'message' => 'Email does not match.'
            ]);
        }

        if ($sessionOtp === $request->otp) {
            session(['reservation_email_verified' => true]);
            session()->forget(['reservation_otp_code', 'reservation_otp_email', 'reservation_otp_expiry']);
            
            return response()->json([
                'success' => true,
                'message' => 'Email verified successfully!'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Invalid OTP code.'
        ]);
    }

    public function show($id)
    {
        $reservation = Reservation::with('room')->findOrFail($id);
        return view('user.reservation.show', compact('reservation'));
    }

    public function edit($id)
    {
        $reservation = Reservation::findOrFail($id);
        $rooms = Room::all();
        return view('user.reservation.edit', compact('reservation', 'rooms'));
    }

    public function update(Request $request, $id)
    {
        $customer = Customer::updateOrCreate(
            ['email' => $request->email],
            [
                'firstname' => $request->first_name,
                'lastname' => $request->last_name,
                'phone_number' => $request->phone
            ]
        );

        $reservation = Reservation::findOrFail($id);

        $validated = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
            'number_of_guests' => 'required|integer|min:1',
            'special_request' => 'nullable|string',
            'payment' => 'nullable|image|max:2048',
        ]);

        $validated['customer_id'] = $customer->id;

        if ($request->hasFile('payment')) {
            $validated['payment'] = $request->file('payment')->store('payments', 'public');
        }

        $room = Room::findOrFail($validated['room_id']);
        $days = max(1, (strtotime($validated['check_out']) - strtotime($validated['check_in'])) / 86400);
        $validated['total_price'] = $room->price * $days;

        $reservation->update($validated);

        return redirect()->route('user.reservation.index')->with('success', 'Reservation updated successfully!');
    }

    public function destroy($id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->delete();

        return redirect()->route('user.reservation.index')->with('success', 'Reservation deleted successfully!');
    }

    public function continuePaying(Request $request, $id)
    {
        $reservation = Reservation::with('room')->findOrFail($id);

        if ($request->isMethod('POST')) {
            $request->validate([
                'email' => 'required|email',
                'phone_number' => 'required|string|max:15',
            ]);

            if ($request->email !== $reservation->email || $request->phone_number != $reservation->phone_number) {
                return back()->withErrors(['Invalid credentials to access this reservation.']);
            }

            session(['reservation_verified_' . $id => true]);
            return redirect()->route('user.reservation.continue', $id);
        }

        return view('user.search.continue', compact('reservation'));
    }

    public function updatePayment(Request $request, $id)
    {
        $reservation = Reservation::findOrFail($id);

        $validated = $request->validate([
            'payment_method' => 'required|in:gcash,paymaya,bank_transfer',
            'payment' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('payment')) {
            $validated['second_payment'] = $request->file('payment')->store('payments', 'public');
        }

        $validated['status'] = 'paid';
        $reservation->update($validated);

        session()->forget('reservation_verified_' . $id);

        return response()->json([
            'success' => true,
            'message' => 'Payment submitted successfully! Your reservation is now being processed.',
            'redirect' => route('home')
        ]);
    }

    private function generateReservationNumber(): string
    {
        do {
            $date = Carbon::now()->format('YmdHis');
            $random = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 6));
            $reservationNumber = "RSV-{$date}-{$random}";
        } while (\App\Models\Booking::where('reservation_number', $reservationNumber)->exists() ||
                 Reservation::where('reservation_number', $reservationNumber)->exists());

        return $reservationNumber;
    }

    protected $casts = [
        'check_in' => 'datetime',
        'check_out' => 'datetime',
        'expires_at' => 'datetime',
    ];
}