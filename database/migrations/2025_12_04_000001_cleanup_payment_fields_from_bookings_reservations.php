<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Remove redundant payment fields from reservations
        // (use payments table instead)
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropColumn(['payment', 'first_payment', 'second_payment']);
        });

        // Remove redundant payment field from bookings
        // (use payments table instead)
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('payment');
        });
    }

    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->string('payment')->nullable();
            $table->string('first_payment')->nullable();
            $table->string('second_payment')->nullable();
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->string('payment')->nullable();
        });
    }
};
