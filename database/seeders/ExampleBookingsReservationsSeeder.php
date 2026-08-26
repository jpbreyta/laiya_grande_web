<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class ExampleBookingsReservationsSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $adminId = DB::table('users')
                ->join('role_user', 'users.id', '=', 'role_user.user_id')
                ->join('roles', 'role_user.role_id', '=', 'roles.id')
                ->where('roles.name', 'admin')
                ->orderBy('users.id')
                ->value('users.id');

            $rooms = DB::table('rooms')
                ->whereNull('deleted_at')
                ->orderBy('id')
                ->limit(4)
                ->get();

            if ($rooms->count() < 4) {
                throw new RuntimeException(
                    'At least four rooms are required. Run RoomSeeder first.'
                );
            }

            $customers = $this->seedCustomers();
            $today = Carbon::today();

            $examples = [
                [
                    'booking_number' => 'DEMO-POS-0001',
                    'customer_email' => 'julience.castillo@example.com',
                    'room_index' => 0,
                    'source' => 'pos',
                    'check_in' => $today->copy()->subDays(10),
                    'check_out' => $today->copy()->subDays(8),
                    'number_of_guests' => 2,
                    'special_request' => 'Late check-in requested.',
                    'status' => 'completed',
                    'payment_method' => 'gcash',
                ],
                [
                    'booking_number' => 'DEMO-POS-0002',
                    'customer_email' => 'jaika.madrid@example.com',
                    'room_index' => 1,
                    'source' => 'pos',
                    'check_in' => $today->copy()->subDays(15),
                    'check_out' => $today->copy()->subDays(12),
                    'number_of_guests' => 3,
                    'special_request' => 'Extra towels requested.',
                    'status' => 'completed',
                    'payment_method' => 'paymaya',
                ],
                [
                    'booking_number' => 'DEMO-ADMIN-0001',
                    'customer_email' => 'aldren.perez@example.com',
                    'room_index' => 2,
                    'source' => 'admin',
                    'check_in' => $today->copy()->subDays(20),
                    'check_out' => $today->copy()->subDays(18),
                    'number_of_guests' => 1,
                    'special_request' => 'Quiet room preferred.',
                    'status' => 'completed',
                    'payment_method' => 'bank_transfer',
                ],
                [
                    'booking_number' => 'DEMO-ADMIN-0002',
                    'customer_email' => 'john.reyta@example.com',
                    'room_index' => 3,
                    'source' => 'admin',
                    'check_in' => $today->copy()->subDays(25),
                    'check_out' => $today->copy()->subDays(22),
                    'number_of_guests' => 4,
                    'special_request' => 'Birthday setup requested.',
                    'status' => 'completed',
                    'payment_method' => 'gcash',
                ],
                [
                    'booking_number' => 'DEMO-ONLINE-0001',
                    'customer_email' => 'julience.castillo@example.com',
                    'room_index' => 0,
                    'source' => 'online',
                    'check_in' => $today->copy()->addDays(5),
                    'check_out' => $today->copy()->addDays(7),
                    'number_of_guests' => 2,
                    'special_request' => 'Late check-in requested.',
                    'status' => 'confirmed',
                    'payment_method' => 'gcash',
                ],
                [
                    'booking_number' => 'DEMO-ONLINE-0002',
                    'customer_email' => 'jaika.madrid@example.com',
                    'room_index' => 1,
                    'source' => 'online',
                    'check_in' => $today->copy()->addDays(10),
                    'check_out' => $today->copy()->addDays(12),
                    'number_of_guests' => 3,
                    'special_request' => 'Extra towels requested.',
                    'status' => 'confirmed',
                    'payment_method' => 'paymaya',
                ],
                [
                    'booking_number' => 'DEMO-ONLINE-0003',
                    'customer_email' => 'aldren.perez@example.com',
                    'room_index' => 2,
                    'source' => 'online',
                    'check_in' => $today->copy()->addDays(15),
                    'check_out' => $today->copy()->addDays(17),
                    'number_of_guests' => 1,
                    'special_request' => 'Quiet room preferred.',
                    'status' => 'confirmed',
                    'payment_method' => 'bank_transfer',
                ],
                [
                    'booking_number' => 'DEMO-ONLINE-0004',
                    'customer_email' => 'john.reyta@example.com',
                    'room_index' => 3,
                    'source' => 'online',
                    'check_in' => $today->copy()->addDays(20),
                    'check_out' => $today->copy()->addDays(22),
                    'number_of_guests' => 4,
                    'special_request' => 'Birthday setup requested.',
                    'status' => 'confirmed',
                    'payment_method' => 'gcash',
                ],
            ];

            foreach ($examples as $example) {
                $room = $rooms[$example['room_index']];
                $roomRate = DB::table('room_rates')
                    ->where('room_id', $room->id)
                    ->where('is_active', true)
                    ->orderBy('id')
                    ->first();

                if ($roomRate === null) {
                    throw new RuntimeException(
                        "No active rate exists for room '{$room->name}'."
                    );
                }

                $numberOfNights = $example['check_in']
                    ->diffInDays($example['check_out']);
                $quotedTotal = (float) $roomRate->price * $numberOfNights;
                $completed = $example['status'] === 'completed';

                DB::table('bookings')->updateOrInsert(
                    ['booking_number' => $example['booking_number']],
                    [
                        'customer_id' => $customers[$example['customer_email']],
                        'room_id' => $room->id,
                        'room_rate_id' => $roomRate->id,
                        'source' => $example['source'],
                        'check_in' => $example['check_in']->toDateString(),
                        'check_out' => $example['check_out']->toDateString(),
                        'number_of_guests' => min(
                            $example['number_of_guests'],
                            (int) $room->capacity
                        ),
                        'special_request' => $example['special_request'],
                        'status' => $example['status'],
                        'quoted_total' => $quotedTotal,
                        'expires_at' => null,
                        'actual_check_in_time' => $completed
                            ? $example['check_in']->copy()->setTime(14, 0)
                            : null,
                        'actual_check_out_time' => $completed
                            ? $example['check_out']->copy()->setTime(11, 0)
                            : null,
                        'created_by' => $adminId,
                        'updated_by' => $adminId,
                        'created_at' => $example['check_in']
                            ->copy()
                            ->subDays(7),
                        'updated_at' => now(),
                        'deleted_at' => null,
                    ]
                );

                $bookingId = DB::table('bookings')
                    ->where('booking_number', $example['booking_number'])
                    ->value('id');

                DB::table('payments')->updateOrInsert(
                    ['reference_id' => 'PAY-' . $example['booking_number']],
                    [
                        'booking_id' => $bookingId,
                        'amount_paid' => $quotedTotal,
                        'payment_stage' => 'full',
                        'status' => 'verified',
                        'payment_method' => $example['payment_method'],
                        'paid_at' => $example['check_in']
                            ->copy()
                            ->subDays(7),
                        'verified_at' => $example['check_in']
                            ->copy()
                            ->subDays(7),
                        'verified_by' => $adminId,
                        'notes' => 'Seeded demonstration payment.',
                        'metadata' => json_encode([
                            'seed_source' => 'example',
                        ], JSON_THROW_ON_ERROR),
                        'created_at' => $example['check_in']
                            ->copy()
                            ->subDays(7),
                        'updated_at' => now(),
                    ]
                );
            }
        });
    }

    private function seedCustomers(): array
    {
        $customerData = [
            [
                'first_name' => 'Julience',
                'last_name' => 'Castillo',
                'email' => 'julience.castillo@example.com',
                'phone_number' => '09123456789',
            ],
            [
                'first_name' => 'Jaika Remina',
                'last_name' => 'Madrid',
                'email' => 'jaika.madrid@example.com',
                'phone_number' => '09123456790',
            ],
            [
                'first_name' => 'Aldren',
                'last_name' => 'Perez',
                'email' => 'aldren.perez@example.com',
                'phone_number' => '09123456791',
            ],
            [
                'first_name' => 'John Paul Bryan',
                'last_name' => 'Reyta',
                'email' => 'john.reyta@example.com',
                'phone_number' => '09123456792',
            ],
        ];

        foreach ($customerData as $customer) {
            DB::table('customers')->updateOrInsert(
                ['email' => $customer['email']],
                [
                    'first_name' => $customer['first_name'],
                    'last_name' => $customer['last_name'],
                    'phone_number' => $customer['phone_number'],
                    'data_consent' => true,
                    'consent_given_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                    'deleted_at' => null,
                ]
            );
        }

        return DB::table('customers')
            ->whereIn('email', array_column($customerData, 'email'))
            ->pluck('id', 'email')
            ->map(static fn ($id): int => (int) $id)
            ->all();
    }
}
