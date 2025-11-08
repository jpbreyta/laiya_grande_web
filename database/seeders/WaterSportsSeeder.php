<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WaterSportsSeeder extends Seeder
{
    public function run(): void
    {
        $activities = [
            [
                'name' => 'Hurricane Boat',
                'price_details' => 'PHP 400 per head, minimum of 6 persons for 15 minutes',
                'min_participants' => 6,
                'duration_minutes' => 15,
            ],
            [
                'name' => 'Banana Boat',
                'price_details' => 'PHP 300 per head, minimum of 5 persons for 15 minutes',
                'min_participants' => 5,
                'duration_minutes' => 15,
            ],
            [
                'name' => 'Flying Fish',
                'price_details' => 'PHP 400 per head, minimum of 6 persons for 15 minutes',
                'min_participants' => 6,
                'duration_minutes' => 15,
            ],
            [
                'name' => 'Jetski',
                'price_details' => 'PHP 4,500 per hour / PHP 2,500 per half hour',
                'min_participants' => null,
                'duration_minutes' => null,
            ],
            [
                'name' => 'Kayak',
                'price_details' => 'PHP 500 per hour',
                'min_participants' => null,
                'duration_minutes' => 60,
            ],
            [
                'name' => 'Crystal Kayak',
                'price_details' => 'PHP 800 per hour',
                'min_participants' => null,
                'duration_minutes' => 60,
            ],
        ];

        DB::table('water_sports')->insert($activities);
    }
}
