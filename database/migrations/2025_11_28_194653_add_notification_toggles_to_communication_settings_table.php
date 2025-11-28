<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('communication_settings', function (Blueprint $table) {
            // SMS Configuration
            $table->string('sms_provider')->nullable();
            $table->string('sms_api_key')->nullable();
            $table->string('sms_api_secret')->nullable();
            $table->string('sms_sender_id')->nullable();
            
            // Email Notification Toggles
            $table->boolean('email_otp_enabled')->default(true);
            $table->boolean('email_booking_confirmation_enabled')->default(true);
            $table->boolean('email_payment_reminder_enabled')->default(true);
            $table->boolean('email_checkin_reminder_enabled')->default(true);
            $table->boolean('email_cancellation_enabled')->default(true);
            
            // SMS Notification Toggles
            $table->boolean('sms_otp_enabled')->default(false);
            $table->boolean('sms_booking_confirmation_enabled')->default(false);
            $table->boolean('sms_payment_reminder_enabled')->default(false);
            $table->boolean('sms_checkin_reminder_enabled')->default(false);
            $table->boolean('sms_cancellation_enabled')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('communication_settings', function (Blueprint $table) {
            $table->dropColumn([
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
            ]);
        });
    }
};
