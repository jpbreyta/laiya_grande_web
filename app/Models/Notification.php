<?php

namespace App\Models;

use App\Models\Builders\NotificationBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'customer_id',
        'is_broadcast',
        'type',
        'title',
        'message',
        'data',
        'read_at',
        'read',
    ];

    protected $casts = [
        'is_broadcast' => 'boolean',
        'data' => 'array',
        'read_at' => 'datetime',
    ];

    public function newEloquentBuilder($query): NotificationBuilder
    {
        return new NotificationBuilder($query);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function deliveries(): HasMany
    {
        return $this->hasMany(NotificationDelivery::class);
    }

    public function scopeUnread(Builder $query): Builder
    {
        return $query->whereNull('read_at');
    }

    public function scopeRead(Builder $query): Builder
    {
        return $query->whereNotNull('read_at');
    }

    public function scopeForUser(Builder $query, int $userId): Builder
    {
        return $query->where(function (Builder $query) use ($userId): void {
            $query->where('user_id', $userId)->orWhere('is_broadcast', true);
        });
    }

    public function scopeForCustomer(Builder $query, int $customerId): Builder
    {
        return $query->where(function (Builder $query) use ($customerId): void {
            $query->where('customer_id', $customerId)->orWhere('is_broadcast', true);
        });
    }

    public function markAsRead(): bool
    {
        if ($this->read_at !== null) {
            return true;
        }

        return $this->update(['read_at' => now()]);
    }

    public function markAsUnread(): bool
    {
        return $this->update(['read_at' => null]);
    }

    public function getReadAttribute(): bool
    {
        return $this->read_at !== null;
    }

    public function setReadAttribute($value): void
    {
        $this->attributes['read_at'] = filter_var($value, FILTER_VALIDATE_BOOL) ? now() : null;
    }
}
