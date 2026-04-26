<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Book extends Model
{
    use HasFactory;

    protected $table = 'books';

    protected $fillable = [
        'title',
        'author',
        'cover',
        'category',
        'description',
        'user_id',
        'location',
        'rating',
        'borrow_count',
        'book_status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function borrowRequests()
    {
        return $this->hasMany(BorrowRequest::class);
    }

    public function activeBorrowRequest()
    {
        return $this->hasOne(BorrowRequest::class)->where('status', 'approved')->latest();
    }

    public function getOwnerNameAttribute(): string
    {
        return $this->user ? $this->user->name : 'Anonymous';
    }

    public function getOwnerAvatarAttribute(): string
    {
        return $this->user && $this->user->avatar ? asset('images/' . $this->user->avatar) : asset('images/avatar_user.png');
    }

    /** Full URL to the cover image. */
    public function getCoverUrlAttribute(): string
    {
        if ($this->cover && str_starts_with($this->cover, 'http')) {
            return $this->cover;
        }
        return $this->cover ? asset('images/' . $this->cover) : asset('images/icon_closed_book.png');
    }

    /** Human-readable book status label */
    public function getBookStatusLabelAttribute(): string
    {
        return match($this->book_status) {
            'available' => 'Tersedia',
            'on_loan'   => 'Sedang Dipinjam',
            'returned'  => 'Sudah Dikembalikan',
            default     => 'Tersedia',
        };
    }

    /** CSS class for book status badge */
    public function getBookStatusClassAttribute(): string
    {
        return match($this->book_status) {
            'available' => 'status-available',
            'on_loan'   => 'status-on-loan',
            'returned'  => 'status-returned',
            default     => 'status-available',
        };
    }
}
