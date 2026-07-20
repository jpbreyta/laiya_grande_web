<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HouseRulesSeeder extends Seeder
{
    public function run(): void
    {
        $rules = [
            ['RULE-ELECTRICAL-APPLIANCES', 'Electrical Appliances', 'Bringing electrical appliances such as rice cookers or induction cookers is subject to a PHP 500 charge per appliance.'],
            ['RULE-LOST-KEY', 'Lost Key', 'A lost room key is subject to a PHP 500 replacement charge.'],
            ['RULE-SWIMMING-HOURS', 'Swimming Hours', 'Swimming hours are from 6:00 AM to 6:00 PM.'],
            ['RULE-ALCOHOL-SWIMMING', 'Swimming and Alcohol', 'Swimming while under the influence of alcohol is strictly prohibited.'],
            ['RULE-CHILD-SUPERVISION', 'Child Supervision', 'Children younger than 10 years old must always be accompanied and supervised by a parent or guardian.'],
            ['RULE-QUIET-HOURS', 'Quiet Hours', 'Loud noise is prohibited from 10:00 PM to 8:00 AM.'],
            ['RULE-EXTENDED-HOURS', 'Extended Hours', 'Approved extensions are subject to a PHP 500 charge per hour.'],
            ['RULE-BEACHFRONT-GATE', 'Beachfront Gate', 'The beachfront gate closes at 10:00 PM.'],
            ['RULE-WATER-SPORT-OWNERSHIP', 'Water Sport Operators', 'Water sport activities may be provided by independent operators and are not directly owned by the resort.'],
            ['RULE-WATER-SPORT-LIABILITY', 'Water Sport Responsibility', 'Guests must follow the independent operator’s safety rules and applicable resort policies when participating in water sport activities.'],
        ];

        foreach ($rules as $index => [$code, $title, $description]) {
            DB::table('policies')->updateOrInsert(
                [
                    'policy_type' => 'house_rule',
                    'code' => $code,
                    'version' => 1,
                ],
                [
                    'title' => $title,
                    'description' => $description,
                    'is_active' => true,
                    'effective_at' => null,
                    'sort_order' => $index + 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
