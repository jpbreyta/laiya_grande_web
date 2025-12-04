<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Traits\LogsDataAccess;
use App\Traits\EncryptsData;

class Customer extends Model
{
    use HasFactory, LogsDataAccess, EncryptsData;

    // Customer data is READ-ONLY after creation for security and legal compliance
    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'phone_number',
        'data_consent',
        'consent_given_at',
        'last_accessed_at',
        'last_accessed_by',
    ];

    // Fields that can be updated after creation (only internal tracking)
    protected $guarded = [
        'firstname',
        'lastname', 
        'email',
        'phone_number',
    ];

    protected $casts = [
        'data_consent' => 'boolean',
        'consent_given_at' => 'datetime',
        'last_accessed_at' => 'datetime',
    ];

    // Sensitive fields to encrypt (optional - enable if needed)
    // protected $encrypted = ['phone_number'];

    // Hidden fields - never expose in API/JSON responses
    protected $hidden = [
        'last_accessed_at',
        'last_accessed_by',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
    
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function guestStays()
    {
        return $this->hasMany(GuestStay::class);
    }

    /**
     * Get full name
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->firstname} {$this->lastname}";
    }

    /**
     * Scope: Only customers who gave consent
     */
    public function scopeWithConsent($query)
    {
        return $query->where('data_consent', true);
    }

    /**
     * Check if customer data can be accessed
     */
    public function canAccess(): bool
    {
        return $this->data_consent === true;
    }

    /**
     * Update last accessed timestamp (internal tracking only)
     */
    public function recordAccess(): void
    {
        $this->update([
            'last_accessed_at' => now(),
            'last_accessed_by' => Auth::id(),
        ]);
    }

    /**
     * Prevent updating customer personal data after creation
     */
    protected static function boot()
    {
        parent::boot();

        static::updating(function ($model) {
            // Only allow updating internal tracking fields and consent
            $allowedFields = ['data_consent', 'consent_given_at', 'last_accessed_at', 'last_accessed_by', 'updated_at'];
            
            $dirtyFields = array_keys($model->getDirty());
            $restrictedFields = array_diff($dirtyFields, $allowedFields);
            
            if (!empty($restrictedFields)) {
                // Log attempt to modify restricted fields
                DataAccessLog::logAccess(
                    self::class,
                    $model->id,
                    'update_blocked',
                    'Attempted to modify read-only fields: ' . implode(', ', $restrictedFields)
                );
                
                // Prevent the update of restricted fields
                foreach ($restrictedFields as $field) {
                    $model->{$field} = $model->getOriginal($field);
                }
            }
        });
    }
}