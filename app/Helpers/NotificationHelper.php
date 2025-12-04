<?php

namespace App\Helpers;

use App\Models\CommunicationSetting;

class NotificationHelper
{
    /**
     * Check if email notifications are enabled for a specific type
     */
    public static function isEmailEnabled(string $type): bool
    {
        $settings = CommunicationSetting::first();
        
        if (!$settings) {
            return true; // Default to enabled if no settings exist
        }

        return match($type) {
            'otp' => $settings->email_otp_enabled,
            'booking_confirmation' => $settings->email_booking_confirmation_enabled,
            'payment_reminder' => $settings->email_payment_reminder_enabled,
            'checkin_reminder' => $settings->email_checkin_reminder_enabled,
            'cancellation' => $settings->email_cancellation_enabled,
            default => false,
        };
    }

    /**
     * Check if SMS notifications are enabled for a specific type
     */
    public static function isSmsEnabled(string $type): bool
    {
        $settings = CommunicationSetting::first();
        
        if (!$settings) {
            return false; // Default to disabled if no settings exist
        }

        return match($type) {
            'otp' => $settings->sms_otp_enabled,
            'booking_confirmation' => $settings->sms_booking_confirmation_enabled,
            'payment_reminder' => $settings->sms_payment_reminder_enabled,
            'checkin_reminder' => $settings->sms_checkin_reminder_enabled,
            'cancellation' => $settings->sms_cancellation_enabled,
            default => false,
        };
    }

    /**
     * Get SMS configuration
     */
    public static function getSmsConfig(): ?array
    {
        $settings = CommunicationSetting::first();
        
        if (!$settings || !$settings->sms_provider) {
            return null;
        }

        return [
            'provider' => $settings->sms_provider,
            'api_key' => $settings->sms_api_key,
            'api_secret' => $settings->sms_api_secret,
            'sender_id' => $settings->sms_sender_id,
        ];
    }

    /**
     * Get email configuration
     */
    public static function getEmailConfig(): ?array
    {
        $settings = CommunicationSetting::first();
        
        if (!$settings || !$settings->smtp_host) {
            return null;
        }

        return [
            'host' => $settings->smtp_host,
            'port' => $settings->smtp_port,
            'username' => $settings->smtp_username,
            'password' => $settings->smtp_password,
            'encryption' => $settings->smtp_encryption,
            'from_address' => $settings->from_address,
            'from_name' => $settings->from_name,
        ];
    }
}
