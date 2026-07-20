<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DefaultRolesSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        DB::table('roles')->upsert([
            [
                'name' => 'admin',
                'display_name' => 'Administrator',
                'description' => 'Full system and security administration access.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'manager',
                'display_name' => 'Manager',
                'description' => 'Operational management and reporting access.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'receptionist',
                'display_name' => 'Receptionist',
                'description' => 'Booking, guest stay, payment, and contact-message access.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'staff',
                'display_name' => 'Staff',
                'description' => 'Standard authenticated operational access.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ], ['name'], ['display_name', 'description', 'updated_at']);
    }
}
