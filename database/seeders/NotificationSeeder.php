<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Notification;
use App\Models\Booking;
use App\Models\Reservation;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create notifications for existing bookings
        $bookings = Booking::all();
        foreach ($bookings as $booking) {
            // Check if notification already exists
            $existing = Notification::where('type', 'booking')
                ->where('data->booking_id', $booking->id)
                ->first();

            if (!$existing) {
                Notification::create([
                    'type' => 'booking',
                    'title' => 'New Booking Request',
                    'message' => "New booking from {$booking->firstname} {$booking->lastname} for {$booking->number_of_guests} guest(s)",
                    'data' => [
                        'booking_id' => $booking->id,
                        'name' => $booking->firstname . ' ' . $booking->lastname,
                        'email' => $booking->email,
                        'phone' => $booking->phone_number,
                        'check_in' => $booking->check_in,
                        'check_out' => $booking->check_out,
                        'guests' => $booking->number_of_guests,
                    ],
                    'read' => false,
                ]);
            }
        }

        // Create notifications for existing reservations
        $reservations = Reservation::all();
        foreach ($reservations as $reservation) {
            // Check if notification already exists
            $existing = Notification::where('type', 'reservation')
                ->where('data->reservation_id', $reservation->id)
                ->first();

            if (!$existing) {
                Notification::create([
                    'type' => 'reservation',
                    'title' => 'New Reservation Request',
                    'message' => "New reservation from {$reservation->firstname} {$reservation->lastname} for {$reservation->number_of_guests} guest(s)",
                    'data' => [
                        'reservation_id' => $reservation->id,
                        'name' => $reservation->firstname . ' ' . $reservation->lastname,
                        'email' => $reservation->email,
                        'phone' => $reservation->phone_number,
                        'check_in' => $reservation->check_in,
                        'check_out' => $reservation->check_out,
                        'guests' => $reservation->number_of_guests,
                    ],
                    'read' => false,
                ]);
            }
        }
    }
}
