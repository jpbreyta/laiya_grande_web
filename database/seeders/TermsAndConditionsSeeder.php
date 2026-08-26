<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TermsAndConditionsSeeder extends Seeder
{
    public function run(): void
    {
        $terms = [
            [
                'code' => 'TERMS-OVERNIGHT-SCHEDULE',
                'title' => 'Overnight Check-In and Check-Out',
                'description' => 'Overnight check-in time is 2:00 PM and check-out time is 12:00 noon.',
            ],
            [
                'code' => 'TERMS-DAYTOUR-SCHEDULE',
                'title' => 'Day Tour Check-In and Check-Out',
                'description' => 'Day tour check-in time is 8:00 AM and check-out time is 5:00 PM.',
            ],
            [
                'code' => 'TERMS-CONFIRMATION',
                'title' => 'Booking Confirmation',
                'description' => 'Guests must present a copy of their booking confirmation before check-in.',
            ],
            [
                'code' => 'TERMS-CANCELLATION',
                'title' => 'Cancellation Policy',
                'description' => 'Confirmed bookings are non-refundable, non-cancellable, and cannot be rescheduled except when the resort approves a change because of severe weather, government restrictions, or another documented emergency.',
            ],
            [
                'code' => 'TERMS-NO-SHOW',
                'title' => 'No-Show Policy',
                'description' => 'Failure to arrive on the confirmed date may be treated as a no-show and charged according to the approved booking terms.',
            ],
        ];

        foreach ($terms as $index => $term) {
            DB::table('policies')->updateOrInsert(
                [
                    'policy_type' => 'terms_and_conditions',
                    'code' => $term['code'],
                    'version' => 1,
                ],
                [
                    'title' => $term['title'],
                    'description' => $term['description'],
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
