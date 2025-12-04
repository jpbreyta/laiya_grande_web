<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pos_transactions', function (Blueprint $table) {
            $table->id();

            // ============================
            // MAIN RELATION (POS Transaction belongs to a POS entry)
            // ============================
            $table->unsignedBigInteger('point_of_sale_id');

            // ============================
            // SOURCE RELATION (Optional)
            // ============================
            // The transaction may come from a booking or a reservation
            $table->unsignedBigInteger('guest_stay_id')->nullable();
            $table->unsignedBigInteger('booking_id')->nullable();
            $table->unsignedBigInteger('reservation_id')->nullable();

            // ============================
            // TRANSACTION DATA
            // ============================
            $table->json('items'); // Cart items as JSON

            $table->decimal('subtotal', 10, 2);
            $table->decimal('tax', 10, 2)->default(0);
            $table->decimal('total', 10, 2);

            $table->string('payment_method')->nullable(); // cash, gcash, card
            $table->string('status')->default('completed'); // completed, canceled, refunded

            $table->timestamps();

            // ============================
            // INDEXES
            // ============================
            $table->index('point_of_sale_id');
            $table->index('guest_stay_id');
            $table->index('booking_id');
            $table->index('reservation_id');

            // ============================
            // FOREIGN KEYS
            // ============================

            // pos_transactions → point_of_sale
            $table->foreign('point_of_sale_id')
                  ->references('id')
                  ->on('point_of_sale')
                  ->cascadeOnDelete();

            // pos_transactions → guest_stays (optional)
            $table->foreign('guest_stay_id')
                  ->references('id')
                  ->on('guest_stays')
                  ->nullOnDelete();

            // pos_transactions → bookings (optional)
            $table->foreign('booking_id')
                  ->references('id')
                  ->on('bookings')
                  ->nullOnDelete();

            // pos_transactions → reservations (optional)
            $table->foreign('reservation_id')
                  ->references('id')
                  ->on('reservations')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pos_transactions');
    }
};
