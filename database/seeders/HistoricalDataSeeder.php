<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Booking;
use App\Models\Reservation;
use App\Models\GuestStay;
use App\Models\Room;
use App\Models\Customer;
use Carbon\Carbon;

class HistoricalDataSeeder extends Seeder
{
    /**
     * Run the database seeds - Creates historical data from January 2025 to today
     */
    public function run(): void
    {
        $this->command->info('Starting Historical Data Seeding...');

        // Get all rooms
        $rooms = Room::all();
        if ($rooms->isEmpty()) {
            $this->command->error('No rooms found! Please seed rooms first.');
            return;
        }

        // Sample customer data
        $customerData = [
            ['firstname' => 'Juan', 'lastname' => 'Dela Cruz', 'email' => 'juan.delacruz@example.com', 'phone_number' => '09171234567'],
            ['firstname' => 'Maria', 'lastname' => 'Santos', 'email' => 'maria.santos@example.com', 'phone_number' => '09181234567'],
            ['firstname' => 'Pedro', 'lastname' => 'Reyes', 'email' => 'pedro.reyes@example.com', 'phone_number' => '09191234567'],
            ['firstname' => 'Ana', 'lastname' => 'Garcia', 'email' => 'ana.garcia@example.com', 'phone_number' => '09201234567'],
            ['firstname' => 'Jose', 'lastname' => 'Martinez', 'email' => 'jose.martinez@example.com', 'phone_number' => '09211234567'],
            ['firstname' => 'Carmen', 'lastname' => 'Lopez', 'email' => 'carmen.lopez@example.com', 'phone_number' => '09221234567'],
            ['firstname' => 'Miguel', 'lastname' => 'Fernandez', 'email' => 'miguel.fernandez@example.com', 'phone_number' => '09231234567'],
            ['firstname' => 'Sofia', 'lastname' => 'Gonzalez', 'email' => 'sofia.gonzalez@example.com', 'phone_number' => '09241234567'],
            ['firstname' => 'Luis', 'lastname' => 'Rodriguez', 'email' => 'luis.rodriguez@example.com', 'phone_number' => '09251234567'],
            ['firstname' => 'Isabel', 'lastname' => 'Hernandez', 'email' => 'isabel.hernandez@example.com', 'phone_number' => '09261234567'],
            ['firstname' => 'Carlos', 'lastname' => 'Diaz', 'email' => 'carlos.diaz@example.com', 'phone_number' => '09271234567'],
            ['firstname' => 'Elena', 'lastname' => 'Morales', 'email' => 'elena.morales@example.com', 'phone_number' => '09281234567'],
            ['firstname' => 'Roberto', 'lastname' => 'Jimenez', 'email' => 'roberto.jimenez@example.com', 'phone_number' => '09291234567'],
            ['firstname' => 'Patricia', 'lastname' => 'Ruiz', 'email' => 'patricia.ruiz@example.com', 'phone_number' => '09301234567'],
            ['firstname' => 'Fernando', 'lastname' => 'Torres', 'email' => 'fernando.torres@example.com', 'phone_number' => '09311234567'],
        ];

        // Create or get customers
        $customers = [];
        foreach ($customerData as $data) {
            $customer = Customer::firstOrCreate(
                ['email' => $data['email']],
                $data
            );
            $customers[] = $customer;
        }

        $this->command->info('Created ' . count($customers) . ' customers');

        // Payment methods (for bookings - includes cash)
        $bookingPaymentMethods = ['gcash', 'paymaya', 'bank_transfer', 'cash'];
        // Payment methods (for reservations - no cash)
        $reservationPaymentMethods = ['gcash', 'paymaya', 'bank_transfer'];
        $specialRequests = [
            'Late check-in requested',
            'Extra towels needed',
            'Quiet room preferred',
            'Birthday celebration setup',
            'Anniversary package',
            'Early check-in if possible',
            'High floor preferred',
            'Near elevator',
            null,
            null,
        ];

        // Generate data from January 1, 2025 to today
        $startDate = Carbon::create(2025, 1, 1);
        $endDate = Carbon::today();
        
        $bookingCount = 0;
        $reservationCount = 0;
        $guestStayCount = 0;

        // Generate bookings (3-5 per week on average)
        $currentDate = $startDate->copy();
        while ($currentDate->lte($endDate)) {
            // Random number of bookings per week (3-5)
            $bookingsThisWeek = rand(3, 5);
            
            for ($i = 0; $i < $bookingsThisWeek; $i++) {
                // Random check-in date within this week
                $checkIn = $currentDate->copy()->addDays(rand(0, 6));
                
                // Skip if check-in is in the future
                if ($checkIn->gt($endDate)) {
                    continue;
                }
                
                // Random stay duration (1-4 nights)
                $nights = rand(1, 4);
                $checkOut = $checkIn->copy()->addDays($nights);
                
                // Random customer and room
                $customer = $customers[array_rand($customers)];
                $room = $rooms->random();
                
                // Random number of guests (1-6)
                $numberOfGuests = rand(1, 6);
                
                // Calculate price (room price * nights)
                $totalPrice = $room->price * $nights;
                
                // Determine booking source (70% online, 30% walk-in/POS)
                $source = rand(1, 10) <= 7 ? 'online' : 'pos';
                
                // Create booking
                $booking = Booking::create([
                    'room_id' => $room->id,
                    'customer_id' => $customer->id,
                    'check_in' => $checkIn,
                    'check_out' => $checkOut,
                    'number_of_guests' => $numberOfGuests,
                    'special_request' => $specialRequests[array_rand($specialRequests)],
                    'payment_method' => $bookingPaymentMethods[array_rand($bookingPaymentMethods)],
                    'payment' => 'full',
                    'total_price' => $totalPrice,
                    'status' => 'confirmed',
                    'source' => $source,
                    'reservation_number' => $this->generateReservationNumber('BK'),
                    'actual_check_in_time' => $checkIn->copy()->setTime(14, rand(0, 59)),
                    'actual_check_out_time' => $checkOut->copy()->setTime(11, rand(0, 59)),
                    'created_at' => $checkIn->copy()->subDays(rand(3, 14)),
                ]);
                
                $bookingCount++;
                
                // Create corresponding guest stay (checked out)
                GuestStay::create([
                    'booking_id' => $booking->id,
                    'room_id' => $room->id,
                    'guest_name' => $customer->firstname . ' ' . $customer->lastname,
                    'status' => 'checked-out',
                    'check_in_time' => $booking->actual_check_in_time,
                    'check_out_time' => $booking->actual_check_out_time,
                    'created_at' => $booking->actual_check_in_time,
                    'updated_at' => $booking->actual_check_out_time,
                ]);
                
                $guestStayCount++;
            }
            
            // Move to next week
            $currentDate->addWeek();
        }

        // Generate reservations (2-4 per week on average) - also checked out
        $currentDate = $startDate->copy();
        while ($currentDate->lte($endDate)) {
            // Random number of reservations per week (2-4)
            $reservationsThisWeek = rand(2, 4);
            
            for ($i = 0; $i < $reservationsThisWeek; $i++) {
                // Random check-in date within this week
                $checkIn = $currentDate->copy()->addDays(rand(0, 6));
                
                // Skip if check-in is in the future
                if ($checkIn->gt($endDate)) {
                    continue;
                }
                
                // Random stay duration (1-4 nights)
                $nights = rand(1, 4);
                $checkOut = $checkIn->copy()->addDays($nights);
                
                // Random customer and room
                $customer = $customers[array_rand($customers)];
                $room = $rooms->random();
                
                // Random number of guests (1-6)
                $numberOfGuests = rand(1, 6);
                
                // Calculate price (room price * nights)
                $totalPrice = $room->price * $nights;
                
                // Create reservation
                $reservation = Reservation::create([
                    'room_id' => $room->id,
                    'customer_id' => $customer->id,
                    'check_in' => $checkIn,
                    'check_out' => $checkOut,
                    'number_of_guests' => $numberOfGuests,
                    'special_request' => $specialRequests[array_rand($specialRequests)],
                    'payment_method' => $reservationPaymentMethods[array_rand($reservationPaymentMethods)],
                    'payment' => 'full',
                    'first_payment' => $totalPrice * 0.5,
                    'second_payment' => $totalPrice * 0.5,
                    'total_price' => $totalPrice,
                    'status' => rand(0, 10) > 2 ? 'confirmed' : 'paid', // 80% confirmed, 20% paid
                    'expires_at' => $checkIn->copy()->subDays(1),
                    'reservation_number' => $this->generateReservationNumber('RSV'),
                    'created_at' => $checkIn->copy()->subDays(rand(7, 30)),
                ]);
                
                $reservationCount++;
                
                // Create corresponding guest stay (checked out)
                GuestStay::create([
                    'reservation_id' => $reservation->id,
                    'room_id' => $room->id,
                    'guest_name' => $customer->firstname . ' ' . $customer->lastname,
                    'status' => 'checked-out',
                    'check_in_time' => $checkIn->copy()->setTime(14, rand(0, 59)),
                    'check_out_time' => $checkOut->copy()->setTime(11, rand(0, 59)),
                    'created_at' => $checkIn->copy()->setTime(14, rand(0, 59)),
                    'updated_at' => $checkOut->copy()->setTime(11, rand(0, 59)),
                ]);
                
                $guestStayCount++;
            }
            
            // Move to next week
            $currentDate->addWeek();
        }

        $this->command->info("✓ Created {$bookingCount} bookings");
        $this->command->info("✓ Created {$reservationCount} reservations");
        $this->command->info("✓ Created {$guestStayCount} guest stays (all checked out)");
        $this->command->info('Historical data seeding completed successfully!');
    }

    /**
     * Generate a unique reservation number
     */
    private function generateReservationNumber($prefix = 'RSV'): string
    {
        $date = Carbon::now()->format('YmdHis');
        $random = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 6));
        return "{$prefix}-{$date}-{$random}";
    }
}
