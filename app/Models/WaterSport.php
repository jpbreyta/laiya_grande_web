<?php

namespace App\Models;

/**
 * Compatibility model for water-sport records stored in catalog_items.
 */
class WaterSport extends CatalogItem
{
    protected $table = 'catalog_items';

    protected $fillable = [
        'catalog_category_id',
        'sku',
        'name',
        'description',
        'pricing_details',
        'price_details',
        'unit_price',
        'min_participants',
        'duration_minutes',
        'is_available',
        'metadata',
    ];

    protected static function booted(): void
    {
        parent::booted();

        static::addGlobalScope('water_sports', fn ($query) => $query->where('item_type', 'water_sport'));
        static::creating(fn (self $item) => $item->item_type = 'water_sport');
    }

    public function getPriceDetailsAttribute(): ?string
    {
        return $this->pricing_details;
    }

    public function setPriceDetailsAttribute($value): void
    {
        $this->attributes['pricing_details'] = $value;
    }
}
