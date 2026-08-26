<?php

namespace App\Services\Communication;

use App\Models\Customer;
use Illuminate\Validation\ValidationException;

class OnlineBookingOtpService
{
    public const PURPOSE = 'online_booking_create';

    public function __construct(
        private readonly OtpService $otp
    ) {
    }

    /**
     * Create a provisional customer when necessary and send an email OTP.
     */
    public function send(
        string $email,
        ?string $firstName,
        ?string $lastName,
        ?string $phone,
        string $ipAddress
    ): Customer {
        $normalizedEmail = mb_strtolower(trim($email));
        $customer = Customer::withTrashed()->firstOrNew(['email' => $normalizedEmail]);

        if ($customer->trashed()) {
            $customer->restore();
        }

        $customer->fill([
            'first_name' => filled($firstName) ? trim((string) $firstName) : ($customer->first_name ?: 'Pending'),
            'last_name' => filled($lastName) ? trim((string) $lastName) : ($customer->last_name ?: 'Verification'),
            'phone_number' => filled($phone) ? trim((string) $phone) : $customer->phone_number,
        ]);
        $customer->save();

        $this->otp->sendForCustomer(
            customer: $customer,
            purpose: self::PURPOSE,
            channel: 'email',
            recipient: $normalizedEmail,
            requestKey: hash('sha256', $normalizedEmail . '|' . $ipAddress)
        );

        return $customer;
    }

    /**
     * Verify an email OTP and grant a temporary checkout session.
     */
    public function verify(string $email, string $code): Customer
    {
        $customer = Customer::query()
            ->where('email', mb_strtolower(trim($email)))
            ->first();

        if (! $customer || ! $this->otp->verifyForCustomer($customer, self::PURPOSE, $code)) {
            throw ValidationException::withMessages([
                'otp' => 'The OTP is invalid, expired, or has reached its attempt limit.',
            ]);
        }

        $this->otp->grantCustomerPurpose($customer, self::PURPOSE);

        return $customer;
    }

    public function isVerified(string $email): bool
    {
        $customer = Customer::query()
            ->where('email', mb_strtolower(trim($email)))
            ->first();

        return $customer !== null
            && $this->otp->hasCustomerPurpose($customer, self::PURPOSE);
    }
}
