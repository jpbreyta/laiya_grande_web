<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class TourPackagesSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        DB::table('catalog_categories')->upsert([
            [
                'name' => 'Tour Packages',
                'slug' => 'tour-packages',
                'description' => 'Resort tours and group packages.',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ], ['slug'], ['name', 'description', 'is_active', 'updated_at']);

        $categoryId = DB::table('catalog_categories')
            ->where('slug', 'tour-packages')
            ->value('id');

        if ($categoryId === null) {
            throw new RuntimeException('Tour Packages category was not seeded.');
        }

        $rows = [
            [
                'sku' => 'TOUR-ISLAND-HOPPING',
                'name' => 'Island Hopping with Snorkeling',
                'description' => "Two-hour boat activity with one hour at the rock formation and one hour of snorkeling.",
                'pricing_details' => 'Contact the resort for the current package price.',
                'unit_price' => null,
                'duration_minutes' => 120,
            ],
            [
                'sku' => 'TOUR-TEAM-BUILDING-DAY',
                'name' => 'Team Building Day Tour Package',
                'description' => "Includes pavilion or beachfront accommodation, AM snack, lunch, PM snack, basic venue setup, facilitators, materials, and certificates.",
                'pricing_details' => 'PHP 1,500 per person.',
                'unit_price' => 1500.00,
                'duration_minutes' => null,
            ],
            [
                'sku' => 'TOUR-TEAM-BUILDING-OVERNIGHT',
                'name' => 'Team Building Overnight Package',
                'description' => "Includes an air-conditioned room with a private bathroom, PM snack, dinner, breakfast, basic venue setup, facilitators, materials, and certificates.",
                'pricing_details' => 'PHP 2,500 per person.',
                'unit_price' => 2500.00,
                'duration_minutes' => null,
            ],
        ];

        $items = array_map(
            static fn (array $row): array => [
                'catalog_category_id' => $categoryId,
                'item_type' => 'tour_package',
                'sku' => $row['sku'],
                'name' => $row['name'],
                'description' => $row['description'],
                'pricing_details' => $row['pricing_details'],
                'unit_price' => $row['unit_price'],
                'pax_capacity' => null,
                'min_participants' => null,
                'duration_minutes' => $row['duration_minutes'],
                'tracks_inventory' => false,
                'stock_quantity' => null,
                'is_available' => true,
                'metadata' => null,
                'created_at' => $now,
                'updated_at' => $now,
                'deleted_at' => null,
            ],
            $rows
        );

        DB::table('catalog_items')->upsert(
            $items,
            ['sku'],
            [
                'catalog_category_id',
                'item_type',
                'name',
                'description',
                'pricing_details',
                'unit_price',
                'duration_minutes',
                'is_available',
                'updated_at',
                'deleted_at',
            ]
        );
    }
}
