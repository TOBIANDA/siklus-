<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Review extends Model
{
    protected $table = 'reviews';

    protected $fillable = [
        'reviewer_id',
        'reviewable_type',
        'reviewable_id',
        'rating',
        'comment',
        'type',
    ];

    protected $casts = [
        'rating' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user who wrote the review
     */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    /**
     * Get the reviewable model (Book or User)
     */
    public function reviewable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get star rating display
     */
    public function getRatingDisplayAttribute(): string
    {
        return str_repeat('⭐', $this->rating);
    }

    /**
     * Get human-readable type
     */
    public function getTypeDisplayAttribute(): string
    {
        return match($this->type) {
            'book_review' => 'Book Review',
            'lender_review' => 'Lender Review',
            default => 'Review',
        };
    }
}
