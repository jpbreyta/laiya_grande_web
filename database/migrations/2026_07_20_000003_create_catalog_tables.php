<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('catalog_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });

        Schema::create('catalog_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('catalog_category_id')->nullable()->constrained('catalog_categories')->nullOnDelete();
            $table->string('item_type', 30)->index();
            $table->string('sku', 80)->nullable()->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->text('pricing_details')->nullable();
            $table->decimal('unit_price', 12, 2)->nullable();
            $table->unsignedSmallInteger('pax_capacity')->nullable();
            $table->unsignedSmallInteger('min_participants')->nullable();
            $table->unsignedInteger('duration_minutes')->nullable();
            $table->boolean('tracks_inventory')->default(false);
            $table->integer('stock_quantity')->nullable();
            $table->boolean('is_available')->default(true)->index();
            $table->jsonb('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['item_type', 'is_available']);
            $table->index(['catalog_category_id', 'is_available'], 'catalog_items_category_available_idx');
        });

        if (DB::connection()->getDriverName() === 'pgsql') {
            DB::statement("ALTER TABLE catalog_items ADD CONSTRAINT catalog_items_type_valid CHECK (item_type IN ('food', 'rental', 'water_sport', 'tour_package', 'other'))");
            DB::statement("ALTER TABLE catalog_items ADD CONSTRAINT catalog_items_price_nonnegative CHECK (unit_price IS NULL OR unit_price >= 0)");
            DB::statement("ALTER TABLE catalog_items ADD CONSTRAINT catalog_items_stock_valid CHECK ((tracks_inventory = false) OR (stock_quantity IS NOT NULL AND stock_quantity >= 0))");
            DB::statement("ALTER TABLE catalog_items ADD CONSTRAINT catalog_items_participants_positive CHECK (min_participants IS NULL OR min_participants > 0)");
            DB::statement("ALTER TABLE catalog_items ADD CONSTRAINT catalog_items_duration_positive CHECK (duration_minutes IS NULL OR duration_minutes > 0)");
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('catalog_items');
        Schema::dropIfExists('catalog_categories');
    }
};
