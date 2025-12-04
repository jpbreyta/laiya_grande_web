<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Customer extends Model

{
    use HasFactory;

    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'phone_number',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
    
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}