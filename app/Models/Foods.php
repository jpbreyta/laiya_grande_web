<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Compatibility model for food records stored in catalog_items.
 */
class Foods extends CatalogItem
{
    protected $table = 'catalog_items';

    protected $fillable = [
        'catalog_category_id',
        'food_category_id',
        'name',
        'description',
        'unit_price',
        'price',
        'pax_capacity',
        'no_of_pax',
        'sku',
        'is_available',
        'metadata',
    ];

    protected static function booted(): void
    {
        parent::booted();

        static::addGlobalScope('food_items', fn ($query) => $query->where('item_type', 'food'));
        static::creating(fn (self $food) => $food->item_type = 'food');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(FoodCategory::class, 'catalog_category_id');
    }

    public function getPriceAttribute(): ?string
    {
        return $this->unit_price;
    }

    public function setPriceAttribute($value): void
    {
        $this->attributes['unit_price'] = $value;
    }

    public function getNoOfPaxAttribute(): ?int
    {
        return $this->pax_capacity;
    }

    public function setNoOfPaxAttribute($value): void
    {
        $this->attributes['pax_capacity'] = $value;
    }

    public function getFoodCategoryIdAttribute(): ?int
    {
        return $this->catalog_category_id;
    }

    public function setFoodCategoryIdAttribute($value): void
    {
        $this->attributes['catalog_category_id'] = $value;
    }
}
