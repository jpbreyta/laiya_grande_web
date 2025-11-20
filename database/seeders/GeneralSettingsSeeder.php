<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\GeneralSetting;

class GeneralSettingsSeeder extends Seeder
{
    public function run(): void
    {
        if (!GeneralSetting::exists()) {

            GeneralSetting::create([
                // General Info / Branding
                'resort_name' => 'Laiya Grande Beach Resort',
                'tagline' => 'Your tropical escape in San Juan, Batangas',
                'description' => 'Experience paradise with pristine beaches, luxury accommodations, and world-class amenities in the heart of Batangas.',

                // Contact Info
                'contact_email' => 'laiyagrandebr22@gmail.com',
                'contact_phone' => '0963 033 7629',
                'contact_address' => 'Laiya, San Juan, Philippines, 4226',

                // Socials (if you don’t have actual links yet, these stay null)
                'social_facebook' => null,
                'social_instagram' => null,
                'social_twitter' => null,
                'social_tripadvisor' => null,

                // Business Hours — set defaults (you can edit later)
                'reception_hours_start' => '08:00',
                'reception_hours_end'   => '20:00',
                'restaurant_hours_start' => '07:00',
                'restaurant_hours_end'   => '22:00',
                'pool_hours_start' => '06:00',
                'pool_hours_end'   => '21:00',
                'activities_hours_start' => '08:00',
                'activities_hours_end'   => '17:00',

                // System Preferences
                'currency' => 'PHP',
                'date_format' => 'd/m/Y',
                'time_format' => '12',

                // Images (set null, admin can upload later)
                'logo_path' => null,
                'favicon_path' => null,
            ]);
        }
    }
}
