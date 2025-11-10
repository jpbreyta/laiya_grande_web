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
            ['name' => 'PALABOK', 'price' => 150.00, 'no_of_pax' => 30, 'food_category_id' => $pmSnacksId, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'PUTO AND KAKANIN', 'price' => 120.00, 'no_of_pax' => 30, 'food_category_id' => $pmSnacksId, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'SAGO SULAMAN', 'price' => 100.00, 'no_of_pax' => 30, 'food_category_id' => $pmSnacksId, 'created_at' => now(), 'updated_at' => now()],

            // Dinner
            ['name' => 'PORK SINIGANG', 'price' => 250.00, 'no_of_pax' => 30, 'food_category_id' => $dinnerId, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'CHICKEN INASAL', 'price' => 220.00, 'no_of_pax' => 30, 'food_category_id' => $dinnerId, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'INIHAW NA TALONG WITH ALAMANG', 'price' => 180.00, 'no_of_pax' => 30, 'food_category_id' => $dinnerId, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'UNLIMITED RICE', 'price' => 50.00, 'no_of_pax' => 30, 'food_category_id' => $dinnerId, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'FRUIT SALAD', 'price' => 80.00, 'no_of_pax' => 30, 'food_category_id' => $dinnerId, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'DRINKS', 'price' => 60.00, 'no_of_pax' => 30, 'food_category_id' => $dinnerId, 'created_at' => now(), 'updated_at' => now()],

            // Breakfast
            ['name' => 'BEEF TAPA', 'price' => 200.00, 'no_of_pax' => 30, 'food_category_id' => $breakfastId, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'SMOKED FISH', 'price' => 180.00, 'no_of_pax' => 30, 'food_category_id' => $breakfastId, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'EGG', 'price' => 30.00, 'no_of_pax' => 30, 'food_category_id' => $breakfastId, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'RICE', 'price' => 20.00, 'no_of_pax' => 30, 'food_category_id' => $breakfastId, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'KAPENG BARAKO', 'price' => 40.00, 'no_of_pax' => 30, 'food_category_id' => $breakfastId, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'BANANA', 'price' => 25.00, 'no_of_pax' => 30, 'food_category_id' => $breakfastId, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
