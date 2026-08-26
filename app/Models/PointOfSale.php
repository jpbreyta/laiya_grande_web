<?php

namespace App\Models;

/**
 * Compatibility alias for the removed point_of_sale table.
 * POS line items now live in pos_transaction_items.
 */
class PointOfSale extends PosTransactionItem
{
    protected $table = 'pos_transaction_items';

    protected $fillable = [
        'pos_transaction_id',
        'transaction_id',
        'catalog_item_id',
        'item_name',
        'item_type',
        'quantity',
        'unit_price',
        'price',
        'discount',
        'line_total',
        'total_amount',
        'metadata',
    ];

    public function getTransactionIdAttribute(): ?int
    {
        return $this->pos_transaction_id;
    }

    public function setTransactionIdAttribute($value): void
    {
        $this->attributes['pos_transaction_id'] = $value;
    }

    public function getPriceAttribute(): ?string
    {
        return $this->unit_price;
    }

    public function setPriceAttribute($value): void
    {
        $this->attributes['unit_price'] = $value;
    }

    public function getTotalAmountAttribute(): ?string
    {
        return $this->line_total;
    }

    public function setTotalAmountAttribute($value): void
    {
        $this->attributes['line_total'] = $value;
    }

    public function getGuestStayAttribute(): ?GuestStay
    {
        return $this->transaction?->guestStay;
    }
}
