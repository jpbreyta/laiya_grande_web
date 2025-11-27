<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();

            // reservation number (unique + indexed)
            $table->string('reservation_number')->unique();

            // Foreign key to rooms table
            $table->unsignedBigInteger('room_id');
            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('cascade');

            // --- NORMALIZATION FIX START ---
            // Replaced redundant guest details with Foreign Key to the customers table
            $table->foreignId('customer_id')
                  ->constrained('customers')
                  ->onDelete('restrict'); // Assuming a reservation must always have a customer
            // --- NORMALIZATION FIX END ---

            // Reservation details
            $table->date('check_in');
            $table->date('check_out');
            $table->unsignedInteger('number_of_guests')->default(1);
            $table->text('special_request')->nullable();

            // Payment method and proof
            $table->enum('payment_method', ['gcash', 'paymaya', 'bank_transfer'])->nullable();
            $table->string('payment')->nullable();
            $table->string('first_payment')->nullable();
            $table->string('second_payment')->nullable();
            $table->decimal('total_price', 10, 2)->default(0);

            // Reservation status
            $table->enum('status', ['pending', 'paid', 'confirmed', 'cancelled'])->default('pending');

            $table->timestamp('expires_at')->nullable();

            $table->timestamps();

            // explicit index (unique already creates an index)
            $table->index('reservation_number');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};