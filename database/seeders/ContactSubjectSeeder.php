<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContactSubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * This method populates the 'contact_subjects' table with the
     * classification values taken from the contact form's dropdown.
     */
    public function run(): void
    {
        // Define the standardized subjects for the contact form dropdown
        $subjects = [
            'Reservation Inquiry',
            'Booking Assistance',
            'General Question',
            'Feedback',
            'Complaint',
            'Other',
        ];

        // Insert subjects if they don't already exist to prevent duplicates on re-seeding
        foreach ($subjects as $subject) {
            DB::table('contact_subjects')->updateOrInsert(
                ['classification' => $subject],
                [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}