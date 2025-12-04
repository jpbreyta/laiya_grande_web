<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommunicationSetting extends Model
{
    protected $fillable = [
        'smtp_host',
        'smtp_port',
        'smtp_username',
        'smtp_password',
        'smtp_encryption',
        'from_address',
        'from_name',
        'sms_provider',
        'sms_api_key',
        'sms_api_secret',
        'sms_sender_id',
        'email_otp_enabled',
        'email_booking_confirmation_enabled',
        'email_payment_reminder_enabled',
        'email_checkin_reminder_enabled',
        'email_cancellation_enabled',
        'sms_otp_enabled',
        'sms_booking_confirmation_enabled',
        'sms_payment_reminder_enabled',
        'sms_checkin_reminder_enabled',
        'sms_cancellation_enabled',
    ];

    protected $casts = [
        'email_otp_enabled' => 'boolean',
        'email_booking_confirmation_enabled' => 'boolean',
        'email_payment_reminder_enabled' => 'boolean',
        'email_checkin_reminder_enabled' => 'boolean',
        'email_cancellation_enabled' => 'boolean',
        'sms_otp_enabled' => 'boolean',
        'sms_booking_confirmation_enabled' => 'boolean',
        'sms_payment_reminder_enabled' => 'boolean',
        'sms_checkin_reminder_enabled' => 'boolean',
        'sms_cancellation_enabled' => 'boolean',
    ];
}
