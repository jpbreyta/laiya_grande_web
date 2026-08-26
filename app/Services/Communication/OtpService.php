<?php

namespace App\Services\Communication;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\Otp;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use RuntimeException;

class OtpService
{
    public function __construct(
        private readonly OtpDeliveryService $delivery
    ) {
    }

    /**
     * Create and send an OTP linked to a customer.
     */
    public function sendForCustomer(
        Customer $customer,
        string $purpose,
        string $channel,
        string $recipient,
        string $requestKey
    ): Otp {
        return $this->createAndSend(
            customerId: $customer->id,
            bookingId: null,
            purpose: $purpose,
            channel: $channel,
            recipient: $recipient,
            requestKey: $requestKey
        );
    }

    /**
     * Create and send an OTP linked to an existing booking.
     */
    public function sendForBooking(
        Booking $booking,
        string $purpose,
        string $channel,
        string $requestKey
    ): Otp {
        $recipient = $channel === 'sms'
            ? (string) $booking->customer?->phone_number
            : (string) $booking->customer?->email;

        if ($recipient === '') {
            throw ValidationException::withMessages([
                'otp_method' => "No {$channel} recipient is available for this booking.",
            ]);
        }

        return $this->createAndSend(
            customerId: $booking->customer_id,
            bookingId: $booking->id,
            purpose: $purpose,
            channel: $channel,
            recipient: $recipient,
            requestKey: $requestKey
        );
    }

    /**
     * Verify the latest active OTP for a customer.
     */
    public function verifyForCustomer(Customer $customer, string $purpose, string $code): bool
    {
        return $this->verify(
            Otp::query()
                ->where('customer_id', $customer->id)
                ->whereNull('booking_id')
                ->where('purpose', $purpose),
            $code
        );
    }

    /**
     * Verify the latest active OTP for a booking.
     */
    public function verifyForBooking(Booking $booking, string $purpose, string $code): bool
    {
        return $this->verify(
            Otp::query()
                ->where('booking_id', $booking->id)
                ->where('purpose', $purpose),
            $code
        );
    }

    /**
     * Grant temporary session access after successful OTP verification.
     */
    public function grantBookingAccess(Booking $booking, int $minutes = 30): void
    {
        session()->put($this->bookingSessionKey($booking), now()->addMinutes($minutes)->timestamp);
    }

    public function hasBookingAccess(Booking $booking): bool
    {
        $expiresAt = (int) session($this->bookingSessionKey($booking), 0);

        if ($expiresAt <= now()->timestamp) {
            session()->forget($this->bookingSessionKey($booking));
            return false;
        }

        return true;
    }

    public function grantCustomerPurpose(Customer $customer, string $purpose, int $minutes = 20): void
    {
        session()->put($this->customerSessionKey($customer, $purpose), now()->addMinutes($minutes)->timestamp);
    }

    public function hasCustomerPurpose(Customer $customer, string $purpose): bool
    {
        $key = $this->customerSessionKey($customer, $purpose);
        $expiresAt = (int) session($key, 0);

        if ($expiresAt <= now()->timestamp) {
            session()->forget($key);
            return false;
        }

        return true;
    }

    private function createAndSend(
        ?int $customerId,
        ?int $bookingId,
        string $purpose,
        string $channel,
        string $recipient,
        string $requestKey
    ): Otp {
        $rateKey = "otp:{$purpose}:{$channel}:{$requestKey}";

        if (RateLimiter::tooManyAttempts($rateKey, 3)) {
            throw ValidationException::withMessages([
                'otp' => 'Too many OTP requests. Please wait before requesting another code.',
            ]);
        }

        RateLimiter::hit($rateKey, 600);
        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $challenge = DB::transaction(function () use (
            $customerId,
            $bookingId,
            $purpose,
            $channel,
            $recipient,
            $code
        ): Otp {
            Otp::query()
                ->where('purpose', $purpose)
                ->where('channel', $channel)
                ->when($bookingId !== null, fn ($query) => $query->where('booking_id', $bookingId))
                ->when($bookingId === null, fn ($query) => $query->where('customer_id', $customerId)->whereNull('booking_id'))
                ->whereNull('consumed_at')
                ->update(['consumed_at' => now()]);

            return Otp::create([
                'customer_id' => $customerId,
                'booking_id' => $bookingId,
                'purpose' => $purpose,
                'channel' => $channel,
                'recipient' => $recipient,
                'code_hash' => Hash::make($code),
                'attempts' => 0,
                'max_attempts' => 5,
                'expires_at' => now()->addMinutes(10),
            ]);
        });

        try {
            $this->delivery->send($channel, $recipient, $code);
            $challenge->update(['sent_at' => now()]);
        } catch (RuntimeException $exception) {
            $challenge->update(['consumed_at' => now()]);
            throw ValidationException::withMessages([
                'otp' => $exception->getMessage(),
            ]);
        }

        return $challenge;
    }

    private function verify($query, string $code): bool
    {
        return DB::transaction(function () use ($query, $code): bool {
            /** @var Otp|null $challenge */
            $challenge = $query
                ->active()
                ->latest('id')
                ->lockForUpdate()
                ->first();

            if (! $challenge) {
                return false;
            }

            $challenge->increment('attempts');

            if (! Hash::check($code, $challenge->code_hash)) {
                return false;
            }

            $challenge->update(['consumed_at' => now()]);

            return true;
        });
    }

    private function bookingSessionKey(Booking $booking): string
    {
        return "verified_booking:{$booking->id}";
    }

    private function customerSessionKey(Customer $customer, string $purpose): string
    {
        return "verified_customer:{$customer->id}:{$purpose}";
    }
}
