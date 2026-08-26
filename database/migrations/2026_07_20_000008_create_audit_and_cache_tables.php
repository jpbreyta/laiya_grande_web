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
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
            $table->string('actor_email')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('model_type', 150);
            $table->unsignedBigInteger('model_id');
            $table->string('action', 30);
            $table->text('reason')->nullable();
            $table->jsonb('metadata')->nullable();
            $table->timestamp('accessed_at')->useCurrent();

            $table->index(['model_type', 'model_id']);
            $table->index(['user_id', 'accessed_at']);
            $table->index(['customer_id', 'accessed_at']);
            $table->index(['action', 'accessed_at']);
            $table->index('accessed_at');
        });

        Schema::create('cache', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->mediumText('value');
            $table->integer('expiration');
        });

        Schema::create('cache_locks', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->string('owner');
            $table->integer('expiration');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cache_locks');
        Schema::dropIfExists('cache');
        Schema::dropIfExists('data_access_logs');
    }
};
