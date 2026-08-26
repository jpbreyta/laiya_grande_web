<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class HistoricalDataSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $rooms = $this->loadRooms();

            if ($rooms === []) {
                throw new RuntimeException(
                    'No rooms with active rates were found. Run RoomSeeder first.'
                );
            }

            $customers = $this->seedCustomers();
            $staffUserId = DB::table('users')
                ->join('role_user', 'users.id', '=', 'role_user.user_id')
                ->join('roles', 'role_user.role_id', '=', 'roles.id')
                ->whereIn('roles.name', ['admin', 'manager'])
                ->orderBy('users.id')
                ->value('users.id');

            mt_srand(20250720);

            $startDate = Carbon::create(2025, 1, 1)->startOfDay();
            $endDate = Carbon::today();
            $currentWeek = $startDate->copy();
            while ($currentWeek->lte($endDate)) {
                $bookingsThisWeek = mt_rand(3, 5);

                for ($index = 0; $index < $bookingsThisWeek; $index++) {
                    $checkIn = $currentWeek
                        ->copy()
                        ->addDays(mt_rand(0, 6));
                    $checkOut = $checkIn
                        ->copy()
                        ->addDays(mt_rand(1, 4));

                    if ($checkOut->gt($endDate)) {
                        continue;
                    }

                    $room = $rooms[array_rand($rooms)];
                    $customer = $customers[array_rand($customers)];
                    $guestLimit = max(1, min(6, $room['capacity']));
                    $numberOfGuests = mt_rand(1, $guestLimit);
                    $nights = $checkIn->diffInDays($checkOut);
                    $quotedTotal = $room['price'] * $nights;
                    $bookingNumber = sprintf(
                        'HIST-%s-%02d',
                        $currentWeek->format('Ymd'),
                        $index + 1
                    );
                    $paymentMethod = [
                        'gcash',
                        'paymaya',
                        'bank_transfer',
                        'cash',
                    ][mt_rand(0, 3)];
                    $source = mt_rand(1, 10) <= 7
                        ? 'online'
                        : 'pos';
                    $createdAt = $checkIn
                        ->copy()
                        ->subDays(mt_rand(3, 30));
                    $actualCheckIn = $checkIn
                        ->copy()
                        ->setTime(14, mt_rand(0, 45));
                    $actualCheckOut = $checkOut
                        ->copy()
                        ->setTime(11, mt_rand(0, 30));

                    DB::table('bookings')->updateOrInsert(
                        ['booking_number' => $bookingNumber],
                        [
                            'customer_id' => $customer['id'],
                            'room_id' => $room['id'],
                            'room_rate_id' => $room['room_rate_id'],
                            'source' => $source,
                            'check_in' => $checkIn->toDateString(),
                            'check_out' => $checkOut->toDateString(),
                            'number_of_guests' => $numberOfGuests,
                            'special_request' => $this->specialRequest(),
                            'status' => 'completed',
                            'quoted_total' => $quotedTotal,
                            'expires_at' => null,
                            'actual_check_in_time' => $actualCheckIn,
                            'actual_check_out_time' => $actualCheckOut,
                            'created_by' => $staffUserId,
                            'updated_by' => $staffUserId,
                            'created_at' => $createdAt,
                            'updated_at' => $actualCheckOut,
                            'deleted_at' => null,
                        ]
                    );

                    $bookingId = DB::table('bookings')
                        ->where('booking_number', $bookingNumber)
                        ->value('id');

                    DB::table('payments')->updateOrInsert(
                        ['reference_id' => 'PAY-' . $bookingNumber],
                        [
                            'booking_id' => $bookingId,
                            'amount_paid' => $quotedTotal,
                            'payment_stage' => 'full',
                            'status' => 'verified',
                            'payment_method' => $paymentMethod,
                            'paid_at' => $createdAt,
                            'verified_at' => $createdAt,
                            'verified_by' => $staffUserId,
                            'notes' => 'Seeded historical payment.',
                            'metadata' => json_encode([
                                'seed_source' => 'historical',
                            ], JSON_THROW_ON_ERROR),
                            'created_at' => $createdAt,
                            'updated_at' => $createdAt,
                        ]
                    );

                    DB::table('guest_stays')->updateOrInsert(
                        ['booking_id' => $bookingId],
                        [
                            'status' => 'checked_out',
                            'check_in_time' => $actualCheckIn,
                            'checked_in_by' => $staffUserId,
                            'check_out_time' => $actualCheckOut,
                            'checked_out_by' => $staffUserId,
                            'notes' => 'Seeded historical guest stay.',
                            'created_at' => $actualCheckIn,
                            'updated_at' => $actualCheckOut,
                        ]
                    );

                }

                $currentWeek->addWeek();
            }
        });
    }

    private function loadRooms(): array
    {
        $rooms = DB::table('rooms')
            ->whereNull('deleted_at')
            ->where('status', 'available')
            ->orderBy('id')
            ->get();

        $result = [];

        foreach ($rooms as $room) {
            $roomRate = DB::table('room_rates')
                ->where('room_id', $room->id)
                ->where('is_active', true)
                ->orderBy('id')
                ->first();

            if ($roomRate === null) {
                continue;
            }

            $result[] = [
                'id' => (int) $room->id,
                'room_rate_id' => (int) $roomRate->id,
                'capacity' => (int) $room->capacity,
                'price' => (float) $roomRate->price,
            ];
        }

        return $result;
    }

    private function seedCustomers(): array
    {
        $customerData = [
            ['Juan', 'Dela Cruz', 'juan.delacruz@example.com', '09171234567'],
            ['Maria', 'Santos', 'maria.santos@example.com', '09181234567'],
            ['Pedro', 'Reyes', 'pedro.reyes@example.com', '09191234567'],
            ['Ana', 'Garcia', 'ana.garcia@example.com', '09201234567'],
            ['Jose', 'Martinez', 'jose.martinez@example.com', '09211234567'],
            ['Carmen', 'Lopez', 'carmen.lopez@example.com', '09221234567'],
            ['Miguel', 'Fernandez', 'miguel.fernandez@example.com', '09231234567'],
            ['Sofia', 'Gonzalez', 'sofia.gonzalez@example.com', '09241234567'],
            ['Luis', 'Rodriguez', 'luis.rodriguez@example.com', '09251234567'],
            ['Isabel', 'Hernandez', 'isabel.hernandez@example.com', '09261234567'],
            ['Carlos', 'Diaz', 'carlos.diaz@example.com', '09271234567'],
            ['Elena', 'Morales', 'elena.morales@example.com', '09281234567'],
            ['Roberto', 'Jimenez', 'roberto.jimenez@example.com', '09291234567'],
            ['Patricia', 'Ruiz', 'patricia.ruiz@example.com', '09301234567'],
            ['Fernando', 'Torres', 'fernando.torres@example.com', '09311234567'],
        ];

        foreach ($customerData as [$firstName, $lastName, $email, $phone]) {
            DB::table('customers')->updateOrInsert(
                ['email' => $email],
                [
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'phone_number' => $phone,
                    'data_consent' => true,
                    'consent_given_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                    'deleted_at' => null,
                ]
            );
        }

        return DB::table('customers')
            ->whereIn(
                'email',
                array_column($customerData, 2)
            )
            ->get()
            ->map(static fn ($customer): array => [
                'id' => (int) $customer->id,
                'email' => $customer->email,
            ])
            ->all();
    }

    private function specialRequest(): ?string
    {
        $requests = [
            'Late check-in requested.',
            'Extra towels requested.',
            'Quiet room preferred.',
            'Birthday setup requested.',
            'Anniversary package requested.',
            'Early check-in requested if available.',
            null,
            null,
        ];

        return $requests[mt_rand(0, count($requests) - 1)];
    }
}
