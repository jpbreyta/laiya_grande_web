<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('room_ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained()->onDelete('cascade');
            $table->string('guest_email');
            $table->string('guest_name')->nullable();
            $table->integer('rating')->unsigned()->comment('1-5 stars');
            $table->text('comment')->nullable();
            $table->string('ip_address')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->timestamps();
            
            // Prevent duplicate ratings from same email for same room
            $table->unique(['room_id', 'guest_email']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_ratings');
    }
};
