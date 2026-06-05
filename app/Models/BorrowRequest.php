<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BorrowRequest extends Model
{
    protected $table = 'borrow_requests';

    protected $fillable = [
        'book_id',
        'user_id',
        'borrower_name',
        'full_name',
        'phone',
        'email',
        'message',
        'borrow_date',
        'return_date',
        'status',
        'read_by_owner',
        'dismissed_by_owner',
    ];

    protected $casts = [
        'borrow_date'         => 'date',
        'return_date'         => 'date',
        'read_by_owner'       => 'boolean',
        'dismissed_by_owner'  => 'boolean',
    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get messages related to this borrow request
     */
    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    /**
     * Get notifications related to this borrow request
     */
    public function notifications()
    {
        return $this->morphMany(Notification::class, 'notifiable');
    }

    /** Human-readable request status */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending'  => 'Menunggu Konfirmasi',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            default    => '-',
        };
    }

    /** CSS class for the request status badge */
    public function getStatusClassAttribute(): string
    {
        return match($this->status) {
            'pending'  => 's-appeal',
            'approved' => 's-onread',
            'rejected' => 's-finish',
            default    => '',
        };
    }

    /** Short description for chat sidebar preview */
    public function getPreviewAttribute(): string
    {
        $name = $this->user ? $this->user->name : 'Seseorang';
        return "{$name} ingin meminjam '{$this->book?->title}'";
    }
}
