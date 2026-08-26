<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class GuestStay extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'status',
        'check_in_time',
        'checked_in_by',
        'check_out_time',
        'checked_out_by',
        'notes',
    ];

    protected $casts = [
        'check_in_time' => 'datetime',
        'check_out_time' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $stay): void {
            if (Auth::check() && $stay->status === 'checked_in') {
                $stay->checked_in_by ??= Auth::id();
            }
        });

        static::updating(function (self $stay): void {
            if (Auth::check() && $stay->isDirty('status') && $stay->status === 'checked_out') {
                $stay->checked_out_by ??= Auth::id();
                $stay->check_out_time ??= now();
            }
        });
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function checkedInBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'checked_in_by');
    }

    public function checkedOutBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'checked_out_by');
    }

    public function posTransactions(): HasMany
    {
        return $this->hasMany(PosTransaction::class);
    }

    public function scopeCheckedIn(Builder $query): Builder
    {
        return $query->where('status', 'checked_in');
    }

    public function scopeCheckedOut(Builder $query): Builder
    {
        return $query->where('status', 'checked_out');
    }

    public function getCustomerAttribute(): ?Customer
    {
        return $this->booking?->customer;
    }

    public function getRoomAttribute(): ?Room
    {
        return $this->booking?->room;
    }

    public function getGuestNameAttribute(): string
    {
        return $this->booking?->customer?->full_name ?? 'Unknown Guest';
    }
}
