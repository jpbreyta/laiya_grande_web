<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TourPackagesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tour_packages')->insert([
            'title' => 'Island Hopping with Snorkeling',
            'description' => "TOUR PACKAGE\n2 HOURS BOAT RIDE ACTIVITY\n- 1 HR ROCK FORMATION\n- 1 HR SNORKELING",
            'contact_info' => '0963-033-7629',
        ]);
    }
}
