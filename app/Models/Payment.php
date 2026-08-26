<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'reference_id',
        'amount_paid',
        'payment_stage',
        'status',
        'payment_method',
        'paid_at',
        'payment_date',
        'verified_at',
        'verified_by',
        'notes',
        'metadata',
    ];

    protected $casts = [
        'amount_paid' => 'decimal:2',
        'paid_at' => 'datetime',
        'verified_at' => 'datetime',
        'metadata' => 'array',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class, 'booking_id');
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function scopeVerified(Builder $query): Builder
    {
        return $query->where('status', 'verified');
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'pending');
    }

    public function getPaymentDateAttribute()
    {
        return $this->paid_at;
    }

    public function setPaymentDateAttribute($value): void
    {
        $this->attributes['paid_at'] = $value;
    }

    public function getCustomerNameAttribute(): string
    {
        return $this->booking?->customer?->full_name ?? '';
    }

    public function getContactNumberAttribute(): ?string
    {
        return $this->booking?->customer?->phone_number;
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'verified' => '<span class="inline-flex rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800">Verified</span>',
            'pending' => '<span class="inline-flex rounded-full bg-yellow-100 px-2.5 py-0.5 text-xs font-medium text-yellow-800">Pending</span>',
            'rejected' => '<span class="inline-flex rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800">Rejected</span>',
            'refunded' => '<span class="inline-flex rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800">Refunded</span>',
            default => '<span class="inline-flex rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-800">Unknown</span>',
        };
    }
}
