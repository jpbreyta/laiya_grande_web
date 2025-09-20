<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuestBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'guest_name',
        'contact_number',
        'arrival_date',
        'total_guests',
        'room_names',
        'car_plate',
        'balance_mode',
        'key_deposit',
        'eco_ticket',
        'parking',
        'cookwares',
        'videoke',
        'others',
        'total_amount',
        'otp_verified',
        'qr_code',
    ];
}
