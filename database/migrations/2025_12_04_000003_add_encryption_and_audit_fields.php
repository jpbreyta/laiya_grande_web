<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add audit trail to bookings
        Schema::table('bookings', function (Blueprint $table) {
            $table->unsignedBigInteger('created_by')->nullable()->after('status');
            $table->unsignedBigInteger('updated_by')->nullable()->after('created_by');
            
            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('updated_by')->references('id')->on('users')->nullOnDelete();
        });

        // Add audit trail to reservations
        Schema::table('reservations', function (Blueprint $table) {
            $table->unsignedBigInteger('created_by')->nullable()->after('status');
            $table->unsignedBigInteger('updated_by')->nullable()->after('created_by');
            
            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('updated_by')->references('id')->on('users')->nullOnDelete();
        });

        // Add audit trail to guest stays
        Schema::table('guest_stays', function (Blueprint $table) {
            $table->unsignedBigInteger('checked_in_by')->nullable()->after('check_in_time');
            $table->unsignedBigInteger('checked_out_by')->nullable()->after('check_out_time');
            
            $table->foreign('checked_in_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('checked_out_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['created_by', 'updated_by']);
            $table->dropColumn(['created_by', 'updated_by']);
        });

        Schema::table('reservations', function (Blueprint $table) {
            $table->dropForeign(['created_by', 'updated_by']);
            $table->dropColumn(['created_by', 'updated_by']);
        });

        Schema::table('guest_stays', function (Blueprint $table) {
            $table->dropForeign(['checked_in_by', 'checked_out_by']);
            $table->dropColumn(['checked_in_by', 'checked_out_by']);
        });
    }
};
