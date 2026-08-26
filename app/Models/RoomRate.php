<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RoomRate extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_id',
        'rate_type',
        'name',
        'price',
        'minimum_nights',
        'starts_on',
        'ends_on',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'minimum_nights' => 'integer',
        'starts_on' => 'date',
        'ends_on' => 'date',
        'is_active' => 'boolean',
    ];

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeEffectiveOn(Builder $query, $date): Builder
    {
        return $query
            ->where(function (Builder $query) use ($date): void {
                $query->whereNull('starts_on')->orWhereDate('starts_on', '<=', $date);
            })
            ->where(function (Builder $query) use ($date): void {
                $query->whereNull('ends_on')->orWhereDate('ends_on', '>=', $date);
            });
    }
}
