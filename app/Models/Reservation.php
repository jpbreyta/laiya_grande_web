<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 
use Carbon\Carbon;

class Reservation extends Model
{
    use HasFactory, SoftDeletes; 

    protected $fillable = [
        'room_id', 
        'customer_id', 
        'check_in', 
        'check_out', 
        'number_of_guests', 
        'special_request',
        'payment_method', 
        'payment', 
        'first_payment', 
        'second_payment', 
        'total_price', 
        'status', 
        'expires_at',
        'reservation_number'
    ];

    protected $casts = [
        'check_in' => 'date',
        'check_out' => 'date',
        'expires_at' => 'datetime',
        'total_price' => 'float',
    ];

    // Relationships
    public function room() {
        return $this->belongsTo(Room::class);
    }

    public function customer() {
        return $this->belongsTo(Customer::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function guestStay()
    {
        return $this->hasOne(GuestStay::class);
    }

    // Accessors
    public function getFirstnameAttribute()
    {
        return $this->customer ? $this->customer->firstname : '';
    }

    public function getLastnameAttribute()
    {
        return $this->customer ? $this->customer->lastname : '';
    }

    public function getEmailAttribute()
    {
        return $this->customer ? $this->customer->email : '';
    }

    public function getPhoneNumberAttribute()
    {
        return $this->customer ? $this->customer->phone_number : '';
    }

    public function isExpired() {
        return $this->expires_at && Carbon::now()->greaterThan($this->expires_at);
    }
}