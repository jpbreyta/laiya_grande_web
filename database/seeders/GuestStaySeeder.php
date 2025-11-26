<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\GuestStay;
use App\Models\Room;
use Carbon\Carbon;

class GuestStaySeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ensure we have rooms to assign these guests to
        $rooms = Room::all();

        if ($rooms->isEmpty()) {
            $this->command->warn("No rooms found! Please seed rooms first.");
            return;
        }

        // 2. Create Dummy "Checked-In" Guests
        $guests = [
            [
                'guest_name' => 'Juan Dela Cruz',
                'room_id' => $rooms->get(0)->id ?? 1, // Assign to first available room
                'status' => 'checked-in',
                'check_in_time' => Carbon::now()->subHours(2), // Arrived 2 hours ago
                'check_out_time' => null, // STILL HERE
            ],
            [
                'guest_name' => 'Maria Clara',
                'room_id' => $rooms->get(1)->id ?? 2, // Assign to second room
                'status' => 'checked-in',
                'check_in_time' => Carbon::now()->subDay(), // Arrived yesterday
                'check_out_time' => null, // STILL HERE
            ],
            [
                'guest_name' => 'John Smith (VIP)',
                'room_id' => $rooms->get(2)->id ?? 3, // Assign to third room
                'status' => 'checked-in',
                'check_in_time' => Carbon::now()->subMinutes(30), // Just arrived
                'check_out_time' => null, // STILL HERE
            ]
        ];

        foreach ($guests as $data) {
            GuestStay::create($data);
        }
    }
}