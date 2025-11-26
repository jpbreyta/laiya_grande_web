<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();

            // reservation number (unique + indexed)
            $table->string('reservation_number')->unique();

            // Foreign key to rooms table
            $table->unsignedBigInteger('room_id');
            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('cascade');

            // Guest details
            $table->string('firstname');
            $table->string('lastname');
            $table->string('email');
            $table->string('phone_number');

            // Booking details
            $table->date('check_in');
            $table->date('check_out')->nullable();
            $table->unsignedInteger('number_of_guests')->default(1);
            $table->text('special_request')->nullable();

            // payment fields expected by your seeder
            $table->enum('payment_method', ['cash', 'gcash', 'paymaya', 'bank_transfer'])->nullable();
            $table->string('payment')->nullable(); // e.g. 'full' or file/ref
            $table->decimal('total_price', 10, 2)->default(0);
            $table->enum('source', ['online', 'pos'])->default('online');
            // actual timestamps (do not use ->after() in create)
            $table->timestamp('actual_check_in_time')->nullable();
            $table->timestamp('actual_check_out_time')->nullable();

            // Payment / status
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'active', 'completed'])->default('pending');

            $table->timestamps();

            // explicit index (unique already creates an index; optional)
            $table->index('reservation_number');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
