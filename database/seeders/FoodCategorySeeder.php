<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FoodCategorySeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        DB::table('catalog_categories')->upsert([
            [
                'name' => 'PM Snacks',
                'slug' => 'pm-snacks',
                'description' => 'Afternoon snack selections.',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Dinner',
                'slug' => 'dinner',
                'description' => 'Dinner meal selections.',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Breakfast',
                'slug' => 'breakfast',
                'description' => 'Breakfast meal selections.',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ], ['slug'], ['name', 'description', 'is_active', 'updated_at']);
    }
}
