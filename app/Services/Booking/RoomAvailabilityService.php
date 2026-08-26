<?php

namespace App\Services\Booking;

use App\Models\Booking;
use App\Models\Room;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;

class RoomAvailabilityService
{
    /**
     * Statuses that reserve room inventory.
     */
    private const OCCUPYING_STATUSES = ['confirmed', 'checked_in'];

    /**
     * Add an inventory-aware availability condition to a room query.
     */
    public function applyAvailableBetween(
        Builder $query,
        CarbonInterface $checkIn,
        CarbonInterface $checkOut,
        int $requiredUnits = 1,
        ?int $excludeBookingId = null
    ): Builder {
        $bindings = [
            now(),
            $checkOut->toDateString(),
            $checkIn->toDateString(),
        ];

        $excludeSql = '';
        if ($excludeBookingId !== null) {
            $excludeSql = ' AND bookings.id <> ?';
            $bindings[] = $excludeBookingId;
        }

        $bindings[] = max(1, $requiredUnits);

        return $query->whereRaw(
            "rooms.inventory_count >= (
                SELECT COUNT(*)
                FROM bookings
                WHERE bookings.room_id = rooms.id
                  AND bookings.deleted_at IS NULL
                  AND (
                        bookings.status IN ('confirmed', 'checked_in')
                        OR (
                            bookings.status = 'pending'
                            AND (bookings.expires_at IS NULL OR bookings.expires_at > ?)
                        )
                  )
                  AND bookings.check_in < ?
                  AND bookings.check_out > ?
                  {$excludeSql}
            ) + ?",
            $bindings
        );
    }

    /**
     * Return the number of room units still available for the dates.
     */
    public function availableUnits(
        Room $room,
        CarbonInterface $checkIn,
        CarbonInterface $checkOut,
        ?int $excludeBookingId = null
    ): int {
        $occupied = Booking::query()
            ->where('room_id', $room->getKey())
            ->whereNull('deleted_at')
            ->when(
                $excludeBookingId !== null,
                fn (Builder $query) => $query->where('id', '<>', $excludeBookingId)
            )
            ->where(function (Builder $query): void {
                $query->whereIn('status', self::OCCUPYING_STATUSES)
                    ->orWhere(function (Builder $pending): void {
                        $pending->where('status', 'pending')
                            ->where(function (Builder $expiry): void {
                                $expiry->whereNull('expires_at')
                                    ->orWhere('expires_at', '>', now());
                            });
                    });
            })
            ->whereDate('check_in', '<', $checkOut->toDateString())
            ->whereDate('check_out', '>', $checkIn->toDateString())
            ->count();

        return max(0, $room->inventory_count - $occupied);
    }

    /**
     * Check whether the requested number of units can be reserved.
     */
    public function hasUnits(
        Room $room,
        CarbonInterface $checkIn,
        CarbonInterface $checkOut,
        int $requiredUnits = 1,
        ?int $excludeBookingId = null
    ): bool {
        return $this->availableUnits($room, $checkIn, $checkOut, $excludeBookingId)
            >= max(1, $requiredUnits);
    }
}
