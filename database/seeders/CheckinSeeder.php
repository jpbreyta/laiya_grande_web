<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Booking;
use Carbon\Carbon;

class CheckinSeeder extends Seeder
{
    public function run(): void
    {
        // Get existing bookings from ExampleBookingsReservationsSeeder
        $bookings = Booking::whereIn('email', [
            'julience.castillo@example.com',
            'jaika.madrid@example.com',
            'aldren.perez@example.com',
            'john.reyta@example.com'
        ])->get();

        if ($bookings->isEmpty()) {
            return; // No bookings to update
        }

        // Update first 2 bookings: set checkin time, status to active
        $firstTwo = $bookings->take(2);
        foreach ($firstTwo as $booking) {
            $booking->update([
                'actual_check_in_time' => Carbon::parse($booking->check_in)->addHour(),
                'status' => 'active'
            ]);
        }

        // Update last 2 bookings: set checkin and checkout times, status to completed
        $lastTwo = $bookings->skip(2);
        foreach ($lastTwo as $booking) {
            $booking->update([
                'actual_check_in_time' => Carbon::parse($booking->check_in)->addHour(),
                'actual_check_out_time' => Carbon::parse($booking->check_out)->subHour(),
                'status' => 'completed'
            ]);
        }
    }
}
