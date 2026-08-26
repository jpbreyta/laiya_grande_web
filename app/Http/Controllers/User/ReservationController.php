<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\BookingSubmissionRequest;
use App\Http\Requests\User\OtpSendRequest;
use App\Http\Requests\User\OtpVerifyRequest;
use App\Http\Requests\User\PaymentContinuationRequest;
use App\Http\Requests\User\ReservationUpdateRequest;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Room;
use App\Models\RoomRate;
use App\Services\Booking\BookingService;
use App\Services\Booking\CartService;
use App\Services\Booking\PaymentProofService;
use App\Services\Booking\RoomAvailabilityService;
use App\Services\Communication\OnlineBookingOtpService;
use App\Services\Communication\OtpService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ReservationController extends Controller
{
    private const LOOKUP_OTP_PURPOSE = 'booking_lookup';

    public function __construct(
        private readonly CartService $cart,
        private readonly BookingService $bookings,
        private readonly PaymentProofService $paymentProofs,
        private readonly RoomAvailabilityService $availability,
        private readonly OtpService $otp,
        private readonly OnlineBookingOtpService $bookingOtp
    ) {
    }

    public function index(): View
    {
        return view('user.reserve.reservation');
    }

    public function create(): View
    {
        $rooms = Room::query()
            ->available()
            ->with([
                'activeRates' => fn ($query) => $query->effectiveOn(today())->orderBy('price'),
                'roomImages',
            ])
            ->orderBy('name')
            ->get();

        return view('user.reservation.create', compact('rooms'));
    }

    /**
     * Create reservation-style online bookings through the shared service.
     */
    public function store(BookingSubmissionRequest $request): JsonResponse
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

        $created = $this->bookings->createFromCart(
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
            specialRequest: $request->input('special_requests', $request->input('special_request')),
            paymentMethod: $request->string('payment_method')->toString(),
            paymentProof: $this->paymentProofs->persist($proof),
            numberPrefix: 'RSV'
        );

        $first = $created->first();
        $this->otp->grantBookingAccess($first);

        session([
            'reservation_number' => $first->booking_number,
            'reservation_data' => [
                'first_name' => $request->string('first_name')->toString(),
                'last_name' => $request->string('last_name')->toString(),
                'email' => $email,
                'phone' => $request->string('phone')->toString(),
                'check_in' => $request->date('check_in')->toDateString(),
                'check_out' => $request->date('check_out')->toDateString(),
                'guests' => $request->integer('guests'),
                'special_requests' => $request->input('special_requests', $request->input('special_request')),
                'payment_method' => $request->string('payment_method')->toString(),
            ],
        ]);

        $this->cart->clear();

        return response()->json([
            'success' => true,
            'reservation_id' => $first->id,
            'reservation_number' => $first->booking_number,
            'message' => 'Reservation submitted successfully. Payment verification is pending.',
            'redirect_url' => route('user.reservation.review'),
        ]);
    }

    public function review(): View|RedirectResponse
    {
        if (! session()->has('reservation_number')) {
            return redirect()->route('booking.index')->with('error', 'No reservation was found.');
        }

        return view('user.reserve.review');
    }

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

    public function show(int $id): View
    {
        $reservation = $this->findReservation($id)->load(['room', 'roomRate', 'payments', 'customer']);
        $this->ensureVerified($reservation);

        return view('user.reservation.show', compact('reservation'));
    }

    public function edit(int $id): View
    {
        $reservation = $this->findReservation($id)->load(['room', 'roomRate', 'customer']);
        $this->ensureVerified($reservation);

        $rooms = Room::query()
            ->available()
            ->with(['activeRates' => fn ($query) => $query->effectiveOn(today())->orderBy('price')])
            ->orderBy('name')
            ->get();

        return view('user.reservation.edit', compact('reservation', 'rooms'));
    }

    /**
     * Update a pending reservation after OTP-based ownership verification.
     */
    public function update(ReservationUpdateRequest $request, int $id): RedirectResponse
    {
        $reservation = $this->findReservation($id);
        $this->ensureVerified($reservation);

        if ($reservation->status !== 'pending') {
            throw ValidationException::withMessages([
                'reservation' => 'Only pending reservations can be edited.',
            ]);
        }

        DB::transaction(function () use ($request, $reservation): void {
            $lockedReservation = Booking::query()->lockForUpdate()->findOrFail($reservation->id);
            $room = Room::query()->lockForUpdate()->findOrFail($request->integer('room_id'));
            $checkIn = Carbon::parse($request->input('check_in'));
            $checkOut = Carbon::parse($request->input('check_out'));

            if (! $this->availability->hasUnits($room, $checkIn, $checkOut, 1, $lockedReservation->id)) {
                throw ValidationException::withMessages([
                    'room_id' => 'The selected room is unavailable for these dates.',
                ]);
            }

            if ($request->integer('number_of_guests') > $room->capacity) {
                throw ValidationException::withMessages([
                    'number_of_guests' => "This room can accommodate only {$room->capacity} guest(s).",
                ]);
            }

            $rate = RoomRate::query()
                ->whereKey($request->integer('room_rate_id'))
                ->where('room_id', $room->id)
                ->active()
                ->effectiveOn($checkIn)
                ->first();

            if (! $rate) {
                throw ValidationException::withMessages([
                    'room_rate_id' => 'The selected room rate is invalid or inactive.',
                ]);
            }

            $nights = max(1, $checkIn->diffInDays($checkOut));
            if ($nights < $rate->minimum_nights) {
                throw ValidationException::withMessages([
                    'check_out' => "This rate requires at least {$rate->minimum_nights} night(s).",
                ]);
            }

            $customer = $lockedReservation->customer;
            $email = mb_strtolower($request->string('email')->toString());

            $emailInUse = Customer::query()
                ->where('email', $email)
                ->where('id', '<>', $customer->id)
                ->exists();

            if ($emailInUse) {
                throw ValidationException::withMessages([
                    'email' => 'This email address belongs to another customer.',
                ]);
            }

            $customer->update([
                'first_name' => $request->string('first_name')->toString(),
                'last_name' => $request->string('last_name')->toString(),
                'email' => $email,
                'phone_number' => $request->string('phone')->toString(),
            ]);

            $lockedReservation->update([
                'room_id' => $room->id,
                'room_rate_id' => $rate->id,
                'check_in' => $checkIn->toDateString(),
                'check_out' => $checkOut->toDateString(),
                'number_of_guests' => $request->integer('number_of_guests'),
                'special_request' => $request->input('special_request'),
                'quoted_total' => (float) $rate->price * $nights,
            ]);
        }, 3);

        return redirect()->route('user.reservation.index')
            ->with('success', 'Reservation updated successfully.');
    }

    /**
     * Cancel instead of deleting financial and audit records.
     */
    public function destroy(int $id): RedirectResponse
    {
        $reservation = $this->findReservation($id);
        $this->ensureVerified($reservation);

        if (! in_array($reservation->status, ['pending', 'confirmed'], true)) {
            throw ValidationException::withMessages([
                'reservation' => 'This reservation can no longer be cancelled.',
            ]);
        }

        $reservation->update(['status' => 'cancelled']);

        return redirect()->route('user.reservation.index')
            ->with('success', 'Reservation cancelled successfully.');
    }

    /**
     * Validate booking contact details, then send an OTP for payment access.
     */
    public function continuePaying(Request $request, int $id): View|RedirectResponse
    {
        $reservation = $this->findReservation($id)->load('customer');

        if ($request->isMethod('post')) {
            $validated = $request->validate([
                'email' => ['required', 'email:rfc'],
                'phone_number' => ['required', 'string', 'max:30'],
            ]);

            if (
                mb_strtolower($validated['email']) !== mb_strtolower($reservation->customer->email)
                || $this->normalizePhone($validated['phone_number']) !== $this->normalizePhone($reservation->customer->phone_number)
            ) {
                throw ValidationException::withMessages([
                    'email' => 'The contact information does not match this reservation.',
                ]);
            }

            $this->otp->sendForBooking(
                $reservation,
                self::LOOKUP_OTP_PURPOSE,
                'email',
                hash('sha256', $reservation->id . '|' . $request->ip())
            );

            return redirect()->route('search.verifyOtpForm', [
                'reservation_code' => $reservation->booking_number,
                'method' => 'email',
            ])->with('alert', [
                'type' => 'success',
                'message' => 'An OTP was sent to the booking email.',
            ]);
        }

        $this->ensureVerified($reservation);

        return view('user.search.continue', compact('reservation'));
    }

    /**
     * Submit a new payment without changing booking status prematurely.
     */
    public function updatePayment(PaymentContinuationRequest $request, int $id): JsonResponse
    {
        $reservation = $this->findReservation($id);
        $this->ensureVerified($reservation);

        $payment = $this->bookings->submitAdditionalPayment(
            booking: $reservation,
            paymentMethod: $request->string('payment_method')->toString(),
            requestedAmount: $request->filled('amount_paid') ? (float) $request->input('amount_paid') : null,
            paymentProof: $this->paymentProofs->persist($request->file('payment_proof'))
        );

        return response()->json([
            'success' => true,
            'message' => 'Payment submitted successfully and is awaiting verification.',
            'payment_id' => $payment->id,
            'redirect' => route('home'),
        ]);
    }

    private function findReservation(int $id): Booking
    {
        return Booking::query()
            ->where('source', 'online')
            ->findOrFail($id);
    }

    private function ensureVerified(Booking $booking): void
    {
        abort_unless($this->otp->hasBookingAccess($booking), 403, 'OTP verification is required.');
    }

    private function normalizePhone(?string $phone): string
    {
        return preg_replace('/\D+/', '', (string) $phone) ?? '';
    }
}
