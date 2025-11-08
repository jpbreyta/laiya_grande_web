<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HouseRulesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rules = [
            ['description' => 'Bringing of ELECTRICAL APPLIANCES such as rice cooker, induction, etc. will be charged PHP 500.00 EACH.'],
            ['description' => 'LOST KEY will be CHARGED PHP 500.00.'],
            ['description' => 'SWIMMING TIME is from 6:00 AM to 6:00 PM.'],
            ['description' => 'SWIMMING while UNDER THE INFLUENCE OF ALCOHOL IS STRONGLY PROHIBITED.'],
            ['description' => 'CHILDREN younger than 10 YEARS OLD should always be ACCOMPANIED and SUPERVISED by a Parent or Guardian anywhere in the resort.'],
            ['description' => 'LOUD NOISE/SOUNDS are STRICTLY PROHIBITED from 10:00 PM to 8:00 AM.'],
            ['description' => 'EXTENDED HOURS will be CHARGED PHP 500.00 PER HOUR.'],
            ['description' => 'BEACHFRONT GATE CLOSES AT 10:00 PM.'],
            ['description' => 'ALL Water Sports Activities are not directly owned by the resort.'],
            ['description' => 'The resort is NOT RESPONSIBLE for any UNWANTED EVENTS that may happen related to those activities.'],
        ];

        DB::table('house_rules')->insert($rules);
    }
}
