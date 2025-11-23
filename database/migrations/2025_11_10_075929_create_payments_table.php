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

            // independent parent references (nullable)
            $table->unsignedBigInteger('booking_id')->nullable();
            $table->unsignedBigInteger('reservation_id')->nullable();

            // Unique payment reference (nullable)
            $table->string('reference_id', 20)->nullable()->unique();

            $table->string('customer_name');
            $table->string('contact_number', 20);
            $table->dateTime('payment_date')->nullable();

            // renamed/normalized amount column
            $table->decimal('amount_paid', 10, 2)->nullable();

            // For reservation partial + final payments
            $table->enum('payment_stage', ['full', 'partial', 'final'])->default('full');

            $table->enum('status', ['pending', 'verified', 'rejected'])->default('pending');

            $table->string('payment_method')->default('gcash');

            $table->timestamp('verified_at')->nullable();
            $table->unsignedBigInteger('verified_by')->nullable();

            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index(['booking_id', 'status']);
            $table->index(['reservation_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
