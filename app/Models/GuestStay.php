<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuestStay extends Model
{
    use HasFactory;

    protected $table = 'guest_stays'; // your table name

    protected $fillable = [
        'booking_id',
        'reservation_id',
        'room_id',
        'guest_name',
        'status',
        'check_in_time',
        'check_out_time',
    ];

    // Relationships
    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'reservation_id');
    }

    protected $casts = [
        'check_in_time' => 'datetime',
        'check_out_time' => 'datetime',
    ];
}
