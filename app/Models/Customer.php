<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'auth_user_id',
        'first_name',
        'last_name',
        'firstname',
        'lastname',
        'email',
        'phone_number',
        'data_consent',
        'consent_given_at',
    ];

    protected $casts = [
        'data_consent' => 'boolean',
        'consent_given_at' => 'datetime',
    ];

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class, 'customer_id');
    }

    public function guestStays(): HasManyThrough
    {
        return $this->hasManyThrough(
            GuestStay::class,
            Booking::class,
            'customer_id',
            'booking_id',
            'id',
            'id'
        );
    }

    public function ratings(): HasManyThrough
    {
        return $this->hasManyThrough(
            RoomRating::class,
            Booking::class,
            'customer_id',
            'booking_id',
            'id',
            'id'
        );
    }

    public function otpChallenges(): HasMany
    {
        return $this->hasMany(Otp::class);
    }

    public function posTransactions(): HasMany
    {
        return $this->hasMany(PosTransaction::class);
    }

    public function contactMessages(): HasMany
    {
        return $this->hasMany(ContactMessage::class);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    public function dataAccessLogs(): HasMany
    {
        return $this->hasMany(DataAccessLog::class);
    }

    public function scopeWithConsent(Builder $query): Builder
    {
        return $query->where('data_consent', true);
    }

    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

    public function getFirstnameAttribute(): string
    {
        return (string) $this->first_name;
    }

    public function setFirstnameAttribute(?string $value): void
    {
        $this->attributes['first_name'] = $value;
    }

    public function getLastnameAttribute(): string
    {
        return (string) $this->last_name;
    }

    public function setLastnameAttribute(?string $value): void
    {
        $this->attributes['last_name'] = $value;
    }

    public function recordAccess(string $action = 'view', ?string $reason = null): void
    {
        DataAccessLog::logAccess(
            static::class,
            $this->getKey(),
            $action,
            $reason,
            $this->getKey()
        );
    }
}
