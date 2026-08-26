<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class ContactMessage extends Model
{
    use HasFactory;

    protected $with = ['contactSubject'];

    protected $fillable = [
        'customer_id',
        'name',
        'email',
        'phone',
        'contact_subject_id',
        'message',
        'status',
        'read_at',
        'reply_subject',
        'reply_content',
        'replied_at',
        'replied_by',
        'archived_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
        'replied_at' => 'datetime',
        'archived_at' => 'datetime',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function contactSubject(): BelongsTo
    {
        return $this->belongsTo(ContactSubject::class);
    }

    public function repliedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'replied_by');
    }

    public function scopeUnread(Builder $query): Builder
    {
        return $query->where('status', 'unread');
    }

    public function scopeRead(Builder $query): Builder
    {
        return $query->where('status', 'read');
    }

    public function scopeReplied(Builder $query): Builder
    {
        return $query->where('status', 'replied');
    }

    public function scopeArchived(Builder $query): Builder
    {
        return $query->whereNotNull('archived_at');
    }

    public function scopeNotArchived(Builder $query): Builder
    {
        return $query->whereNull('archived_at');
    }

    public function getSubjectAttribute(): ?string
    {
        return $this->contactSubject?->classification;
    }

    public function markAsRead(): bool
    {
        if ($this->status !== 'unread') {
            return true;
        }

        return $this->update([
            'status' => 'read',
            'read_at' => now(),
        ]);
    }

    public function markAsReplied(string $subject, string $content, ?int $userId = null): bool
    {
        return $this->update([
            'status' => 'replied',
            'read_at' => $this->read_at ?? now(),
            'reply_subject' => $subject,
            'reply_content' => $content,
            'replied_at' => now(),
            'replied_by' => $userId ?? Auth::id(),
        ]);
    }

    public function archive(): bool
    {
        return $this->update([
            'status' => 'archived',
            'archived_at' => now(),
        ]);
    }

    public function unarchive(): bool
    {
        $status = $this->replied_at !== null
            ? 'replied'
            : ($this->read_at !== null ? 'read' : 'unread');

        return $this->update([
            'status' => $status,
            'archived_at' => null,
        ]);
    }
}
