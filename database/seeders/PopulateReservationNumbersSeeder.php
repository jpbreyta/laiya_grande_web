<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PopulateReservationNumbersSeeder extends Seeder
{
    public function run(): void
    {
        $bookings = DB::table('bookings')
            ->where(function ($query): void {
                $query->whereNull('booking_number')
                    ->orWhere('booking_number', '');
            })
            ->orderBy('id')
            ->get();

        foreach ($bookings as $booking) {
            DB::table('bookings')
                ->where('id', $booking->id)
                ->update([
                    'booking_number' => $this->generateBookingNumber(),
                    'updated_at' => now(),
                ]);
        }
    }

    private function generateBookingNumber(): string
    {
        do {
            $bookingNumber = 'BK-'
                . now()->format('Ymd')
                . '-'
                . Str::upper(Str::random(8));
        } while (
            DB::table('bookings')
                ->where('booking_number', $bookingNumber)
                ->exists()
        );

        return $bookingNumber;
    }
}
