<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\BookingPreviewRequest;
use App\Http\Requests\User\BookingSubmissionRequest;
use App\Http\Requests\User\CartAddRequest;
use App\Http\Requests\User\OtpSendRequest;
use App\Http\Requests\User\OtpVerifyRequest;
use App\Mail\BookingConfirmationMail;
use App\Models\Booking;
use App\Models\CommunicationSetting;
use App\Models\Customer;
use App\Models\Room;
use App\Services\Booking\BookingService;
use App\Services\Booking\CartService;
use App\Services\Booking\PaymentProofService;
use App\Services\Communication\OnlineBookingOtpService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function __construct(
        private readonly CartService $cart,
        private readonly BookingService $bookings,
        private readonly PaymentProofService $paymentProofs,
        private readonly OnlineBookingOtpService $bookingOtp
    ) {
    }

    public function view(int $id): View
    {
        $room = Room::query()
            ->available()
            ->with([
                'activeRates' => fn ($query) => $query->effectiveOn(today())->orderBy('price'),
                'roomImages',
                'amenities',
            ])
            ->findOrFail($id);

        return view('user.booking.view', compact('room'));
    }

    public function index(): RedirectResponse
    {
        return redirect()->route('user.rooms.index');
    }

    /**
     * Compatibility endpoint for older booking pages that add cart items here.
     */
    public function addToCart(CartAddRequest $request): JsonResponse
    {
        $cart = $this->cart->add(
            roomId: $request->integer('room_id'),
            roomRateId: $request->filled('room_rate_id') ? $request->integer('room_rate_id') : null,
            quantity: $request->integer('quantity', 1)
        );

        return response()->json(['success' => true, 'cart' => $cart]);
    }

    public function removeFromCart(Request $request, ?int $roomId = null): JsonResponse
    {
        $validated = $request->validate([
            'room_id' => ['nullable', 'integer'],
        ]);

        $id = $roomId ?? (int) ($validated['room_id'] ?? 0);

        return response()->json([
            'success' => true,
            'cart' => $this->cart->remove($id),
        ]);
    }

    public function clearCart(): JsonResponse
    {
        $this->cart->clear();

        return response()->json(['success' => true]);
    }

    /**
     * Display the checkout form using prices saved by the server.
     */
    public function book(): View|RedirectResponse
    {
        $cart = $this->cart->all();
        $checkIn = session('booking_check_in');
        $checkOut = session('booking_check_out');

        if (! $checkIn || ! $checkOut) {
            return redirect()->route('user.rooms.index')
                ->with('error', 'Please select your booking dates first.');
        }

        if ($cart === []) {
            return redirect()->route('user.rooms.index')
                ->with('error', 'Please select at least one room.');
        }

        $nights = max(1, Carbon::parse($checkIn)->diffInDays(Carbon::parse($checkOut)));
        $totalCapacity = collect($cart)->sum(function (array $item): int {
            $room = Room::query()->find($item['room_id']);
            return $room ? $room->capacity * (int) $item['quantity'] : 0;
        });
        $cartSubtotal = collect($cart)->sum(
            fn (array $item): float => (float) $item['room_price'] * (int) $item['quantity']
        );
        $cartTotal = $cartSubtotal * $nights;

        return view('user.booking.book', compact(
            'cart',
            'totalCapacity',
            'cartTotal',
            'cartSubtotal',
            'checkIn',
            'checkOut',
            'nights'
        ));
    }

    /**
     * Validate checkout details and move the proof to private temporary storage.
     */
    public function showConfirmBooking(BookingPreviewRequest $request): View
    {
        $cart = $this->cart->all();
        if ($cart === []) {
            throw ValidationException::withMessages(['cart' => 'The cart is empty.']);
        }

        if (! $this->bookingOtp->isVerified($request->string('email')->toString())) {
            throw ValidationException::withMessages([
                'email' => 'Verify this email address with an OTP before continuing.',
            ]);
        }

        $checkInValue = session('booking_check_in', $request->input('check_in'));
        $checkOutValue = session('booking_check_out', $request->input('check_out'));

        if (! $checkInValue || ! $checkOutValue) {
            throw ValidationException::withMessages([
                'check_in' => 'Select valid check-in and check-out dates.',
            ]);
        }

        $checkIn = Carbon::parse($checkInValue);
        $checkOut = Carbon::parse($checkOutValue);
        $nights = max(1, $checkIn->diffInDays($checkOut));
        $totalCapacity = collect($cart)->sum(function (array $item): int {
            $room = Room::query()->find($item['room_id']);
            return $room ? $room->capacity * (int) $item['quantity'] : 0;
        });

        if ($request->integer('guests') > $totalCapacity) {
            throw ValidationException::withMessages([
                'guests' => "The selected rooms can accommodate only {$totalCapacity} guest(s).",
            ]);
        }

        $cartSubtotal = collect($cart)->sum(
            fn (array $item): float => (float) $item['room_price'] * (int) $item['quantity']
        );
        $total = $cartSubtotal * $nights;
        $temporaryProof = $this->paymentProofs->storeTemporary($request->file('payment_proof'));

        return view('user.booking.confirmbooking', [
            'request' => $request,
            'cart' => $cart,
            'cartSubtotal' => $cartSubtotal,
            'total' => $total,
            'nights' => $nights,
            'paymentProofPath' => $temporaryProof['path'],
            // Payment proofs are private and must not be exposed with asset().
            'paymentProofUrl' => null,
        ]);
    }

    /**
     * Create bookings and pending payments in one atomic transaction.
     */
    public function confirmBooking(BookingSubmissionRequest $request): JsonResponse
    {
        $email = mb_strtolower($request->string('email')->toString());

        if (! $this->bookingOtp->isVerified($email)) {
            throw ValidationException::withMessages([
                'email' => 'Email OTP verification is missing or has expired.',
            ]);
        }

        $proof = $request->hasFile('payment_proof')
            ? $request->file('payment_proof')
            : $request->string('payment_proof_temp')->toString();
        $proofMetadata = $this->paymentProofs->persist($proof);

        $createdBookings = $this->bookings->createFromCart(
            cart: $this->cart->all(),
            customerData: [
                'first_name' => $request->string('first_name')->toString(),
                'last_name' => $request->string('last_name')->toString(),
                'email' => $email,
                'phone' => $request->string('phone')->toString(),
                'data_consent' => $request->boolean('data_consent', true),
            ],
            checkInDate: $request->date('check_in')->toDateString(),
            checkOutDate: $request->date('check_out')->toDateString(),
            totalGuests: $request->integer('guests'),
            specialRequest: $request->input('special_request'),
            paymentMethod: $request->string('payment_method')->toString(),
            paymentProof: $proofMetadata,
            numberPrefix: 'BKG'
        );

        $this->cart->clear();
        $firstBooking = $createdBookings->first();
        $this->sendBookingConfirmationEmail($firstBooking, $createdBookings->count());

        return response()->json([
            'success' => true,
            'message' => 'Booking submitted successfully. Payment verification is pending.',
            'booking_id' => $firstBooking->id,
            'booking_number' => $firstBooking->booking_number,
            'reservation_number' => $firstBooking->booking_number,
            'bookings_created' => $createdBookings->count(),
        ]);
    }

    /**
     * Send a hashed OTP linked to the customer record.
     */
    public function sendOTP(OtpSendRequest $request): JsonResponse
    {
        $this->bookingOtp->send(
            email: $request->string('email')->toString(),
            firstName: $request->input('first_name'),
            lastName: $request->input('last_name'),
            phone: $request->input('phone'),
            ipAddress: (string) $request->ip()
        );

        return response()->json([
            'success' => true,
            'message' => 'OTP sent successfully to your email.',
        ]);
    }

    /**
     * Verify the hashed customer OTP and grant a short checkout session.
     */
    public function verifyOTP(OtpVerifyRequest $request): JsonResponse
    {
        $this->bookingOtp->verify(
            email: (string) $request->input('email'),
            code: $request->string('otp')->toString()
        );

        return response()->json([
            'success' => true,
            'message' => 'Email verified successfully.',
        ]);
    }

    private function sendBookingConfirmationEmail(Booking $booking, int $roomUnits): void
    {
        if (! CommunicationSetting::instance()->email_booking_confirmation_enabled) {
            return;
        }

        try {
            Mail::to($booking->customer->email)->send(
                new BookingConfirmationMail($booking, [
                    'guest_name' => $booking->customer->full_name,
                    'guest_email' => $booking->customer->email,
                    'guest_phone' => $booking->customer->phone_number,
                    'guests' => $booking->number_of_guests,
                    'room_units' => $roomUnits,
                ])
            );
        } catch (\Throwable $exception) {
            // Email failure must not roll back an already committed booking.
            Log::error('Booking confirmation email failed.', [
                'booking_id' => $booking->id,
                'error' => $exception->getMessage(),
            ]);
        }
    }
}
