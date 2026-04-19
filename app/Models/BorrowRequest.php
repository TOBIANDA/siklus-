<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BorrowRequest extends Model
{
    protected $table = 'borrow_requests';

    protected $fillable = [
        'book_id',
        'borrower_name',
        'full_name',
        'phone',
        'email',
        'message',
        'borrow_date',
        'return_date',
        'status',
        'read_by_owner',
    ];

    protected $casts = [
        'borrow_date'    => 'date',
        'return_date'    => 'date',
        'read_by_owner'  => 'boolean',
    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
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
        $name = $this->borrower_name ?? $this->full_name;
        return "{$name} ingin meminjam '{$this->book?->title}'";
    }
}
