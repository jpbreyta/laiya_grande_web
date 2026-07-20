<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class RentalItemSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedCategories();

        $categoryIds = DB::table('catalog_categories')
            ->whereIn('slug', [
                'kitchen-rentals',
                'entertainment-rentals',
                'toiletry-rentals',
            ])
            ->pluck('id', 'slug');

        $items = [
            ['RENT-GAS-STOVE', 'kitchen-rentals', 'Portable Gas Stove', 250.00, 10],
            ['RENT-PLATES-6', 'kitchen-rentals', 'Extra Plates (Set of 6)', 50.00, 50],
            ['RENT-KARAOKE', 'entertainment-rentals', 'Karaoke Machine', 1000.00, 3],
            ['RENT-RICE-COOKER', 'kitchen-rentals', 'Rice Cooker', 150.00, 5],
            ['RENT-GRILL-SET', 'kitchen-rentals', 'Grill Set', 200.00, 8],
            ['RENT-TOWEL', 'toiletry-rentals', 'Towel Rental', 100.00, 100],
        ];

        $rows = [];
        $now = now();

        foreach ($items as [$sku, $categorySlug, $name, $price, $stock]) {
            $categoryId = $categoryIds->get($categorySlug);

            if ($categoryId === null) {
                throw new RuntimeException(
                    "Rental category '{$categorySlug}' was not seeded."
                );
            }

            $rows[] = [
                'catalog_category_id' => $categoryId,
                'item_type' => 'rental',
                'sku' => $sku,
                'name' => $name,
                'description' => null,
                'pricing_details' => 'Rental price per item.',
                'unit_price' => $price,
                'pax_capacity' => null,
                'min_participants' => null,
                'duration_minutes' => null,
                'tracks_inventory' => true,
                'stock_quantity' => $stock,
                'is_available' => $stock > 0,
                'metadata' => null,
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
                'tracks_inventory',
                'stock_quantity',
                'is_available',
                'updated_at',
                'deleted_at',
            ]
        );
    }

    private function seedCategories(): void
    {
        $now = now();

        DB::table('catalog_categories')->upsert([
            [
                'name' => 'Kitchen Rentals',
                'slug' => 'kitchen-rentals',
                'description' => 'Kitchen equipment and dining-item rentals.',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Entertainment Rentals',
                'slug' => 'entertainment-rentals',
                'description' => 'Entertainment equipment rentals.',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Toiletry Rentals',
                'slug' => 'toiletry-rentals',
                'description' => 'Guest toiletry and linen rentals.',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ], ['slug'], ['name', 'description', 'is_active', 'updated_at']);
    }
}
