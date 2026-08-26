<?php

namespace App\Services\Communication;

use App\Models\CommunicationSetting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use RuntimeException;

class OtpDeliveryService
{
    /**
     * Deliver an OTP without storing or logging the plain code.
     */
    public function send(string $channel, string $recipient, string $code): void
    {
        match ($channel) {
            'email' => $this->sendEmail($recipient, $code),
            'sms' => $this->sendSms($recipient, $code),
            default => throw new RuntimeException('Unsupported OTP delivery channel.'),
        };
    }

    private function sendEmail(string $email, string $code): void
    {
        $settings = CommunicationSetting::instance();

        if (! $settings->email_otp_enabled) {
            throw new RuntimeException('Email OTP delivery is disabled.');
        }

        Mail::raw(
            "Your Laiya Grande verification code is {$code}. It expires in 10 minutes. Do not share this code.",
            function ($message) use ($email): void {
                $message->to($email)->subject('Laiya Grande Verification Code');
            }
        );
    }

    private function sendSms(string $phone, string $code): void
    {
        $settings = CommunicationSetting::instance();

        if (! $settings->sms_otp_enabled) {
            throw new RuntimeException('SMS OTP delivery is disabled.');
        }

        $apiUrl = config('services.iprog.url', env('IPROG_SMS_API_URL'));
        $token = $settings->sms_api_key ?: config('services.iprog.token', env('IPROG_SMS_API_TOKEN'));

        if (! $apiUrl || ! $token) {
            throw new RuntimeException('SMS provider credentials are not configured.');
        }

        $normalizedPhone = preg_replace('/\D+/', '', $phone) ?? '';
        if (Str::startsWith($normalizedPhone, '0')) {
            $normalizedPhone = '63' . substr($normalizedPhone, 1);
        } elseif (! Str::startsWith($normalizedPhone, '63')) {
            $normalizedPhone = '63' . $normalizedPhone;
        }

        $response = Http::timeout(15)
            ->retry(2, 500)
            ->post($apiUrl, [
                'api_token' => $token,
                'phone_number' => $normalizedPhone,
                'message' => "Your Laiya Grande OTP is {$code}. It expires in 10 minutes.",
                'sender_id' => $settings->sms_sender_id ?: 'LaiyaGrande',
            ]);

        if (! $response->successful()) {
            throw new RuntimeException('The SMS provider rejected the OTP request.');
        }
    }
}
