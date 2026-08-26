<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'auth_user_id',
        'name',
        'email',
        'password',
        'is_active',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'last_login_at' => 'datetime',
        ];
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class)->withTimestamps();
    }

    public function createdBookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'created_by');
    }

    public function updatedBookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'updated_by');
    }

    public function checkedInStays(): HasMany
    {
        return $this->hasMany(GuestStay::class, 'checked_in_by');
    }

    public function checkedOutStays(): HasMany
    {
        return $this->hasMany(GuestStay::class, 'checked_out_by');
    }

    public function verifiedPayments(): HasMany
    {
        return $this->hasMany(Payment::class, 'verified_by');
    }

    public function posTransactions(): HasMany
    {
        return $this->hasMany(PosTransaction::class, 'created_by');
    }

    public function moderatedRatings(): HasMany
    {
        return $this->hasMany(RoomRating::class, 'moderated_by');
    }

    public function repliedMessages(): HasMany
    {
        return $this->hasMany(ContactMessage::class, 'replied_by');
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    public function dataAccessLogs(): HasMany
    {
        return $this->hasMany(DataAccessLog::class);
    }

    public function hasRole(string $role): bool
    {
        return $this->roles()->where('roles.name', $role)->exists();
    }

    public function hasAnyRole(array $roles): bool
    {
        return $this->roles()->whereIn('roles.name', $roles)->exists();
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    public function isStaff(): bool
    {
        return $this->hasAnyRole(['admin', 'manager', 'receptionist', 'staff']);
    }

    /**
     * Compatibility accessor for old code that expected users.role.
     */
    public function getRoleAttribute(): ?string
    {
        return $this->relationLoaded('roles')
            ? $this->roles->first()?->name
            : $this->roles()->value('name');
    }
}
