<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'short_description',
        'full_description',
        'price',
        'capacity',
        'availability',
        'amenities',
        'images',
        'image',
        'rate_name',
        'status',
        'has_aircon',
        'has_private_cr',
        'has_kitchen',
        'has_free_parking',
        'no_entrance_fee',
        'no_corkage_fee',
    ];

    protected $casts = [
        'amenities' => 'array',
        'images' => 'array',
        'has_aircon' => 'boolean',
        'has_private_cr' => 'boolean',
        'has_kitchen' => 'boolean',
        'has_free_parking' => 'boolean',
        'no_entrance_fee' => 'boolean',
        'no_corkage_fee' => 'boolean',
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
