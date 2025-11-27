<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('point_of_sale', function (Blueprint $table) {
            $table->id();

            // ============================
            // PRIMARY RELATIONSHIP
            // ============================
            // The POS sale belongs to a checked-in guest stay
            $table->unsignedBigInteger('guest_stay_id');

            // ============================
            // OPTIONAL ORIGIN (SOURCE)
            // ============================
            // The POS transaction may originate from:
            // - a booking (walk-in or transition)
            // - a reservation (future guest)
            $table->unsignedBigInteger('booking_id')->nullable();
            $table->unsignedBigInteger('reservation_id')->nullable();

            // ============================
            // TRANSACTION DETAILS
            // ============================
            $table->string('item_name');   // Name of the product/service sold
            $table->string('item_type');   // 'rental', 'water_sport', 'other'

            $table->integer('quantity')->default(1);

            // ============================
            // FINANCIALS
            // ============================
            $table->decimal('price', 10, 2);        // Price per unit
            $table->decimal('total_amount', 10, 2); // Price * Quantity - Discount
            $table->decimal('discount', 10, 2)->default(0);

            $table->timestamps();

            // ============================
            // INDEXES
            // ============================
            $table->index('guest_stay_id');
            $table->index('booking_id');
            $table->index('reservation_id');
            $table->index('item_type');

            // ============================
            // FOREIGN KEYS
            // ============================

            // POS → guest_stays
            $table->foreign('guest_stay_id')
                  ->references('id')
                  ->on('guest_stays')
                  ->onDelete('cascade');

            // POS → bookings (optional link)
            $table->foreign('booking_id')
                  ->references('id')
                  ->on('bookings')
                  ->nullOnDelete();

            // POS → reservations (optional link)
            $table->foreign('reservation_id')
                  ->references('id')
                  ->on('reservations')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('point_of_sale');
    }
};
