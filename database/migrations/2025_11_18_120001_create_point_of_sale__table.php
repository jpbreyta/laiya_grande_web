<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('point_of_sale', function (Blueprint $table) {
            $table->id();

            // Link to active customer
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('guest_stays')->onDelete('cascade');

            // Sale details
            $table->string('item');
            $table->decimal('price', 10, 2);
            $table->decimal('discount', 10, 2)->default(0);

            $table->timestamps();

            // Index for faster lookups
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('point_of_sale');
    }
};
