<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('active_customers', function (Blueprint $table) {
            $table->id();

            // Connections to either booking or reservation
            $table->unsignedBigInteger('booking_id')->nullable();
            $table->unsignedBigInteger('reservation_id')->nullable();
            $table->unsignedBigInteger('room_id')->nullable();

            // Snapshot details
            $table->string('guest_name');
            $table->enum('status', ['active', 'confirmed', 'paid', 'pending', 'cancelled', 'completed'])->default('active');
            $table->date('check_in')->nullable();
            $table->date('check_out')->nullable();

            $table->timestamps();

            // Foreign keys
            $table->foreign('booking_id')->references('id')->on('bookings')->onDelete('cascade');
            $table->foreign('reservation_id')->references('id')->on('reservations')->onDelete('cascade');
            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('cascade');

            // Indexes
            $table->index(['booking_id', 'reservation_id']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('active_customers');
    }
};
