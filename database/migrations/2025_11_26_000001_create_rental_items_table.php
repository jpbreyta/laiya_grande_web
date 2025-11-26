<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rental_items', function (Blueprint $table) {
            $table->id();
            
            // Item Details
            $table->string('name');         // e.g. "Portable Gas Stove", "Karaoke"
            $table->string('category');     // e.g. "Kitchen", "Entertainment", "Toiletries"
            $table->text('description')->nullable();
            
            // Pricing & Inventory
            $table->decimal('price', 10, 2); 
            $table->integer('stock_quantity')->default(0); // How many do you own?
            $table->boolean('is_available')->default(true);
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rental_items');
    }
};