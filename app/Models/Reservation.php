<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_id', 'firstname', 'lastname', 'email', 'phone_number',
        'check_in', 'check_out', 'number_of_guests', 'special_request',
        'payment_method', 'payment', 'total_price', 'status', 'expires_at'
    ];

    protected $dates = ['expires_at', 'check_in', 'check_out'];

    public function room() {
        return $this->belongsTo(Room::class);
    }

    public function isExpired() {
        return $this->expires_at && Carbon::now()->greaterThan($this->expires_at);
    }
}
