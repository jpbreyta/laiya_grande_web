<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('foods', function (Blueprint $table) {
            // Remove old columns
            $table->dropColumn(['description', 'price']);

            // Add new column
            $table->integer('no_of_pax')->default(1); // default to 1 pax
        });
    }

    public function down(): void
    {
        Schema::table('foods', function (Blueprint $table) {
            // Rollback: restore description and price
            $table->text('description')->nullable();
            $table->decimal('price', 8, 2)->default(0);

            // Remove pax column
            $table->dropColumn('no_of_pax');
        });
    }
};