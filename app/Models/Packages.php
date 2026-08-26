<?php

namespace App\Models;

/**
 * Compatibility model for tour packages stored in catalog_items.
 */
class Packages extends CatalogItem
{
    protected $table = 'catalog_items';

    protected $fillable = [
        'catalog_category_id',
        'sku',
        'name',
        'title',
        'description',
        'pricing_details',
        'unit_price',
        'price',
        'duration_minutes',
        'duration',
        'image_path',
        'is_available',
        'metadata',
    ];

    protected static function booted(): void
    {
        parent::booted();

        static::addGlobalScope('tour_packages', fn ($query) => $query->where('item_type', 'tour_package'));
        static::creating(fn (self $item) => $item->item_type = 'tour_package');
    }

    public function getTitleAttribute(): string
    {
        return (string) $this->name;
    }

    public function setTitleAttribute($value): void
    {
        $this->attributes['name'] = $value;
    }

    public function getPriceAttribute(): ?string
    {
        return $this->unit_price;
    }

    public function setPriceAttribute($value): void
    {
        $this->attributes['unit_price'] = $value;
    }

    public function getDurationAttribute(): ?int
    {
        return $this->duration_minutes;
    }

    public function setDurationAttribute($value): void
    {
        $this->attributes['duration_minutes'] = $value;
    }

    public function getImagePathAttribute(): ?string
    {
        return data_get($this->metadata, 'image_path');
    }

    public function setImagePathAttribute($value): void
    {
        $metadata = $this->metadata ?? [];
        $metadata['image_path'] = $value;
        $this->attributes['metadata'] = json_encode($metadata);
    }
}
