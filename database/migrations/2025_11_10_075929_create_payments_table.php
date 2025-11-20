<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            // Parent references (one will be null depending on source)
            $table->foreignId('booking_id')
                ->nullable()
                ->constrained()
                ->onDelete('cascade');

            $table->foreignId('reservation_id')
                ->nullable()
                ->constrained()
                ->onDelete('cascade');

            // Unique payment reference
            $table->string('reference_id', 20)->unique();

            $table->string('customer_name');
            $table->string('contact_number', 20);
            $table->datetime('payment_date');

            $table->decimal('amount', 10, 2)->nullable();

            // For reservation partial + final payments
            $table->enum('payment_stage', ['full', 'partial', 'final'])
                ->default('full');

            $table->enum('status', ['pending', 'verified', 'rejected'])
                ->default('pending');

            $table->string('payment_method')->default('gcash');

            $table->timestamp('verified_at')->nullable();
            $table->foreignId('verified_by')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null');

            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index(['booking_id', 'status']);
            $table->index(['reservation_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
