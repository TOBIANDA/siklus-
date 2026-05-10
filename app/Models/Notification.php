<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Notification extends Model
{
    protected $table = 'notifications';

    protected $fillable = [
        'user_id',
        'type',
        'notifiable_type',
        'notifiable_id',
        'data',
        'read_at',
    ];

    protected $casts = [
        'data' => 'json',
        'read_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user who receives the notification
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the notifiable model (BorrowRequest, Message, etc)
     */
    public function notifiable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(): void
    {
        if (!$this->read_at) {
            $this->update(['read_at' => now()]);
        }
    }

    /**
     * Check if notification is read
     */
    public function isRead(): bool
    {
        return $this->read_at !== null;
    }

    /**
     * Get human-readable notification type
     */
    public function getTypeDisplayAttribute(): string
    {
        return match($this->type) {
            'borrow_requested' => 'Permintaan Peminjaman Baru',
            'borrow_approved' => 'Peminjaman Disetujui',
            'borrow_rejected' => 'Peminjaman Ditolak',
            'borrow_returned' => 'Buku Dikembalikan',
            'message' => 'Pesan Baru',
            'review' => 'Review Baru',
            default => ucfirst($this->type),
        };
    }

    /**
     * Get notification icon
     */
    public function getIconAttribute(): string
    {
        return match($this->type) {
            'borrow_requested' => '📬',
            'borrow_approved' => '✅',
            'borrow_rejected' => '❌',
            'borrow_returned' => '📦',
            'message' => '💬',
            'review' => '⭐',
            default => '🔔',
        };
    }
}
