<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\GuestStay;
use App\Models\Booking;
use Carbon\Carbon;

class GuestStaySeeder extends Seeder
{
    /**
     * Seed guest stays from existing bookings.
     * Guest stays are created when a booking transitions to 'active' or 'completed' status.
     */
    public function run(): void
    {
        // Get bookings that should have guest stays (active or completed)
        $bookings = Booking::whereIn('status', ['active', 'completed'])
            ->with(['customer', 'room'])
            ->get();

        foreach ($bookings as $booking) {
            // Determine status based on booking status
            $status = $booking->status === 'completed' ? 'checked-out' : 'checked-in';
            
            // Create guest stay
            GuestStay::create([
                'booking_id' => $booking->id,
                'room_id' => $booking->room_id,
                'customer_id' => $booking->customer_id,
                'status' => $status,
                'check_in_time' => $booking->actual_check_in_time ?? $booking->check_in,
                'check_out_time' => $status === 'checked-out' 
                    ? ($booking->actual_check_out_time ?? $booking->check_out) 
                    : null,
            ]);
        }

        $this->command->info('Guest stays seeded successfully from bookings.');
    }
}
