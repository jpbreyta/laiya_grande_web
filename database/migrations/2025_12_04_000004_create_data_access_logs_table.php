<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('data_access_logs', function (Blueprint $table) {
            $table->id();
            
            // Who accessed the data
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('user_email')->nullable();
            $table->string('ip_address', 45);
            
            // What was accessed
            $table->string('model_type'); // Customer, Booking, Reservation
            $table->unsignedBigInteger('model_id');
            $table->string('action'); // view, create, update, delete, export
            
            // When and why
            $table->text('reason')->nullable(); // "Checking in guest", "Payment verification"
            $table->timestamp('accessed_at');
            
            // Indexes for quick audit queries
            $table->index(['model_type', 'model_id']);
            $table->index('user_id');
            $table->index('accessed_at');
            
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_access_logs');
    }
};
