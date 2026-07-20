<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GeneralSettingsSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('general_settings')->updateOrInsert(
            ['singleton_key' => true],
            [
                'resort_name' => 'Laiya Grande Beach Resort',
                'tagline' => 'Your tropical escape in San Juan, Batangas',
                'description' => 'Experience a relaxing beach getaway with comfortable accommodations and resort activities in San Juan, Batangas.',
                'contact_email' => 'laiyagrandebr22@gmail.com',
                'contact_phone' => '0963 033 7629',
                'contact_address' => 'Laiya, San Juan, Batangas, Philippines 4226',
                'social_facebook' => null,
                'social_instagram' => null,
                'social_twitter' => null,
                'social_tripadvisor' => null,
                'reception_hours_start' => '08:00:00',
                'reception_hours_end' => '20:00:00',
                'restaurant_hours_start' => '07:00:00',
                'restaurant_hours_end' => '22:00:00',
                'pool_hours_start' => '06:00:00',
                'pool_hours_end' => '21:00:00',
                'activities_hours_start' => '08:00:00',
                'activities_hours_end' => '17:00:00',
                'currency' => 'PHP',
                'date_format' => 'd/m/Y',
                'time_format' => 'h:i A',
                'logo_path' => null,
                'favicon_path' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
