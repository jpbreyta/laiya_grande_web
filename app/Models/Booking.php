<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class Booking extends Model
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
        'total_price',
        'status',
        'source',
        'reservation_number',
        'actual_check_in_time',
        'actual_check_out_time',
        'created_by',
        'updated_by',
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

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function paymentRecord()
    {
        return $this->hasOne(Payment::class);
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
    public function getFullNameAttribute()
    {
        return $this->customer 
            ? "{$this->customer->firstname} {$this->customer->lastname}" 
            : 'Walk-in / Unknown';
    }

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

    // Auto-format reservation number
    public static function generateReservationNumber(): string
    {
        $date = Carbon::now()->format('YmdHis');
        $random = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 6));
        return "RSV-{$date}-{$random}";
    }

    // Audit trail relationships
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Boot method to auto-track who created/updated
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (Auth::check()) {
                $model->created_by = Auth::id();
            }
        });

        static::updating(function ($model) {
            if (Auth::check()) {
                $model->updated_by = Auth::id();
            }
        });
    }
}
