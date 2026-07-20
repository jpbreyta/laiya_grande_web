<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class FoodSeeder extends Seeder
{
    public function run(): void
    {
        $categoryIds = DB::table('catalog_categories')
            ->whereIn('slug', ['pm-snacks', 'dinner', 'breakfast'])
            ->pluck('id', 'slug');

        foreach (['pm-snacks', 'dinner', 'breakfast'] as $requiredSlug) {
            if (!$categoryIds->has($requiredSlug)) {
                throw new RuntimeException(
                    "Catalog category '{$requiredSlug}' is missing. Run FoodCategorySeeder first."
                );
            }
        }

        $items = [
            ['FOOD-PALABOK', 'pm-snacks', 'Palabok', 150.00],
            ['FOOD-PUTO-KAKANIN', 'pm-snacks', 'Puto and Kakanin', 120.00],
            ['FOOD-SAGO-GULAMAN', 'pm-snacks', 'Sago at Gulaman', 100.00],
            ['FOOD-PORK-SINIGANG', 'dinner', 'Pork Sinigang', 250.00],
            ['FOOD-CHICKEN-INASAL', 'dinner', 'Chicken Inasal', 220.00],
            ['FOOD-TALONG-ALAMANG', 'dinner', 'Inihaw na Talong with Alamang', 180.00],
            ['FOOD-UNLIMITED-RICE', 'dinner', 'Unlimited Rice', 50.00],
            ['FOOD-FRUIT-SALAD', 'dinner', 'Fruit Salad', 80.00],
            ['FOOD-DRINKS', 'dinner', 'Drinks', 60.00],
            ['FOOD-BEEF-TAPA', 'breakfast', 'Beef Tapa', 200.00],
            ['FOOD-SMOKED-FISH', 'breakfast', 'Smoked Fish', 180.00],
            ['FOOD-EGG', 'breakfast', 'Egg', 30.00],
            ['FOOD-RICE', 'breakfast', 'Rice', 20.00],
            ['FOOD-KAPENG-BARAKO', 'breakfast', 'Kapeng Barako', 40.00],
            ['FOOD-BANANA', 'breakfast', 'Banana', 25.00],
        ];

        $now = now();
        $rows = [];

        foreach ($items as [$sku, $categorySlug, $name, $price]) {
            $rows[] = [
                'catalog_category_id' => $categoryIds[$categorySlug],
                'item_type' => 'food',
                'sku' => $sku,
                'name' => $name,
                'description' => null,
                'pricing_details' => 'Price per serving.',
                'unit_price' => $price,
                'pax_capacity' => 30,
                'min_participants' => null,
                'duration_minutes' => null,
                'tracks_inventory' => false,
                'stock_quantity' => null,
                'is_available' => true,
                'metadata' => json_encode(
                    ['legacy_no_of_pax' => 30],
                    JSON_THROW_ON_ERROR
                ),
                'created_at' => $now,
                'updated_at' => $now,
                'deleted_at' => null,
            ];
        }

        DB::table('catalog_items')->upsert(
            $rows,
            ['sku'],
            [
                'catalog_category_id',
                'item_type',
                'name',
                'description',
                'pricing_details',
                'unit_price',
                'pax_capacity',
                'min_participants',
                'duration_minutes',
                'tracks_inventory',
                'stock_quantity',
                'is_available',
                'metadata',
                'updated_at',
                'deleted_at',
            ]
        );
    }
}
