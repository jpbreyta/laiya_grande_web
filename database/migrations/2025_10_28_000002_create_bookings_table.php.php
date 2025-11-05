<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();

            // Foreign key to rooms table
            $table->unsignedBigInteger('room_id');
            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('cascade');

            // Guest details
            $table->string('firstname');
            $table->string('lastname');
            $table->string('email');
            $table->unsignedBigInteger('phone_number');

            // Booking details
            $table->date('check_in');
            $table->date('check_out');
            $table->unsignedInteger('number_of_guests');
            $table->text('special_request')->nullable();

            // Payment info
            $table->enum('payment_method', ['gcash', 'paymaya', 'bank_transfer'])->nullable();
            $table->string('payment')->nullable();
            $table->decimal('total_price', 10, 2)->default(0);

            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'completed'])->default('pending');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
