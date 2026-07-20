<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->nullable()->unique();
            $table->string('name')->unique();
            $table->text('short_description')->nullable();
            $table->longText('full_description')->nullable();
            $table->unsignedSmallInteger('capacity');
            $table->unsignedSmallInteger('inventory_count')->default(1);
            $table->string('status', 30)->default('available')->index();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('room_rates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained()->cascadeOnDelete();
            $table->string('rate_type', 30);
            $table->string('name', 100);
            $table->decimal('price', 12, 2);
            $table->unsignedSmallInteger('minimum_nights')->default(1);
            $table->date('starts_on')->nullable();
            $table->date('ends_on')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();

            $table->unique(['room_id', 'rate_type', 'name']);
            $table->index(['room_id', 'is_active']);
        });

        Schema::create('amenities', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->string('icon')->nullable();
            $table->timestamps();
        });

        Schema::create('amenity_room', function (Blueprint $table) {
            $table->foreignId('amenity_id')->constrained()->cascadeOnDelete();
            $table->foreignId('room_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->primary(['amenity_id', 'room_id']);
            $table->index(['room_id', 'amenity_id']);
        });

        Schema::create('room_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained()->cascadeOnDelete();
            $table->string('path');
            $table->string('alt_text')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_primary')->default(false);
            $table->timestamps();

            $table->index(['room_id', 'sort_order']);
        });

        if (DB::connection()->getDriverName() === 'pgsql') {
            DB::statement("ALTER TABLE rooms ADD CONSTRAINT rooms_capacity_positive CHECK (capacity > 0)");
            DB::statement("ALTER TABLE rooms ADD CONSTRAINT rooms_inventory_nonnegative CHECK (inventory_count >= 0)");
            DB::statement("ALTER TABLE rooms ADD CONSTRAINT rooms_status_valid CHECK (status IN ('available', 'unavailable', 'maintenance'))");
            DB::statement("ALTER TABLE room_rates ADD CONSTRAINT room_rates_price_nonnegative CHECK (price >= 0)");
            DB::statement("ALTER TABLE room_rates ADD CONSTRAINT room_rates_dates_valid CHECK (ends_on IS NULL OR starts_on IS NULL OR ends_on >= starts_on)");
            DB::statement("CREATE UNIQUE INDEX room_images_one_primary_per_room ON room_images (room_id) WHERE is_primary = true");
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('room_images');
        Schema::dropIfExists('amenity_room');
        Schema::dropIfExists('amenities');
        Schema::dropIfExists('room_rates');
        Schema::dropIfExists('rooms');
    }
};
