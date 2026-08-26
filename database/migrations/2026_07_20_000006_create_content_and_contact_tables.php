<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('policies', function (Blueprint $table) {
            $table->id();
            $table->string('policy_type', 30)->index();
            $table->string('code', 100)->nullable();
            $table->string('title')->nullable();
            $table->text('description');
            $table->unsignedInteger('version')->default(1);
            $table->boolean('is_active')->default(true)->index();
            $table->timestamp('effective_at')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['policy_type', 'code', 'version'], 'policies_type_code_version_idx');
            $table->index(['policy_type', 'is_active', 'sort_order'], 'policies_type_active_order_idx');
        });

        Schema::create('contact_subjects', function (Blueprint $table) {
            $table->id();
            $table->string('classification')->unique();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });

        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('email');
            $table->string('phone', 30)->nullable();
            $table->foreignId('contact_subject_id')->constrained('contact_subjects')->restrictOnDelete();
            $table->text('message');
            $table->string('status', 20)->default('unread')->index();
            $table->timestamp('read_at')->nullable();
            $table->string('reply_subject')->nullable();
            $table->text('reply_content')->nullable();
            $table->timestamp('replied_at')->nullable();
            $table->foreignId('replied_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('archived_at')->nullable()->index();
            $table->timestamps();

            $table->index(['email', 'created_at']);
            $table->index(['status', 'created_at']);
        });

        if (DB::connection()->getDriverName() === 'pgsql') {
            DB::statement("ALTER TABLE policies ADD CONSTRAINT policies_type_valid CHECK (policy_type IN ('terms_and_conditions', 'house_rule', 'privacy_policy', 'cancellation_policy', 'other'))");
            DB::statement("ALTER TABLE contact_messages ADD CONSTRAINT contact_messages_status_valid CHECK (status IN ('unread', 'read', 'replied', 'archived'))");
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_messages');
        Schema::dropIfExists('contact_subjects');
        Schema::dropIfExists('policies');
    }
};
