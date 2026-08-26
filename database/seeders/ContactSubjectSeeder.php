<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContactSubjectSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $subjects = [
            'Reservation Inquiry',
            'Booking Assistance',
            'General Question',
            'Feedback',
            'Complaint',
            'Other',
        ];

        $rows = array_map(
            static fn (string $classification): array => [
                'classification' => $classification,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            $subjects
        );

        DB::table('contact_subjects')->upsert(
            $rows,
            ['classification'],
            ['is_active', 'updated_at']
        );
    }
}
