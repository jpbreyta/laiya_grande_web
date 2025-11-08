<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TermsAndConditionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $terms = [
            ['description' => 'Overnight Check-In Time is 2:00 PM and Check-Out Time is 12:00 NN.'],
            ['description' => 'Daytour Check-In Time is 8:00 AM and Check-Out Time is 5:00 PM.'],
            ['description' => 'Guests must present a copy of their booking confirmation before check-in.'],
            ['description' => 'Cancellation Policy: Confirmed bookings are non-refundable, non-cancellable, and cannot be rescheduled unless due to typhoon or quarantine protocol restrictions.'],
            ['description' => 'Failure to arrive at the resort will be considered a No Show and will be charged 100%.'],
        ];

        DB::table('terms_and_conditions')->insert($terms);
    }
}
