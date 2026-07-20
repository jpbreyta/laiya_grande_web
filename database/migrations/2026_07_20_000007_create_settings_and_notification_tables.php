<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('general_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('singleton_key')->default(true)->unique();
            $table->string('resort_name')->nullable();
            $table->string('tagline')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone', 30)->nullable();
            $table->text('contact_address')->nullable();
            $table->text('description')->nullable();
            $table->string('social_facebook')->nullable();
            $table->string('social_instagram')->nullable();
            $table->string('social_twitter')->nullable();
            $table->string('social_tripadvisor')->nullable();
            $table->time('reception_hours_start')->nullable();
            $table->time('reception_hours_end')->nullable();
            $table->time('restaurant_hours_start')->nullable();
            $table->time('restaurant_hours_end')->nullable();
            $table->time('pool_hours_start')->nullable();
            $table->time('pool_hours_end')->nullable();
            $table->time('activities_hours_start')->nullable();
            $table->time('activities_hours_end')->nullable();
            $table->string('currency', 3)->default('PHP');
            $table->string('date_format')->default('Y-m-d');
            $table->string('time_format')->default('H:i');
            $table->string('logo_path')->nullable();
            $table->string('favicon_path')->nullable();
            $table->timestamps();
        });

        Schema::create('communication_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('singleton_key')->default(true)->unique();
            $table->string('smtp_host')->nullable();
            $table->unsignedInteger('smtp_port')->nullable();
            $table->string('smtp_username')->nullable();
            $table->text('smtp_password')->nullable();
            $table->string('smtp_encryption', 20)->nullable();
            $table->string('from_address')->nullable();
            $table->string('from_name')->nullable();
            $table->string('sms_provider')->nullable();
            $table->text('sms_api_key')->nullable();
            $table->text('sms_api_secret')->nullable();
            $table->string('sms_sender_id')->nullable();
            $table->boolean('email_otp_enabled')->default(true);
            $table->boolean('email_booking_confirmation_enabled')->default(true);
            $table->boolean('email_payment_reminder_enabled')->default(true);
            $table->boolean('email_checkin_reminder_enabled')->default(true);
            $table->boolean('email_cancellation_enabled')->default(true);
            $table->boolean('sms_otp_enabled')->default(false);
            $table->boolean('sms_booking_confirmation_enabled')->default(false);
            $table->boolean('sms_payment_reminder_enabled')->default(false);
            $table->boolean('sms_checkin_reminder_enabled')->default(false);
            $table->boolean('sms_cancellation_enabled')->default(false);
            $table->timestamps();
        });

        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained()->cascadeOnDelete();
            $table->boolean('is_broadcast')->default(false);
            $table->string('type', 50)->index();
            $table->string('title');
            $table->text('message');
            $table->jsonb('data')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'read_at']);
            $table->index(['customer_id', 'read_at']);
            $table->index(['is_broadcast', 'created_at']);
        });

        Schema::create('notification_deliveries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('notification_id')->constrained()->cascadeOnDelete();
            $table->string('channel', 20);
            $table->string('status', 20)->default('pending');
            $table->string('provider_message_id')->nullable()->index();
            $table->unsignedSmallInteger('attempts')->default(0);
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('failed_at')->nullable();
            $table->text('failure_reason')->nullable();
            $table->timestamps();

            $table->index(['notification_id', 'channel']);
            $table->index(['status', 'created_at']);
        });

        if (DB::connection()->getDriverName() === 'pgsql') {
            DB::statement("ALTER TABLE general_settings ADD CONSTRAINT general_settings_singleton_true CHECK (singleton_key = true)");
            DB::statement("ALTER TABLE communication_settings ADD CONSTRAINT communication_settings_singleton_true CHECK (singleton_key = true)");
            DB::statement("ALTER TABLE notifications ADD CONSTRAINT notifications_recipient_valid CHECK ((is_broadcast = true AND user_id IS NULL AND customer_id IS NULL) OR (is_broadcast = false AND num_nonnulls(user_id, customer_id) = 1))");
            DB::statement("ALTER TABLE notification_deliveries ADD CONSTRAINT notification_deliveries_channel_valid CHECK (channel IN ('database', 'email', 'sms', 'push'))");
            DB::statement("ALTER TABLE notification_deliveries ADD CONSTRAINT notification_deliveries_status_valid CHECK (status IN ('pending', 'sent', 'delivered', 'failed'))");
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('notification_deliveries');
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('communication_settings');
        Schema::dropIfExists('general_settings');
    }
};
