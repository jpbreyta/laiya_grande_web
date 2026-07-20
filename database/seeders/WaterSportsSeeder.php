<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class WaterSportsSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        DB::table('catalog_categories')->upsert([
            [
                'name' => 'Water Sports',
                'slug' => 'water-sports',
                'description' => 'Water-based recreational activities.',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ], ['slug'], ['name', 'description', 'is_active', 'updated_at']);

        $categoryId = DB::table('catalog_categories')
            ->where('slug', 'water-sports')
            ->value('id');

        if ($categoryId === null) {
            throw new RuntimeException('Water Sports category was not seeded.');
        }

        $activities = [
            [
                'sku' => 'WATER-HURRICANE-BOAT',
                'name' => 'Hurricane Boat',
                'unit_price' => 400.00,
                'pricing_details' => 'PHP 400 per person, minimum of 6 persons for 15 minutes.',
                'min_participants' => 6,
                'duration_minutes' => 15,
                'metadata' => null,
            ],
            [
                'sku' => 'WATER-BANANA-BOAT',
                'name' => 'Banana Boat',
                'unit_price' => 300.00,
                'pricing_details' => 'PHP 300 per person, minimum of 5 persons for 15 minutes.',
                'min_participants' => 5,
                'duration_minutes' => 15,
                'metadata' => null,
            ],
            [
                'sku' => 'WATER-FLYING-FISH',
                'name' => 'Flying Fish',
                'unit_price' => 400.00,
                'pricing_details' => 'PHP 400 per person, minimum of 6 persons for 15 minutes.',
                'min_participants' => 6,
                'duration_minutes' => 15,
                'metadata' => null,
            ],
            [
                'sku' => 'WATER-JETSKI',
                'name' => 'Jetski',
                'unit_price' => 2500.00,
                'pricing_details' => 'PHP 2,500 per 30 minutes or PHP 4,500 per hour.',
                'min_participants' => 1,
                'duration_minutes' => 30,
                'metadata' => json_encode([
                    'hourly_price' => 4500.00,
                    'hourly_duration_minutes' => 60,
                ], JSON_THROW_ON_ERROR),
            ],
            [
                'sku' => 'WATER-KAYAK',
                'name' => 'Kayak',
                'unit_price' => 500.00,
                'pricing_details' => 'PHP 500 per hour.',
                'min_participants' => 1,
                'duration_minutes' => 60,
                'metadata' => null,
            ],
            [
                'sku' => 'WATER-CRYSTAL-KAYAK',
                'name' => 'Crystal Kayak',
                'unit_price' => 800.00,
                'pricing_details' => 'PHP 800 per hour.',
                'min_participants' => 1,
                'duration_minutes' => 60,
                'metadata' => null,
            ],
        ];

        $rows = array_map(
            static fn (array $activity): array => [
                'catalog_category_id' => $categoryId,
                'item_type' => 'water_sport',
                'sku' => $activity['sku'],
                'name' => $activity['name'],
                'description' => null,
                'pricing_details' => $activity['pricing_details'],
                'unit_price' => $activity['unit_price'],
                'pax_capacity' => null,
                'min_participants' => $activity['min_participants'],
                'duration_minutes' => $activity['duration_minutes'],
                'tracks_inventory' => false,
                'stock_quantity' => null,
                'is_available' => true,
                'metadata' => $activity['metadata'],
                'created_at' => $now,
                'updated_at' => $now,
                'deleted_at' => null,
            ],
            $activities
        );

        DB::table('catalog_items')->upsert(
            $rows,
            ['sku'],
            [
                'catalog_category_id',
                'item_type',
                'name',
                'pricing_details',
                'unit_price',
                'min_participants',
                'duration_minutes',
                'is_available',
                'metadata',
                'updated_at',
                'deleted_at',
            ]
        );
    }
}
