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
        Schema::create('general_settings', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('resort_name')->nullable();
            $table->string('tagline')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->text('contact_address')->nullable();
            $table->text('description')->nullable();
            $table->string('social_facebook')->nullable();
            $table->string('social_instagram')->nullable();
            $table->string('social_twitter')->nullable();
            $table->string('social_tripadvisor')->nullable();
            $table->time('reception_hours_start')->nullable();
            $table->time('reception_hours_end')->nullable();
            $table->time('restaurant_hours_start')->nullable();
            $table->time('restaurant_hours_end')->nullable();
            $table->time('pool_hours_start')->nullable();
            $table->time('pool_hours_end')->nullable();
            $table->time('activities_hours_start')->nullable();
            $table->time('activities_hours_end')->nullable();
            $table->string('currency', 3)->nullable();
            $table->string('date_format')->nullable();
            $table->string('time_format')->nullable();
            $table->string('logo_path')->nullable();
            $table->string('favicon_path')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('general_settings');
    }
};
