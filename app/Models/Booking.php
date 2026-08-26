<?php

namespace App\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class Booking extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'booking_number',
        'reservation_number',
        'customer_id',
        'room_id',
        'room_rate_id',
        'source',
        'check_in',
        'check_out',
        'number_of_guests',
        'special_request',
        'status',
        'quoted_total',
        'total_price',
        'expires_at',
        'actual_check_in_time',
        'actual_check_out_time',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'check_in' => 'date',
        'check_out' => 'date',
        'quoted_total' => 'decimal:2',
        'expires_at' => 'datetime',
        'actual_check_in_time' => 'datetime',
        'actual_check_out_time' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $booking): void {
            $booking->booking_number ??= static::generateBookingNumber();
            $booking->created_by ??= Auth::id();
        });

        static::updating(function (self $booking): void {
            if (Auth::check()) {
                $booking->updated_by = Auth::id();
            }
        });
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function roomRate(): BelongsTo
    {
        return $this->belongsTo(RoomRate::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function paymentRecord(): HasOne
    {
        return $this->hasOne(Payment::class)->latestOfMany();
    }

    public function guestStay(): HasOne
    {
        return $this->hasOne(GuestStay::class);
    }

    public function rating(): HasOne
    {
        return $this->hasOne(RoomRating::class);
    }

    public function otpChallenges(): HasMany
    {
        return $this->hasMany(Otp::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->whereIn('status', ['confirmed', 'checked_in']);
    }

    public function scopeUpcoming(Builder $query): Builder
    {
        return $query->whereDate('check_in', '>=', today());
    }

    public function getFullNameAttribute(): string
    {
        return $this->customer?->full_name ?? 'Unknown Guest';
    }

    public function getFirstnameAttribute(): string
    {
        return $this->customer?->first_name ?? '';
    }

    public function getLastnameAttribute(): string
    {
        return $this->customer?->last_name ?? '';
    }

    public function getEmailAttribute(): string
    {
        return $this->customer?->email ?? '';
    }

    public function getPhoneNumberAttribute(): string
    {
        return $this->customer?->phone_number ?? '';
    }

    public function getReservationNumberAttribute(): string
    {
        return (string) $this->booking_number;
    }

    public function setReservationNumberAttribute($value): void
    {
        $this->attributes['booking_number'] = $value;
    }

    public function getTotalPriceAttribute(): string
    {
        return (string) $this->quoted_total;
    }

    public function setTotalPriceAttribute($value): void
    {
        $this->attributes['quoted_total'] = $value;
    }

    public function getPaymentMethodAttribute(): ?string
    {
        return $this->payments()->latest('paid_at')->value('payment_method');
    }

    public function isExpired(): bool
    {
        return $this->expires_at instanceof CarbonInterface
            && $this->expires_at->isPast()
            && $this->status === 'pending';
    }

    public static function generateBookingNumber(string $prefix = 'BKG'): string
    {
        do {
            $number = $prefix . '-' . now()->format('YmdHis') . '-' . Str::upper(Str::random(8));
        } while (static::withTrashed()->where('booking_number', $number)->exists());

        return $number;
    }

    public static function generateReservationNumber(): string
    {
        return static::generateBookingNumber('RSV');
    }
}
