<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Booking;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\Customer;
use App\Models\Payment;
use Carbon\Carbon;

class ExampleBookingsReservationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure at least 4 rooms exist
        $rooms = Room::take(4)->get();
        if ($rooms->isEmpty()) {
            // NOTE: Assuming you have a RoomFactory defined
            $rooms = Room::factory(4)->create();
        }

        // Define a list of unique customers we will use
        $customerData = [
            [
                'firstname' => 'Julience',
                'lastname' => 'Castillo',
                'email' => 'julience.castillo@example.com',
                'phone_number' => '09123456789',
            ],
            [
                'firstname' => 'Jaika Remina',
                'lastname' => 'Madrid',
                'email' => 'jaika.madrid@example.com',
                'phone_number' => '09123456790',
            ],
            [
                'firstname' => 'Aldren',
                'lastname' => 'Perez',
                'email' => 'aldren.perez@example.com',
                'phone_number' => '09123456791',
            ],
            [
                'firstname' => 'John Paul Bryan',
                'lastname' => 'Reyta',
                'email' => 'john.reyta@example.com',
                'phone_number' => '09123456792',
            ],
        ];

        // 1. Create or retrieve the customers and map them for easy lookup
        $customers = [];
        foreach ($customerData as $data) {
            $customer = Customer::firstOrCreate(
                ['email' => $data['email']], // Check only by unique email
                $data // Data to create if not found
            );
            // Map customer IDs using their email for the booking/reservation arrays
            $customers[$data['email']] = $customer->id;
        }

        // Generate timestamp for demo
        $timestamp = Carbon::now()->format('YmdHis');

        // === BOOKINGS ===
        $bookings = [
            [
                'room_id' => $rooms[0]->id,
                // FIX: Replaced guest details with customer_id
                'customer_id' => $customers['julience.castillo@example.com'], 
                'check_in' => Carbon::now()->subDays(10),
                'check_out' => Carbon::now()->subDays(8),
                'number_of_guests' => 2,
                'special_request' => 'Late check-in requested',
                'payment_method' => 'gcash',
                'payment' => 'full',
                'total_price' => 15000,
                'status' => 'confirmed',
                'reservation_number' => "BK-{$timestamp}-A1B2C3",
            ],
            [
                'room_id' => $rooms[1]->id,
                // FIX: Replaced guest details with customer_id
                'customer_id' => $customers['jaika.madrid@example.com'],
                'check_in' => Carbon::now()->subDays(15),
                'check_out' => Carbon::now()->subDays(12),
                'number_of_guests' => 3,
                'special_request' => 'Extra towels needed',
                'payment_method' => 'paymaya',
                'payment' => 'full',
                'total_price' => 22500,
                'status' => 'confirmed',
                'reservation_number' => "BK-{$timestamp}-D4E5F6",
            ],
            [
                'room_id' => $rooms[2]->id,
                // FIX: Replaced guest details with customer_id
                'customer_id' => $customers['aldren.perez@example.com'],
                'check_in' => Carbon::now()->subDays(20),
                'check_out' => Carbon::now()->subDays(18),
                'number_of_guests' => 1,
                'special_request' => 'Quiet room preferred',
                'payment_method' => 'bank_transfer',
                'payment' => 'full',
                'total_price' => 12000,
                'status' => 'confirmed',
                'reservation_number' => "BK-{$timestamp}-G7H8I9",
            ],
            [
                'room_id' => $rooms[3]->id,
                // FIX: Replaced guest details with customer_id
                'customer_id' => $customers['john.reyta@example.com'],
                'check_in' => Carbon::now()->subDays(25),
                'check_out' => Carbon::now()->subDays(22),
                'number_of_guests' => 4,
                'special_request' => 'Birthday celebration setup',
                'payment_method' => 'gcash',
                'payment' => 'full',
                'total_price' => 30000,
                'status' => 'confirmed',
                'reservation_number' => "BK-{$timestamp}-J1K2L3",
            ],
        ];

        foreach ($bookings as $bookingData) {
            // Remove the old 'payment' field
            unset($bookingData['payment']);
            
            // Create booking
            $booking = Booking::create($bookingData);
            
            // Create payment record in payments table
            Payment::create([
                'booking_id' => $booking->id,
                'customer_name' => $booking->customer->firstname . ' ' . $booking->customer->lastname,
                'contact_number' => $booking->customer->phone_number,
                'payment_date' => $booking->created_at,
                'amount_paid' => $booking->total_price,
                'payment_stage' => 'full',
                'status' => 'verified',
                'payment_method' => $booking->payment_method,
                'verified_at' => $booking->created_at,
            ]);
        }

        // === RESERVATIONS ===
        $reservations = [
            [
                'room_id' => $rooms[0]->id,
                // FIX: Replaced guest details with customer_id
                'customer_id' => $customers['julience.castillo@example.com'],
                'check_in' => Carbon::now()->addDays(5),
                'check_out' => Carbon::now()->addDays(7),
                'number_of_guests' => 2,
                'special_request' => 'Late check-in requested',
                'payment_method' => 'gcash',
                'payment' => 'full',
                'total_price' => 15000,
                'status' => 'confirmed',
                'expires_at' => Carbon::now()->addHours(24),
                'reservation_number' => "RSV-{$timestamp}-M4N5O6",
            ],
            [
                'room_id' => $rooms[1]->id,
                // FIX: Replaced guest details with customer_id
                'customer_id' => $customers['jaika.madrid@example.com'],
                'check_in' => Carbon::now()->addDays(10),
                'check_out' => Carbon::now()->addDays(12),
                'number_of_guests' => 3,
                'special_request' => 'Extra towels needed',
                'payment_method' => 'paymaya',
                'payment' => 'full',
                'total_price' => 22500,
                'status' => 'confirmed',
                'expires_at' => Carbon::now()->addHours(24),
                'reservation_number' => "RSV-{$timestamp}-P7Q8R9",
            ],
            [
                'room_id' => $rooms[2]->id,
                // FIX: Replaced guest details with customer_id
                'customer_id' => $customers['aldren.perez@example.com'],
                'check_in' => Carbon::now()->addDays(15),
                'check_out' => Carbon::now()->addDays(17),
                'number_of_guests' => 1,
                'special_request' => 'Quiet room preferred',
                'payment_method' => 'bank_transfer',
                'payment' => 'full',
                'total_price' => 12000,
                'status' => 'confirmed',
                'expires_at' => Carbon::now()->addHours(24),
                'reservation_number' => "RSV-{$timestamp}-S1T2U3",
            ],
            [
                'room_id' => $rooms[3]->id,
                // FIX: Replaced guest details with customer_id
                'customer_id' => $customers['john.reyta@example.com'],
                'check_in' => Carbon::now()->addDays(20),
                'check_out' => Carbon::now()->addDays(22),
                'number_of_guests' => 4,
                'special_request' => 'Birthday celebration setup',
                'payment_method' => 'gcash',
                'payment' => 'full',
                'total_price' => 30000,
                'status' => 'confirmed',
                'expires_at' => Carbon::now()->addHours(24),
                'reservation_number' => "RSV-{$timestamp}-V4W5X6",
            ],
        ];

        foreach ($reservations as $reservationData) {
            // Remove old payment fields
            unset($reservationData['payment']);
            
            // Create reservation
            $reservation = Reservation::create($reservationData);
            
            // Create payment record in payments table
            Payment::create([
                'reservation_id' => $reservation->id,
                'customer_name' => $reservation->customer->firstname . ' ' . $reservation->customer->lastname,
                'contact_number' => $reservation->customer->phone_number,
                'payment_date' => $reservation->created_at,
                'amount_paid' => $reservation->total_price,
                'payment_stage' => 'full',
                'status' => 'verified',
                'payment_method' => $reservation->payment_method,
                'verified_at' => $reservation->created_at,
            ]);
        }
    }
}