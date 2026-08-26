<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CommunicationSettingsSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('communication_settings')->updateOrInsert(
            ['singleton_key' => true],
            [
                'smtp_host' => null,
                'smtp_port' => null,
                'smtp_username' => null,
                'smtp_password' => null,
                'smtp_encryption' => null,
                'from_address' => null,
                'from_name' => 'Laiya Grande Beach Resort',
                'sms_provider' => null,
                'sms_api_key' => null,
                'sms_api_secret' => null, 
                'sms_sender_id' => null,
                'email_otp_enabled' => true,
                'email_booking_confirmation_enabled' => true,
                'email_payment_reminder_enabled' => true,
                'email_checkin_reminder_enabled' => true,
                'email_cancellation_enabled' => true,
                'sms_otp_enabled' => false, 
                'sms_booking_confirmation_enabled' => false,
                'sms_payment_reminder_enabled' => false,
                'sms_checkin_reminder_enabled' => false,
                'sms_cancellation_enabled' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
