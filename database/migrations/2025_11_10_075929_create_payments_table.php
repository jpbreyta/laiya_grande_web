<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            // nullable parent references
            $table->unsignedBigInteger('booking_id')->nullable();
            $table->unsignedBigInteger('reservation_id')->nullable();

            // Unique payment reference
            $table->string('reference_id', 20)->nullable()->unique();

            $table->string('customer_name');
            $table->string('contact_number', 20);
            $table->dateTime('payment_date')->nullable();

            $table->decimal('amount_paid', 10, 2)->nullable();

            $table->enum('payment_stage', ['full', 'partial', 'final'])->default('full');
            $table->enum('status', ['pending', 'verified', 'rejected'])->default('pending');

            $table->string('payment_method')->default('gcash');

            $table->timestamp('verified_at')->nullable();
            $table->unsignedBigInteger('verified_by')->nullable();

            $table->text('notes')->nullable();

            $table->timestamps();

            // indexes
            $table->index(['booking_id', 'status']);
            $table->index(['reservation_id', 'status']);

            // *************** FOREIGN KEYS ***************
            $table->foreign('booking_id')
                  ->references('id')
                  ->on('bookings')
                  ->onDelete('set null');

            $table->foreign('reservation_id')
                  ->references('id')
                  ->on('reservations')
                  ->onDelete('set null');

            $table->foreign('verified_by')
                  ->references('id')
                  ->on('users')
                  ->nullOnDelete();
            // ********************************************
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
