<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GuestStaySeeder extends Seeder
{
    public function run(): void
    {
        $staffUserId = DB::table('users')
            ->join('role_user', 'users.id', '=', 'role_user.user_id')
            ->join('roles', 'role_user.role_id', '=', 'roles.id')
            ->whereIn('roles.name', ['admin', 'manager', 'receptionist'])
            ->orderBy('users.id')
            ->value('users.id');

        $bookings = DB::table('bookings')
            ->whereNull('deleted_at')
            ->whereIn('status', ['checked_in', 'completed'])
            ->orderBy('id')
            ->get();

        foreach ($bookings as $booking) {
            $checkedOut = $booking->status === 'completed';

            $checkInTime = $booking->actual_check_in_time
                ?? Carbon::parse($booking->check_in)->setTime(14, 0);

            $checkOutTime = $checkedOut
                ? (
                    $booking->actual_check_out_time
                    ?? Carbon::parse($booking->check_out)->setTime(11, 0)
                )
                : null;

            DB::table('guest_stays')->updateOrInsert(
                ['booking_id' => $booking->id],
                [
                    'status' => $checkedOut ? 'checked_out' : 'checked_in',
                    'check_in_time' => $checkInTime,
                    'checked_in_by' => $staffUserId,
                    'check_out_time' => $checkOutTime,
                    'checked_out_by' => $checkedOut ? $staffUserId : null,
                    'notes' => 'Generated from the current booking status.',
                    'created_at' => $checkInTime,
                    'updated_at' => now(),
                ]
            );
        }
    }
}
