<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FoodCategory;

class FoodCategorySeeder extends Seeder
{
    public function run(): void
    {
        $foodcategory = [
            [
                'name' => 'PM Snacks',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Dinner',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Breakfast',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        FoodCategory::insert($foodcategory);
    }
}