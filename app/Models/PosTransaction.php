<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class PosTransaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'receipt_number',
        'guest_stay_id',
        'customer_id',
        'created_by',
        'subtotal',
        'discount',
        'tax',
        'total',
        'items_count',
        'payment_status',
        'status',
        'transaction_date',
        'notes',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax' => 'decimal:2',
        'total' => 'decimal:2',
        'items_count' => 'integer',
        'transaction_date' => 'datetime',
    ];

    public function guestStay(): BelongsTo
    {
        return $this->belongsTo(GuestStay::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(PosTransactionItem::class);
    }

    public function posItems(): HasMany
    {
        return $this->hasMany(PointOfSale::class, 'pos_transaction_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(PosTransactionPayment::class);
    }

    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where('status', 'completed');
    }

    public function scopePaid(Builder $query): Builder
    {
        return $query->where('payment_status', 'paid');
    }
}
