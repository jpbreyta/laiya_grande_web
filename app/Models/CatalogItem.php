<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class CatalogItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'catalog_category_id',
        'item_type',
        'sku',
        'name',
        'description',
        'pricing_details',
        'unit_price',
        'pax_capacity',
        'min_participants',
        'duration_minutes',
        'tracks_inventory',
        'stock_quantity',
        'is_available',
        'metadata',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'pax_capacity' => 'integer',
        'min_participants' => 'integer',
        'duration_minutes' => 'integer',
        'tracks_inventory' => 'boolean',
        'stock_quantity' => 'integer',
        'is_available' => 'boolean',
        'metadata' => 'array',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(CatalogCategory::class, 'catalog_category_id');
    }

    public function transactionItems(): HasMany
    {
        return $this->hasMany(PosTransactionItem::class);
    }

    public function scopeAvailable(Builder $query): Builder
    {
        return $query->where('is_available', true);
    }

    public function scopeOfType(Builder $query, string $type): Builder
    {
        return $query->where('item_type', $type);
    }
}
