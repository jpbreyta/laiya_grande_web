<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        $recipientUserId = DB::table('users')
            ->join('role_user', 'users.id', '=', 'role_user.user_id')
            ->join('roles', 'role_user.role_id', '=', 'roles.id')
            ->where('roles.name', 'admin')
            ->orderBy('users.id')
            ->value('users.id');

        if ($recipientUserId === null) {
            return;
        }

        $bookings = DB::table('bookings')
            ->join('customers', 'bookings.customer_id', '=', 'customers.id')
            ->whereNull('bookings.deleted_at')
            ->whereIn('bookings.status', ['pending', 'confirmed'])
            ->select([
                'bookings.id',
                'bookings.booking_number',
                'bookings.status',
                'bookings.check_in',
                'bookings.check_out',
                'bookings.number_of_guests',
                'customers.first_name',
                'customers.last_name',
                'customers.email',
                'customers.phone_number',
            ])
            ->orderBy('bookings.id')
            ->get();

        foreach ($bookings as $booking) {
            $title = "Booking {$booking->booking_number}";
            $customerName = trim(
                "{$booking->first_name} {$booking->last_name}"
            );

            DB::table('notifications')->updateOrInsert(
                [
                    'user_id' => $recipientUserId,
                    'type' => 'booking',
                    'title' => $title,
                ],
                [
                    'customer_id' => null,
                    'is_broadcast' => false,
                    'message' => "{$customerName} has a {$booking->status} booking for {$booking->number_of_guests} guest(s).",
                    'data' => json_encode([
                        'booking_id' => $booking->id,
                        'booking_number' => $booking->booking_number,
                        'customer_name' => $customerName,
                        'email' => $booking->email,
                        'phone' => $booking->phone_number,
                        'check_in' => $booking->check_in,
                        'check_out' => $booking->check_out,
                        'guests' => $booking->number_of_guests,
                        'status' => $booking->status,
                    ], JSON_THROW_ON_ERROR),
                    'read_at' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
