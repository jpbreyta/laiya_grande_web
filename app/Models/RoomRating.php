<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomRating extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_id',
        'guest_email',
        'guest_name',
        'rating',
        'comment',
        'ip_address',
        'is_verified',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
