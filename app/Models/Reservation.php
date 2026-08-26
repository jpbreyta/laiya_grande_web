<?php

namespace App\Models;

/**
 * Compatibility alias. The reservations table was normalized into bookings.
 */
class Reservation extends Booking
{
    protected $table = 'bookings';

    protected static function booted(): void
    {
        parent::booted();

        static::addGlobalScope('online_reservations', fn ($query) => $query->where('source', 'online'));
        static::creating(function (self $reservation): void {
            $reservation->source = 'online';
            $reservation->booking_number ??= static::generateBookingNumber('RSV');
        });
    }
}
