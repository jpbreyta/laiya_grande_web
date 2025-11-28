<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contact_messages', function (Blueprint $table) {
            $table->string('reply_subject')->nullable()->after('read_at');
            $table->text('reply_content')->nullable()->after('reply_subject');
            $table->timestamp('replied_at')->nullable()->after('reply_content');
        });
    }

    public function down(): void
    {
        Schema::table('contact_messages', function (Blueprint $table) {
            $table->dropColumn(['reply_subject', 'reply_content', 'replied_at']);
        });
    }
};
