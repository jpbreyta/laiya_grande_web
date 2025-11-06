<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Booking;
use App\Models\Reservation;
use Carbon\Carbon;

class PopulateReservationNumbersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Populate reservation numbers for existing bookings
        $bookings = Booking::whereNull('reservation_number')->get();
        foreach ($bookings as $booking) {
            $booking->update([
                'reservation_number' => $this->generateBookingNumber()
            ]);
        }

        // Populate reservation numbers for existing reservations
        $reservations = Reservation::whereNull('reservation_number')->get();
        foreach ($reservations as $reservation) {
            $reservation->update([
                'reservation_number' => $this->generateReservationNumber()
            ]);
        }
    }

    private function generateBookingNumber(): string
    {
        do {
            $date = Carbon::now()->format('YmdHis');
            $random = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 6));
            $bookingNumber = 'BK-' . $date . '-' . $random;
        } while (Booking::where('reservation_number', $bookingNumber)->exists() ||
                 Reservation::where('reservation_number', $bookingNumber)->exists());

        return $bookingNumber;
    }

    private function generateReservationNumber(): string
    {
        do {
            $date = Carbon::now()->format('YmdHis');
            $random = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 6));
            $reservationNumber = 'RSV-' . $date . '-' . $random;
        } while (Booking::where('reservation_number', $reservationNumber)->exists() ||
                 Reservation::where('reservation_number', $reservationNumber)->exists());

        return $reservationNumber;
    }
}
