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
        Schema::table('pos_transactions', function (Blueprint $table) {
            // Drop all foreign keys first
            $table->dropForeign(['point_of_sale_id']);
            $table->dropForeign(['guest_stay_id']);
            $table->dropForeign(['booking_id']);
            $table->dropForeign(['reservation_id']);
        });
        
        Schema::table('pos_transactions', function (Blueprint $table) {
            // Drop old columns
            $table->dropColumn(['point_of_sale_id', 'booking_id', 'reservation_id', 'items', 'tax', 'payment_method', 'status']);
            
            // Add new columns
            $table->string('receipt_number')->unique()->after('id');
            $table->decimal('discount', 10, 2)->default(0)->after('subtotal');
            $table->integer('items_count')->default(0)->after('total');
            $table->timestamp('transaction_date')->useCurrent()->after('items_count');
            
            // Re-add the guest_stay_id foreign key
            $table->foreign('guest_stay_id')
                  ->references('id')
                  ->on('guest_stays')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pos_transactions', function (Blueprint $table) {
            // Drop foreign key
            $table->dropForeign(['guest_stay_id']);
        });
        
        Schema::table('pos_transactions', function (Blueprint $table) {
            // Drop new columns
            $table->dropColumn(['receipt_number', 'discount', 'items_count', 'transaction_date']);
            
            // Restore old columns
            $table->unsignedBigInteger('point_of_sale_id')->after('id');
            $table->unsignedBigInteger('booking_id')->nullable()->after('guest_stay_id');
            $table->unsignedBigInteger('reservation_id')->nullable()->after('booking_id');
            $table->json('items')->after('reservation_id');
            $table->decimal('tax', 10, 2)->default(0)->after('subtotal');
            $table->string('payment_method')->nullable()->after('total');
            $table->string('status')->default('completed')->after('payment_method');
            
            // Restore foreign keys
            $table->foreign('point_of_sale_id')->references('id')->on('point_of_sale')->cascadeOnDelete();
            $table->foreign('guest_stay_id')->references('id')->on('guest_stays')->nullOnDelete();
            $table->foreign('booking_id')->references('id')->on('bookings')->nullOnDelete();
            $table->foreign('reservation_id')->references('id')->on('reservations')->nullOnDelete();
        });
    }
};
