<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TourPackagesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tour_packages')->insert([
            [
                'title' => 'Island Hopping with Snorkeling',
                'description' => "TOUR PACKAGE\n2 HOURS BOAT RIDE ACTIVITY\n- 1 HR ROCK FORMATION\n- 1 HR SNORKELING",
            ],
            [
                'title' => 'Team Building Day Tour Package',
                'description' => "TEAM BUILDING DAY TOUR COMPLETE PACKAGE\nPHP 1,500.00 PER PERSON\n\nINCLUSIONS:\n- ACCOMMODATION: Pavilion or Beach Front\n- MEALS: AM Snack, Lunch, PM Snack\n- BASIC VENUE SETUP: Tent and Tables\n- PROFESSIONAL FACILITATORS: Program, Materials, Certificate",
            ],
            [
                'title' => 'Team Building Overnight Package',
                'description' => "TEAM BUILDING OVERNIGHT COMPLETE PACKAGE\nPHP 2,500.00 PER PERSON\n\nINCLUSIONS:\n- ACCOMMODATION: Air-conditioned Room with Private Comfort Room\n- MEALS: PM Snack, Dinner, Breakfast\n- BASIC VENUE SETUP: Tent and Tables\n- PROFESSIONAL FACILITATORS: Materials, Certificate",
            ],
        ]);
    }
}
