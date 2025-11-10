<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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
        'reservation_number',
        'actual_check_in_time',
        'actual_check_out_time',
    ];

    protected $casts = [
        'check_in' => 'datetime',
        'check_out' => 'datetime',
        'actual_check_in_time' => 'datetime',
        'actual_check_out_time' => 'datetime',
        'payment' => 'string',
        'total_price' => 'float',
    ];

    // Relationships
    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function paymentRecord()
    {
        return $this->hasOne(Payment::class);
    }

    // Accessors
    public function getFullNameAttribute()
    {
        return "{$this->firstname} {$this->lastname}";
    }

    // Auto-format reservation number (optional utility)
    public static function generateReservationNumber(): string
    {
        $date = Carbon::now()->format('YmdHis');
        $random = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 6));
        return "RSV-{$date}-{$random}";
    }
}
