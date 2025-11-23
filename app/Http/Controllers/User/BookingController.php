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
        $query = Room::query();

        // Filter by guest capacity
        if ($request->filled('guests')) {
            $query->where('capacity', '>=', $request->guests);
        }

        // Filter by price range
        if ($request->filled('min_price') && $request->filled('max_price')) {
            $query->whereBetween('price', [$request->min_price, $request->max_price]);
        }

        // Filter by availability based on date overlap
        if ($request->filled('check_in') && $request->filled('check_out')) {
            $checkIn = $request->check_in;
            $checkOut = $request->check_out;

            $query->whereDoesntHave('bookings', function ($bookingQuery) use ($checkIn, $checkOut) {
                $bookingQuery->where(function ($dateFilter) use ($checkIn, $checkOut) {
                    $dateFilter
                        ->whereBetween('check_in', [$checkIn, $checkOut])
                        ->orWhereBetween('check_out', [$checkIn, $checkOut])
                        ->orWhere(function ($q) use ($checkIn, $checkOut) {
                            $q->where('check_in', '<=', $checkIn)
                                ->where('check_out', '>=', $checkOut);
                        });
                });
            });
        }

        $rooms = $query->where('availability', '>', 0)->get();
        return view('user.booking.index', compact('rooms'));
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
    public function removeFromCart(Request $request)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$request->room_id])) {
            unset($cart[$request->room_id]);
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
     * Show confirmation page before final submission
     */
    public function showConfirmBooking(Request $request)
    {
        // Validate that payment proof is uploaded (required for admin verification and OCR)
        $request->validate([
            'payment_proof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
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

        $cart_total = collect($cart)->sum(fn($item) => $item['room_price'] * $item['quantity']);
        $tax = $cart_total * 0.12;
        $total = $cart_total + $tax;

        $checkIn = Carbon::parse($request->check_in);
        $checkOut = Carbon::parse($request->check_out);
        $nights = $checkIn->diffInDays($checkOut);

        // --- Payment Proof Logic (now required) ---
        $paymentProofPath = null;
        $paymentProofUrl = null;

        if ($request->hasFile('payment_proof')) {
            // Store temporarily in storage/app/public/temp/payments for preview
            $path = $request->file('payment_proof')->store('temp/payments', 'public');
            $paymentProofPath = $path;
            $paymentProofUrl = asset('storage/' . $path);
        }

        return view('user.booking.confirmbooking', compact(
            'request',
            'cart',
            'cart_total',
            'tax',
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
            'phone' => 'required|string|max:20',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
            'guests' => 'required|integer|min:1',
            'total_price' => 'required|numeric|min:0',
            'agree_terms' => 'required|accepted',
            'payment_method' => 'required|string|in:gcash,paymaya,bank_transfer',
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

        // Check capacity limits before proceeding
        foreach ($cart as $item) {
            $room = Room::findOrFail($item['room_id']);
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
                $newPath = 'payments/' . $filename;
                Storage::disk('public')->move($tempPath, $newPath);
                $paymentPath = $newPath;
            }
        }

        foreach ($cart as $item) {

            $booking = Booking::create([
                'room_id' => $item['room_id'],
                'firstname' => $request->first_name,
                'lastname' => $request->last_name,
                'email' => $request->email,
                'phone_number' => $request->phone,
                'check_in' => $request->check_in,
                'check_out' => $request->check_out,
                'number_of_guests' => $request->guests,
                'special_request' => $request->special_request ?? null,
                'payment_method' => $request->payment_method ?? null,
                'payment' => $paymentPath, // now always defined
                'total_price' => $item['room_price'] * $item['quantity'],
                'status' => 'pending',
                'reservation_number' => $this->generateReservationNumber(),
            ]);

            // Create payment record - now required since payment proof is mandatory
            \App\Models\Payment::create([
                'booking_id' => $booking->id,
                'reference_id' => null, // Will be filled by OCR later
                'customer_name' => $request->first_name . ' ' . $request->last_name,
                'contact_number' => $request->phone,
                'payment_date' => now(),
                'amount' => $item['room_price'] * $item['quantity'],
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
                'name' => $request->first_name . ' ' . $request->last_name,
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



    private function generateReservationNumber(): string
    {
        do {
            $date = Carbon::now()->format('YmdHis');
            $random = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 6));
            $reservationNumber = 'BK-' . $date . '-' . $random;
        } while (
            Booking::where('reservation_number', $reservationNumber)->exists() ||
            \App\Models\Reservation::where('reservation_number', $reservationNumber)->exists()
        );

        return $reservationNumber;
    }
}
