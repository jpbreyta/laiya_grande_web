<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class GuestStay extends Model
{
    use HasFactory;

    protected $table = 'guest_stays'; // your table name

    protected $fillable = [
        'booking_id',
        'reservation_id',
        'room_id',
        'customer_id',
        'status',
        'check_in_time',
        'check_out_time',
        'checked_in_by',
        'checked_out_by',
    ];

    // Relationships
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function checkedInBy()
    {
        return $this->belongsTo(User::class, 'checked_in_by');
    }

    public function checkedOutBy()
    {
        return $this->belongsTo(User::class, 'checked_out_by');
    }

    // Accessor for guest name (fetched from customer via booking)
    public function getGuestNameAttribute()
    {
        return $this->customer 
            ? "{$this->customer->firstname} {$this->customer->lastname}" 
            : ($this->booking && $this->booking->customer 
                ? "{$this->booking->customer->firstname} {$this->booking->customer->lastname}"
                : 'Unknown Guest');
    }

    // Boot method to auto-track who checked in/out
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (Auth::check() && $model->status === 'checked-in') {
                $model->checked_in_by = Auth::id();
            }
        });

        static::updating(function ($model) {
            if (Auth::check() && $model->isDirty('status') && $model->status === 'checked-out') {
                $model->checked_out_by = Auth::id();
            }
        });
    }

    protected $casts = [
        'check_in_time' => 'datetime',
        'check_out_time' => 'datetime',
    ];
}
