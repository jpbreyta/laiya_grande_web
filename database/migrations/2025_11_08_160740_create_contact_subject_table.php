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
        // Creates the lookup table for dropdown values
        Schema::create('contact_subjects', function (Blueprint $table) {
            $table->id();
            // The 'classification' holds the human-readable subject (e.g., 'Reservation Inquiry')
            $table->string('classification')->unique(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_subjects');
    }
};