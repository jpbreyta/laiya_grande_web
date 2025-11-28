<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PosTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'receipt_number',
        'guest_stay_id',
        'subtotal',
        'discount',
        'total',
        'items_count',
        'transaction_date',
        'payment_method',
        'status'
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
        'transaction_date' => 'datetime',
    ];

    // Relationship to guest stay
    public function guestStay()
    {
        return $this->belongsTo(GuestStay::class, 'guest_stay_id');
    }

    // Get all POS items for this transaction
    public function posItems()
    {
        return $this->hasMany(PointOfSale::class, 'transaction_id');
    }
}
