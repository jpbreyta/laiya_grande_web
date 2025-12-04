<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            // Data privacy and security
            $table->boolean('data_consent')->default(false)->after('phone_number');
            $table->timestamp('consent_given_at')->nullable()->after('data_consent');
            
            // Track data access for audit
            $table->timestamp('last_accessed_at')->nullable()->after('consent_given_at');
            $table->unsignedBigInteger('last_accessed_by')->nullable()->after('last_accessed_at');
            
            // Foreign key for audit trail
            $table->foreign('last_accessed_by')
                  ->references('id')
                  ->on('users')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropForeign(['last_accessed_by']);
            $table->dropColumn([
                'data_consent',
                'consent_given_at',
                'last_accessed_at',
                'last_accessed_by',
            ]);
        });
    }
};
