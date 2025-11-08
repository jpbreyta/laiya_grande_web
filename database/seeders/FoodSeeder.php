<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FoodSeeder extends Seeder
{
    public function run(): void
    {
        // Get category IDs dynamically (avoids hardcoding)
        $pmSnacksId = DB::table('food_categories')->where('name', 'PM Snacks')->value('id');
        $dinnerId   = DB::table('food_categories')->where('name', 'Dinner')->value('id');
        $breakfastId= DB::table('food_categories')->where('name', 'Breakfast')->value('id');

        // Insert foods
        DB::table('foods')->insert([
            // PM Snacks
            ['name' => 'PALABOK', 'no_of_pax' => 30, 'food_category_id' => $pmSnacksId, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'PUTO AND KAKANIN', 'no_of_pax' => 30, 'food_category_id' => $pmSnacksId, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'SAGO SULAMAN', 'no_of_pax' => 30, 'food_category_id' => $pmSnacksId, 'created_at' => now(), 'updated_at' => now()],

            // Dinner
            ['name' => 'PORK SINIGANG', 'no_of_pax' => 30, 'food_category_id' => $dinnerId, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'CHICKEN INASAL', 'no_of_pax' => 30, 'food_category_id' => $dinnerId, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'INIHAW NA TALONG WITH ALAMANG', 'no_of_pax' => 30, 'food_category_id' => $dinnerId, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'UNLIMITED RICE', 'no_of_pax' => 30, 'food_category_id' => $dinnerId, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'FRUIT SALAD', 'no_of_pax' => 30, 'food_category_id' => $dinnerId, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'DRINKS', 'no_of_pax' => 30, 'food_category_id' => $dinnerId, 'created_at' => now(), 'updated_at' => now()],

            // Breakfast
            ['name' => 'BEEF TAPA', 'no_of_pax' => 30, 'food_category_id' => $breakfastId, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'SMOKED FISH', 'no_of_pax' => 30, 'food_category_id' => $breakfastId, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'EGG', 'no_of_pax' => 30, 'food_category_id' => $breakfastId, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'RICE', 'no_of_pax' => 30, 'food_category_id' => $breakfastId, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'KAPENG BARAKO', 'no_of_pax' => 30, 'food_category_id' => $breakfastId, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'BANANA', 'no_of_pax' => 30, 'food_category_id' => $breakfastId, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
