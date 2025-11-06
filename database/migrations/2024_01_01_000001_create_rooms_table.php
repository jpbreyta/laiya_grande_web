<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('short_description')->nullable();
            $table->longText('full_description')->nullable();
            $table->decimal('price', 10, 2);
            $table->integer('capacity'); // GOOD FOR X PAX
            $table->integer('availability')->default(1);

            // Room feature flags based on actual inclusions
            $table->boolean('has_aircon')->default(true);
            $table->boolean('has_private_cr')->default(true);
            $table->boolean('has_kitchen')->default(false);
            $table->boolean('has_free_parking')->default(true);
            $table->boolean('no_entrance_fee')->default(true);
            $table->boolean('no_corkage_fee')->default(true);

            $table->json('amenities')->nullable(); // optional, can store “Free WiFi”, “TV”, etc.
            $table->json('images')->nullable(); 
            $table->string('image')->nullable();
            
            $table->enum('rate_name', ['overnight', 'daytour'])->nullable();
            $table->enum('status', ['available', 'unavailable'])->default('available');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
