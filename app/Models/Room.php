<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class Room extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'short_description',
        'full_description',
        'capacity',
        'inventory_count',
        'status',
    ];

    protected $casts = [
        'capacity' => 'integer',
        'inventory_count' => 'integer',
    ];

    public function rates(): HasMany
    {
        return $this->hasMany(RoomRate::class);
    }

    public function activeRates(): HasMany
    {
        return $this->rates()->where('is_active', true);
    }

    public function roomImages(): HasMany
    {
        return $this->hasMany(RoomImage::class)->orderBy('sort_order');
    }

    public function amenities(): BelongsToMany
    {
        return $this->belongsToMany(Amenity::class)->withTimestamps();
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class, 'room_id');
    }

    public function ratings(): HasManyThrough
    {
        return $this->hasManyThrough(
            RoomRating::class,
            Booking::class,
            'room_id',
            'booking_id',
            'id',
            'id'
        );
    }

    public function scopeAvailable(Builder $query): Builder
    {
        return $query->where('status', 'available')->where('inventory_count', '>', 0);
    }

    public function averageRating(): float
    {
        return (float) ($this->ratings()->where('is_verified', true)->avg('rating') ?? 0);
    }

    public function totalRatings(): int
    {
        return $this->ratings()->where('is_verified', true)->count();
    }

    public function getPriceAttribute(): ?string
    {
        $rate = $this->relationLoaded('rates')
            ? $this->rates->where('is_active', true)->sortBy('price')->first()
            : $this->activeRates()->orderBy('price')->first();

        return $rate?->price;
    }

    public function getImageAttribute(): ?string
    {
        $image = $this->relationLoaded('roomImages')
            ? ($this->roomImages->firstWhere('is_primary', true) ?? $this->roomImages->first())
            : $this->roomImages()->orderByDesc('is_primary')->orderBy('sort_order')->first();

        return $image?->path;
    }

    public function getImagesAttribute(): array
    {
        $images = $this->relationLoaded('roomImages')
            ? $this->roomImages
            : $this->roomImages()->get();

        return $images->pluck('path')->all();
    }
}
