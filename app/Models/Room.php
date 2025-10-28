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
    'bed_type',
    'bathrooms',
    'size',
    'amenities',
    'images',
    'rate_name',
    'image', 
];

    protected $casts = [
        'amenities' => 'array',
        'images' => 'array',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
