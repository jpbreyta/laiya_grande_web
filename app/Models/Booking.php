<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
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
        'payment_method',
        'payment',
        'total_price',
        'status',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
