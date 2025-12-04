<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Room;
use App\Models\Booking;
use App\Models\Customer;
use App\Mail\BookingConfirmationMail;
use Carbon\Carbon;

class BookingController extends Controller
{
    /**
     * Show all available rooms and handle filters/search.
     */
    public function view($id)
    {
        $room = Room::findOrFail($id);
        return view('user.booking.view', compact('room'));
    }

    public function index(Request $request)
    {
        // Redirect to rooms page - booking happens from rooms selection
        return redirect()->route('user.rooms.index');
    }

    /**
     * Add room to booking cart (session-based)
     */
    public function addToCart(Request $request)
    {
        $cart = session()->get('cart', []);
        $roomId = $request->room_id;

        if (isset($cart[$roomId])) {
            $cart[$roomId]['quantity'] += $request->quantity;
        } else {
            $cart[$roomId] = [
                'room_id' => $roomId,
                'room_name' => $request->room_name,
                'room_price' => $request->room_price,
                'quantity' => $request->quantity,
            ];
        }

        session(['cart' => $cart]);
        return response()->json(['success' => true, 'cart' => $cart]);
    }

    /**
     * Remove item from cart
     */
    public function removeFromCart(Request $request, $roomId = null)
    {
        $cart = session()->get('cart', []);
        
        // Support both POST with room_id in body and DELETE with room_id in URL
        $id = $roomId ?? $request->room_id;

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return response()->json(['success' => true, 'cart' => $cart]);
    }

    /**
     * Clear entire cart
     */
    public function clearCart()
    {
        session()->forget('cart');
        return response()->json(['success' => true]);
    }

    /**
     * Show booking form with cart items
     */
    public function book()
    {
        $cart = session()->get('cart', []);
        
        // Get dates from session
        $checkIn = session('booking_check_in');
        $checkOut = session('booking_check_out');
        
        // Redirect to rooms if dates not set
        if (!$checkIn || !$checkOut) {
            return redirect()->route('user.rooms.index')->with('error', 'Please select your booking dates first.');
        }
        
        // Redirect to rooms if cart is empty
        if (empty($cart)) {
            return redirect()->route('user.rooms.index')->with('error', 'Please select at least one room.');
        }
        
        // Calculate nights
        $nights = max(1, Carbon::parse($checkIn)->diffInDays(Carbon::parse($checkOut)));
        
        // Calculate total capacity
        $totalCapacity = 0;
        foreach ($cart as $item) {
            $room = Room::find($item['room_id']);
            if ($room) {
                $totalCapacity += $room->capacity * $item['quantity'];
            }
        }

        // Calculate cart totals (per night)
        $cartSubtotal = collect($cart)->sum(fn($item) => $item['room_price'] * $item['quantity']);
        $cartTotal = $cartSubtotal * $nights;

        return view('user.booking.book', compact('cart', 'totalCapacity', 'cartTotal', 'cartSubtotal', 'checkIn', 'checkOut', 'nights'));
    }

    /**
     * Show confirmation page before final submission
     */
    public function showConfirmBooking(Request $request)
    {
        // Validate OTP verification and phone number
        $request->validate([
            'otp_verified' => 'required|in:1',
            'phone' => ['required', 'regex:/^(09|\+639|639)\d{9}$/'],
            'payment_proof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ], [
            'otp_verified.required' => 'Please verify your email with OTP before proceeding.',
            'otp_verified.in' => 'Email verification is required.',
            'phone.regex' => 'Please enter a valid Philippine mobile number (11 digits starting with 09).',
        ]);

        // Validate guest capacity against room capacities
        $cart = session()->get('cart', []);
        $totalGuests = $request->guests ?? 0;
        $totalCapacity = 0;

        foreach ($cart as $item) {
            $room = Room::find($item['room_id']);
            if ($room) {
                $totalCapacity += $room->capacity * $item['quantity'];
            }
        }

        if ($totalGuests > $totalCapacity) {
            return back()->withErrors([
                'guests' => "Number of guests ({$totalGuests}) exceeds total room capacity ({$totalCapacity}). Please adjust your guest count or select rooms with higher capacity."
            ])->withInput();
        }

        // Get dates from session (already validated in booking form)
        $checkIn = Carbon::parse(session('booking_check_in', $request->check_in));
        $checkOut = Carbon::parse(session('booking_check_out', $request->check_out));
        $nights = max(1, $checkIn->diffInDays($checkOut));

        // Calculate totals
        $cartSubtotal = collect($cart)->sum(fn($item) => $item['room_price'] * $item['quantity']);
        $total = $cartSubtotal * $nights;

        // --- Payment Proof Logic (now required) ---
        $paymentProofPath = null;
        $paymentProofUrl = null;

        if ($request->hasFile('payment_proof')) {
            // Store temporarily in storage/app/public/temp/payments for preview
            $path = $request->file('payment_proof')->store('temp/payments', 'public');
            $paymentProofPath = $path;
            $paymentProofUrl = asset("storage/{$path}");
        }

        return view('user.booking.confirmbooking', compact(
            'request',
            'cart',
            'cartSubtotal',
            'total',
            'nights',
            'paymentProofPath',
            'paymentProofUrl'
        ));
    }


    /**
     * Save booking to DB — mark as pending, wait for admin approval
     * Handles payment proof upload
     */
    public function confirmBooking(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => ['required', 'regex:/^(09|\+639|639)\d{9}$/'],
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
            'guests' => 'required|integer|min:1',
            'total_price' => 'required|numeric|min:0',
            'agree_terms' => 'required|accepted',
            'payment_method' => 'required|string|in:gcash,paymaya,bank_transfer',
        ], [
            'phone.regex' => 'Please enter a valid Philippine mobile number (11 digits starting with 09).',
        ]);

        // Custom validation for payment proof (either new upload or existing temp file)
        if (!$request->hasFile('payment_proof') && !$request->filled('payment_proof_temp')) {
            return response()->json([
                'success' => false,
                'message' => 'The payment proof field is required.'
            ]);
        }

        // Validate file if uploaded
        if ($request->hasFile('payment_proof')) {
            $request->validate([
                'payment_proof' => 'file|mimes:jpg,jpeg,png,pdf|max:5120',
            ]);
        }

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return response()->json(['success' => false, 'message' => 'Cart is empty.']);
        }

        // Get dates from session
        $checkInDate = session('booking_check_in', $request->check_in);
        $checkOutDate = session('booking_check_out', $request->check_out);
        
        // Calculate nights
        $checkIn = Carbon::parse($checkInDate);
        $checkOut = Carbon::parse($checkOutDate);
        $nights = max(1, $checkIn->diffInDays($checkOut));

        // Check for overlapping bookings and availability
        foreach ($cart as $item) {
            $room = Room::findOrFail($item['room_id']);
            
            // Check if room has conflicting bookings
            $conflictingBookings = Booking::where('room_id', $room->id)
                ->whereIn('status', ['confirmed', 'pending'])
                ->where(function ($query) use ($checkInDate, $checkOutDate) {
                    $query->whereBetween('check_in', [$checkInDate, $checkOutDate])
                        ->orWhereBetween('check_out', [$checkInDate, $checkOutDate])
                        ->orWhere(function ($q) use ($checkInDate, $checkOutDate) {
                            $q->where('check_in', '<=', $checkInDate)
                                ->where('check_out', '>=', $checkOutDate);
                        });
                })
                ->exists();
            
            if ($conflictingBookings) {
                return response()->json([
                    'success' => false,
                    'message' => "Room {$room->name} is no longer available for the selected dates. Please select different dates or rooms."
                ]);
            }
            
            if ($room->availability < $item['quantity']) {
                return response()->json([
                    'success' => false,
                    'message' => "Insufficient availability for {$room->name}. Only {$room->availability} room(s) left."
                ]);
            }
        }

        $createdBookings = collect();

        // Handle payment proof — since it's now required, it should always be present
        $paymentPath = null;
        if ($request->hasFile('payment_proof')) {
            // Store the uploaded file directly to permanent storage
            $path = $request->file('payment_proof')->store('payments', 'public');
            $paymentPath = $path;
        } elseif ($request->filled('payment_proof_temp')) {
            // Move from temp to permanent storage
            $tempPath = $request->payment_proof_temp;
            if (Storage::disk('public')->exists($tempPath)) {
                $filename = basename($tempPath);
                $newPath = "payments/{$filename}";
                Storage::disk('public')->move($tempPath, $newPath);
                $paymentPath = $newPath;
            }
        }
        $customer = Customer::updateOrCreate(
            ['email' => $request->email],
            [
                'firstname' => $request->first_name,
                'lastname' => $request->last_name,
                'phone_number' => $request->phone
            ]
        );

        foreach ($cart as $item) {
            // Calculate total price including nights
            $itemTotal = $item['room_price'] * $item['quantity'] * $nights;

            $booking = Booking::create([
                'room_id' => $item['room_id'],
                'customer_id' => $customer->id,
                'check_in' => $checkInDate,
                'check_out' => $checkOutDate,
                'number_of_guests' => $request->guests,
                'special_request' => $request->special_request ?? null,
                'payment_method' => $request->payment_method ?? null,
                'payment' => $paymentPath,
                'total_price' => $itemTotal,
                'status' => 'pending',
                'reservation_number' => $this->generateReservationNumber(),
            ]);


            // Create payment record - now required since payment proof is mandatory
            \App\Models\Payment::create([
                'booking_id' => $booking->id,
                'reference_id' => null, // Will be filled by OCR later
                'customer_name' => "{$request->first_name} {$request->last_name}",
                'contact_number' => $request->phone,
                'payment_date' => now(),
                'amount' => $itemTotal,
                'status' => 'pending',
                'payment_method' => $request->payment_method,
                'notes' => 'Payment proof uploaded'
            ]);

            $createdBookings->push($booking);

            // Reduce room availability
            $room = Room::findOrFail($item['room_id']);
            $room->availability -= $item['quantity'];
            $room->save();
        }


        // Create notification for admin
        \App\Models\Notification::create([
            'type' => 'booking',
            'title' => 'New Booking Request',
            'message' => "New booking from {$request->first_name} {$request->last_name} for {$createdBookings->count()} room(s)",
            'data' => [
                'booking_id' => $createdBookings->first()->id,
                'name' => "{$request->first_name} {$request->last_name}",
                'email' => $request->email,
                'phone' => $request->phone,
                'check_in' => $request->check_in,
                'check_out' => $request->check_out,
                'guests' => $request->guests,
            ],
            'read' => false,
        ]);

        // Clear the cart
        session()->forget('cart');

        return response()->json([
            'success' => true,
            'message' => 'Booking submitted successfully! Please wait for admin confirmation via email or SMS.',
            'booking_id' => $createdBookings->first()->id,
            'reservation_number' => $createdBookings->first()->reservation_number,
        ]);
    }

    /**
     * Keep email logic for admin confirmation stage
     */
    private function sendBookingConfirmationEmail(Booking $booking, Request $request)
    {
        try {
            $bookingDetails = [
                'guest_name' => trim(($request->first_name ?? 'Guest') . ' ' . ($request->last_name ?? '')),
                'guest_email' => $request->email ?? 'guest@example.com',
                'guest_phone' => $request->phone ?? 'N/A',
                'guests' => $request->guests ?? 1,
            ];

            Mail::to($bookingDetails['guest_email'])->send(
                new BookingConfirmationMail($booking, $bookingDetails)
            );

            Log::info('Booking confirmation email sent successfully', [
                'booking_id' => $booking->id,
                'email' => $bookingDetails['guest_email'],
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send booking confirmation email', [
                'booking_id' => $booking->id,
                'email' => $bookingDetails['guest_email'] ?? 'unknown',
                'error' => $e->getMessage(),
            ]);
        }
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
            // Generate 6-digit OTP
            $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            
            // Store OTP in session with 1-minute expiry
            $expiryTime = now()->addMinute();
            session([
                'otp_code' => $otp,
                'otp_email' => $request->email,
                'otp_expiry' => $expiryTime,
                'otp_sent_at' => now()->timestamp
            ]);

            // Send OTP via email
            Mail::raw("Your OTP code for Laiya Grande booking is: {$otp}\n\nThis code will expire in 1 minute.", function ($message) use ($request) {
                $message->to($request->email)
                    ->subject('Email Verification - Laiya Grande Resort');
            });

            Log::info('OTP sent', ['email' => $request->email, 'otp' => $otp, 'expiry' => $expiryTime]);

            return response()->json([
                'success' => true,
                'message' => 'OTP sent successfully to your email',
                'sent_at' => now()->timestamp
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send OTP', ['error' => $e->getMessage()]);
            
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

        $sessionOtp = session('otp_code');
        $sessionEmail = session('otp_email');
        $otpExpiry = session('otp_expiry');

        Log::info('OTP Verification Attempt', [
            'input_email' => $request->email,
            'input_otp' => $request->otp,
            'session_email' => $sessionEmail,
            'session_otp' => $sessionOtp,
            'expiry' => $otpExpiry
        ]);

        // Check if OTP exists
        if (!$sessionOtp || !$sessionEmail || !$otpExpiry) {
            return response()->json([
                'success' => false,
                'message' => 'No OTP found. Please request a new one.'
            ]);
        }

        // Check if OTP expired
        if (now()->greaterThan($otpExpiry)) {
            session()->forget(['otp_code', 'otp_email', 'otp_expiry', 'otp_sent_at']);
            return response()->json([
                'success' => false,
                'message' => 'OTP has expired. Please request a new one.'
            ]);
        }

        // Check if email matches
        if ($sessionEmail !== $request->email) {
            return response()->json([
                'success' => false,
                'message' => 'Email does not match.'
            ]);
        }

        // Verify OTP
        if ($sessionOtp === $request->otp) {
            session(['email_verified' => true]);
            session()->forget(['otp_code', 'otp_email', 'otp_expiry']);
            
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

    private function generateReservationNumber(): string
    {
        do {
            $date = Carbon::now()->format('YmdHis');
            $random = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 6));
            $reservationNumber = "BK-{$date}-{$random}";
        } while (
            Booking::where('reservation_number', $reservationNumber)->exists() ||
            \App\Models\Reservation::where('reservation_number', $reservationNumber)->exists()
        );

        return $reservationNumber;
    }
}
