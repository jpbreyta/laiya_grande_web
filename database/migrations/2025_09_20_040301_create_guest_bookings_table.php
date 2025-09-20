<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('guest_bookings', function (Blueprint $table) {
            $table->id();
            $table->string('guest_name');
            $table->string('contact_number')->unique();
            $table->date('arrival_date');
            $table->integer('total_guests');
            $table->string('room_names');
            $table->string('car_plate')->nullable();
            $table->string('balance_mode');
            $table->decimal('key_deposit', 10, 2)->nullable();
            $table->boolean('eco_ticket')->default(false);
            $table->boolean('parking')->default(false);
            $table->boolean('cookwares')->default(false);
            $table->boolean('videoke')->default(false);
            $table->text('others')->nullable();
            $table->decimal('total_amount', 10, 2)->nullable();
            $table->boolean('otp_verified')->default(false);
            $table->string('qr_code')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guest_bookings');
    }
};
