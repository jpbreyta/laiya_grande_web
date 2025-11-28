<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Add Soft Deletes for "Archiving"
            $table->softDeletes();
            
            // Note: Modifying an enum in Laravel/Doctrine can be tricky. 
            // We usually just change the column to string to allow any status, 
            // or we run a raw statement. Here we change it to string for flexibility.
            $table->string('status')->default('pending')->change();
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropSoftDeletes();
            // Reverting to enum might require raw SQL depending on DB driver, skipping for safety
        });
    }
};