<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\BookingLookupRequest;
use App\Http\Requests\User\OtpVerifyRequest;
use App\Models\Booking;
use App\Services\Communication\OtpService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class SearchController extends Controller
{
    private const OTP_PURPOSE = 'booking_lookup';

    public function __construct(
        private readonly OtpService $otp
    ) {
    }

    /**
     * Validate booking contact details before sending or checking an OTP.
     */
    public function validateContactInformation(BookingLookupRequest $request): JsonResponse|RedirectResponse
    {
        $booking = $this->findByCode($request->string('reservation_code')->toString());
        $this->validateContact($booking, $request->string('email')->toString(), $request->string('phone')->toString());

        if ($request->filled('otp')) {
            if (! $this->otp->verifyForBooking($booking, self::OTP_PURPOSE, $request->string('otp')->toString())) {
                throw ValidationException::withMessages([
                    'otp' => 'The OTP is invalid, expired, or has reached its attempt limit.',
                ]);
            }

            $this->otp->grantBookingAccess($booking);

            return $this->successResponse(
                $request,
                'Verification successful.',
                route('search.show', ['id' => $booking->id, 'type' => 'booking'])
            );
        }

        $this->otp->sendForBooking(
            booking: $booking,
            purpose: self::OTP_PURPOSE,
            channel: 'email',
            requestKey: hash('sha256', $booking->id . '|' . $request->ip())
        );

        return $this->successResponse(
            $request,
            'OTP sent successfully to the booking email.',
            route('search.verifyOtpForm', [
                'reservation_code' => $booking->booking_number,
                'method' => 'email',
            ])
        );
    }

    /**
     * Resend an OTP to the selected booking channel.
     */
    public function sendOtp(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'reservation_code' => ['required', 'string', 'max:50'],
            'otp_method' => ['nullable', 'in:email,sms'],
        ]);

        $booking = $this->findByCode($validated['reservation_code']);
        $channel = $validated['otp_method'] ?? 'email';

        $this->otp->sendForBooking(
            booking: $booking,
            purpose: self::OTP_PURPOSE,
            channel: $channel,
            requestKey: hash('sha256', $booking->id . '|' . $channel . '|' . $request->ip())
        );

        return response()->json([
            'success' => true,
            'message' => "OTP sent successfully by {$channel}.",
        ]);
    }

    /**
     * Verify a booking OTP and grant temporary session access.
     */
    public function verifyOtp(OtpVerifyRequest $request): JsonResponse
    {
        $code = (string) ($request->input('reservation_code') ?: $request->input('booking_number'));
        if ($code === '') {
            throw ValidationException::withMessages([
                'reservation_code' => 'The booking code is required.',
            ]);
        }

        $booking = $this->findByCode($code);

        if (! $this->otp->verifyForBooking($booking, self::OTP_PURPOSE, $request->string('otp')->toString())) {
            throw ValidationException::withMessages([
                'otp' => 'The OTP is invalid, expired, or has reached its attempt limit.',
            ]);
        }

        $this->otp->grantBookingAccess($booking);

        return response()->json([
            'success' => true,
            'message' => 'OTP verified successfully.',
            'redirect_url' => route('search.show', ['id' => $booking->id, 'type' => 'booking']),
        ]);
    }

    public function index(): View
    {
        return view('user.search.index');
    }

    /**
     * Confirm that a booking code exists before selecting an OTP method.
     */
    public function searchByCode(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'reservation_code' => ['required', 'string', 'max:50'],
        ]);

        $booking = $this->findByCode($validated['reservation_code']);

        return response()->json([
            'success' => true,
            'redirect_url' => route('search.selectOtpMethod', [
                'reservation_code' => $booking->booking_number,
            ]),
        ]);
    }

    public function selectOtpMethod(Request $request): View|RedirectResponse
    {
        $code = (string) $request->query('reservation_code');

        if ($code === '') {
            return redirect()->route('search.index')->with('alert', [
                'type' => 'error',
                'message' => 'Enter a valid booking code.',
            ]);
        }

        $booking = $this->findByCode($code);
        $reservationCode = $booking->booking_number;

        return view('user.search.select-otp-method', compact('reservationCode'));
    }

    /**
     * Send an OTP through the customer-selected channel.
     */
    public function sendOtpByMethod(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'reservation_code' => ['required', 'string', 'max:50'],
            'otp_method' => ['required', 'in:email,sms'],
        ]);

        $booking = $this->findByCode($validated['reservation_code']);

        $this->otp->sendForBooking(
            booking: $booking,
            purpose: self::OTP_PURPOSE,
            channel: $validated['otp_method'],
            requestKey: hash('sha256', $booking->id . '|' . $validated['otp_method'] . '|' . $request->ip())
        );

        return response()->json([
            'success' => true,
            'message' => "OTP sent successfully by {$validated['otp_method']}.",
            'redirect_url' => route('search.verifyOtpForm', [
                'reservation_code' => $booking->booking_number,
                'method' => $validated['otp_method'],
            ]),
        ]);
    }

    /**
     * Display a booking only during an active verified session.
     */
    public function show(int $id, string $type = 'booking'): View
    {
        $booking = Booking::query()
            ->with(['room', 'roomRate', 'customer', 'payments', 'guestStay'])
            ->findOrFail($id);

        $this->ensureVerified($booking);

        // Preserve the old view variables while using the normalized booking table.
        $data = $booking;
        $type = $booking->booking_number && str_starts_with($booking->booking_number, 'RSV-')
            ? 'reservation'
            : 'booking';

        return view('user.search.view', compact('data', 'type'));
    }

    /**
     * Display the secure payment continuation form.
     */
    public function continuePayment(int $id, string $type = 'booking'): View
    {
        $booking = Booking::query()
            ->with(['room', 'roomRate', 'customer', 'payments'])
            ->findOrFail($id);

        $this->ensureVerified($booking);

        if (! in_array($booking->status, ['pending', 'confirmed'], true)) {
            throw ValidationException::withMessages([
                'booking' => 'This booking no longer accepts payments.',
            ]);
        }

        $reservation = $booking;

        return view('user.search.continue', compact('reservation'));
    }

    private function findByCode(string $code): Booking
    {
        return Booking::query()
            ->with('customer')
            ->where('booking_number', trim($code))
            ->firstOrFail();
    }

    private function validateContact(Booking $booking, string $email, string $phone): void
    {
        $emailMatches = mb_strtolower(trim($email)) === mb_strtolower($booking->customer->email);
        $phoneMatches = $this->normalizePhone($phone) === $this->normalizePhone($booking->customer->phone_number);

        if (! $emailMatches || ! $phoneMatches) {
            throw ValidationException::withMessages([
                'email' => 'The contact information does not match this booking.',
            ]);
        }
    }

    private function ensureVerified(Booking $booking): void
    {
        abort_unless($this->otp->hasBookingAccess($booking), 403, 'OTP verification is required.');
    }

    private function normalizePhone(?string $phone): string
    {
        return preg_replace('/\D+/', '', (string) $phone) ?? '';
    }

    private function successResponse(Request $request, string $message, string $redirectUrl): JsonResponse|RedirectResponse
    {
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'redirect_url' => $redirectUrl,
            ]);
        }

        return redirect($redirectUrl)->with('alert', [
            'type' => 'success',
            'message' => $message,
        ]);
    }
}
