<?php

namespace App\Models;

/**
 * Compatibility model for rental records stored in catalog_items.
 */
class RentalItem extends CatalogItem
{
    protected $table = 'catalog_items';

    protected $fillable = [
        'catalog_category_id',
        'sku',
        'name',
        'description',
        'unit_price',
        'price',
        'tracks_inventory',
        'stock_quantity',
        'is_available',
        'metadata',
    ];

    protected static function booted(): void
    {
        parent::booted();

        static::addGlobalScope('rental_items', fn ($query) => $query->where('item_type', 'rental'));
        static::creating(function (self $item): void {
            $item->item_type = 'rental';
            $item->tracks_inventory = true;
        });
    }

    public function getPriceAttribute(): ?string
    {
        return $this->unit_price;
    }

    public function setPriceAttribute($value): void
    {
        $this->attributes['unit_price'] = $value;
    }

    public function getCategoryAttribute(): ?string
    {
        return $this->relationLoaded('category')
            ? $this->getRelation('category')?->name
            : $this->category()->value('name');
    }
}
