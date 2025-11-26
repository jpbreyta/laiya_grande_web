<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('point_of_sale', function (Blueprint $table) {
            $table->id();

            // Link to the guest_stays table (The checked-in guest)
            $table->unsignedBigInteger('guest_stay_id');
            $table->foreign('guest_stay_id')->references('id')->on('guest_stays')->onDelete('cascade');

            // Transaction Details
            $table->string('item_name');   // Name of the item/service at the time of purchase
            $table->string('item_type');   // 'rental', 'water_sport', 'other'
            $table->integer('quantity')->default(1);
            
            // Financials
            $table->decimal('price', 10, 2); // Price per item
            $table->decimal('total_amount', 10, 2); // (Price * Quantity) - Discount
            $table->decimal('discount', 10, 2)->default(0);

            $table->timestamps();

            // Indexes for faster reporting
            $table->index('guest_stay_id');
            $table->index('item_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('point_of_sale');
    }
};