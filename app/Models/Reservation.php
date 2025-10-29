<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_id',
        'firstname',
        'lastname',
        'email',
        'phone_number',
        'check_in',
        'check_out',
        'number_of_guests',
        'special_request',
        'payment',
        'total_price',
    ];

    // Relationship: Each reservation belongs to a room
    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
