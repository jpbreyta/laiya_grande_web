<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_number', 50)->unique();
            $table->foreignId('customer_id')->constrained()->restrictOnDelete();
            $table->foreignId('room_id')->constrained()->restrictOnDelete();
            $table->foreignId('room_rate_id')->nullable()->constrained('room_rates')->nullOnDelete();
            $table->string('source', 20)->default('online');
            $table->date('check_in');
            $table->date('check_out');
            $table->unsignedSmallInteger('number_of_guests')->default(1);
            $table->text('special_request')->nullable();
            $table->string('status', 30)->default('pending');
            $table->decimal('quoted_total', 12, 2)->default(0);
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('actual_check_in_time')->nullable();
            $table->timestamp('actual_check_out_time')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['room_id', 'check_in', 'check_out'], 'bookings_room_dates_idx');
            $table->index(['customer_id', 'created_at']);
            $table->index(['status', 'created_at']);
            $table->index(['source', 'status']);
        });

        Schema::create('guest_stays', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('status', 20)->default('checked_in')->index();
            $table->timestamp('check_in_time');
            $table->foreignId('checked_in_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('check_out_time')->nullable();
            $table->foreignId('checked_out_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->string('reference_id', 100)->nullable()->unique();
            $table->decimal('amount_paid', 12, 2);
            $table->string('payment_stage', 20)->default('full');
            $table->string('status', 20)->default('pending');
            $table->string('payment_method', 30);
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->jsonb('metadata')->nullable();
            $table->timestamps();

            $table->index(['booking_id', 'status']);
            $table->index(['status', 'created_at']);
        });

        Schema::create('otp_challenges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('booking_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('purpose', 40)->index();
            $table->string('channel', 20);
            $table->string('recipient');
            $table->string('code_hash');
            $table->unsignedSmallInteger('attempts')->default(0);
            $table->unsignedSmallInteger('max_attempts')->default(5);
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('expires_at')->index();
            $table->timestamp('consumed_at')->nullable();
            $table->timestamps();

            $table->index(['customer_id', 'purpose']);
            $table->index(['booking_id', 'purpose']);
        });

        Schema::create('room_ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->unique()->constrained()->cascadeOnDelete();
            $table->unsignedSmallInteger('rating');
            $table->text('comment')->nullable();
            $table->boolean('is_verified')->default(false)->index();
            $table->timestamp('moderated_at')->nullable();
            $table->foreignId('moderated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['is_verified', 'created_at']);
        });

        if (DB::connection()->getDriverName() === 'pgsql') {
            DB::statement("ALTER TABLE bookings ADD CONSTRAINT bookings_dates_valid CHECK (check_out > check_in)");
            DB::statement("ALTER TABLE bookings ADD CONSTRAINT bookings_guests_positive CHECK (number_of_guests > 0)");
            DB::statement("ALTER TABLE bookings ADD CONSTRAINT bookings_total_nonnegative CHECK (quoted_total >= 0)");
            DB::statement("ALTER TABLE bookings ADD CONSTRAINT bookings_source_valid CHECK (source IN ('online', 'pos', 'admin'))");
            DB::statement("ALTER TABLE bookings ADD CONSTRAINT bookings_status_valid CHECK (status IN ('pending', 'confirmed', 'cancelled', 'checked_in', 'completed', 'rejected', 'expired'))");
            DB::statement("ALTER TABLE bookings ADD CONSTRAINT bookings_actual_times_valid CHECK (actual_check_out_time IS NULL OR actual_check_in_time IS NULL OR actual_check_out_time >= actual_check_in_time)");
            DB::statement("ALTER TABLE guest_stays ADD CONSTRAINT guest_stays_status_valid CHECK (status IN ('checked_in', 'checked_out'))");
            DB::statement("ALTER TABLE guest_stays ADD CONSTRAINT guest_stays_times_valid CHECK (check_out_time IS NULL OR check_out_time >= check_in_time)");
            DB::statement("ALTER TABLE payments ADD CONSTRAINT payments_amount_positive CHECK (amount_paid > 0)");
            DB::statement("ALTER TABLE payments ADD CONSTRAINT payments_stage_valid CHECK (payment_stage IN ('full', 'partial', 'final', 'refund'))");
            DB::statement("ALTER TABLE payments ADD CONSTRAINT payments_status_valid CHECK (status IN ('pending', 'verified', 'rejected', 'refunded'))");
            DB::statement("ALTER TABLE otp_challenges ADD CONSTRAINT otp_challenges_target_present CHECK (customer_id IS NOT NULL OR booking_id IS NOT NULL)");
            DB::statement("ALTER TABLE otp_challenges ADD CONSTRAINT otp_challenges_attempts_valid CHECK (attempts <= max_attempts)");
            DB::statement("ALTER TABLE room_ratings ADD CONSTRAINT room_ratings_rating_valid CHECK (rating BETWEEN 1 AND 5)");
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('room_ratings');
        Schema::dropIfExists('otp_challenges');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('guest_stays');
        Schema::dropIfExists('bookings');
    }
};
