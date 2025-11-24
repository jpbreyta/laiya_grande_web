<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('foods', function (Blueprint $table) {
            $table->id();

            $table->string('name');                 // e.g., Pancakes, Fried Chicken
            $table->text('description')->nullable();
            $table->decimal('price', 8, 2)->default(0);
            $table->integer('no_of_pax')->default(1);

            $table->foreignId('food_category_id')->constrained('food_categories')->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('foods');
    }
};