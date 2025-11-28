<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    protected $with = ['contactSubject'];

    protected $fillable = [
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
        'archived_at'
    ];

    protected $casts = [
        'read_at' => 'datetime',
        'replied_at' => 'datetime',
        'archived_at' => 'datetime',
    ];

    // Relationship with ContactSubject
    public function contactSubject()
    {
        return $this->belongsTo(ContactSubject::class);
    }

    // Accessor for subject (for backward compatibility with views)
    public function getSubjectAttribute()
    {
        return $this->contactSubject?->classification;
    }

    // Scope for unread messages
    public function scopeUnread($query)
    {
        return $query->where('status', 'unread');
    }

    // Scope for read messages
    public function scopeRead($query)
    {
        return $query->where('status', 'read');
    }

    // Scope for replied messages
    public function scopeReplied($query)
    {
        return $query->where('status', 'replied');
    }

    // Mark as read
    public function markAsRead()
    {
        $this->update([
            'status' => 'read',
            'read_at' => now()
        ]);
    }

    // Mark as replied
    public function markAsReplied()
    {
        $this->update(['status' => 'replied']);
    }

    // Scope for archived messages
    public function scopeArchived($query)
    {
        return $query->whereNotNull('archived_at');
    }

    // Scope for non-archived messages
    public function scopeNotArchived($query)
    {
        return $query->whereNull('archived_at');
    }

    // Archive message
    public function archive()
    {
        $this->update(['archived_at' => now()]);
    }

    // Unarchive message
    public function unarchive()
    {
        $this->update(['archived_at' => null]);
    }
}
