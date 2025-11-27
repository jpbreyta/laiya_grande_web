<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('guest_stays', function (Blueprint $table) {
            $table->id();

            // Connections to bookings or reservations
            $table->unsignedBigInteger('booking_id')->nullable();
            $table->unsignedBigInteger('reservation_id')->nullable();
            $table->unsignedBigInteger('room_id')->nullable();

            // Guest snapshot (optional if you want redundancy)
            $table->string('guest_name');

            // Only two statuses needed
            $table->enum('status', ['checked-in', 'checked-out'])->default('checked-in');

            // Track exact times
            $table->timestamp('check_in_time')->nullable();
            $table->timestamp('check_out_time')->nullable();

            $table->timestamps();

            // Foreign keys
            $table->foreign('booking_id')->references('id')->on('bookings')->onDelete('cascade');
            $table->foreign('reservation_id')->references('id')->on('reservations')->onDelete('cascade');
            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('cascade');

            // Indexes for quick queries
            $table->index(['booking_id', 'reservation_id']);
            $table->index('status');
            $table->index('room_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guest_stays');
    }
};
