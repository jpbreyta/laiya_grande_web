<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('reservation_number')->unique();
            $table->unsignedBigInteger('room_id');
            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('cascade');

            $table->foreignId('customer_id')
                  ->constrained('customers')
                  ->onDelete('restrict');

            $table->date('check_in');
            $table->date('check_out')->nullable();
            $table->unsignedInteger('number_of_guests')->default(1);
            $table->text('special_request')->nullable();

            $table->enum('payment_method', ['cash', 'gcash', 'paymaya', 'bank_transfer'])->nullable();
            $table->string('payment')->nullable();
            $table->decimal('total_price', 10, 2)->default(0);
            $table->enum('source', ['online', 'pos'])->default('online');
            
            $table->timestamp('actual_check_in_time')->nullable();
            $table->timestamp('actual_check_out_time')->nullable();

            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'active', 'completed', 'rejected'])
                  ->default('pending')
                  ->index(); 
            $table->timestamps();
            
            $table->index('created_at'); 
            $table->index(['status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};