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
            $table->integer('capacity');
            $table->integer('availability')->default(1);
            $table->string('bed_type')->nullable();
            $table->integer('bathrooms')->default(1);
            $table->integer('size')->nullable();
            $table->json('amenities')->nullable();
            $table->json('images')->nullable();
            $table->string('image')->nullable();
            $table->string('rate_name')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};

