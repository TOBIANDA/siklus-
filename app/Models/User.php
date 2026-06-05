<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'province',
        'city',
        'bio',
        'occupation',
        'level',
        'avatar',
        'books_listed',
        'exchanges',
        'rating',
        // Settings preferences
        'language_preference',
        'theme_preference',
        'text_size',
        'notif_borrow',
        'notif_message',
        'notif_return',
        'notif_updates',
        'public_profile',
        'show_location',
    ];

    /**
     * Resolve the full URL for the user's avatar.
     * Supports: storage/profile/* uploads, public/images/* seeded files, and fallback.
     */
    public function getAvatarUrlAttribute(): string
    {
        if (!$this->avatar) {
            return asset('images/avatar_user.png');
        }
        // Uploaded via form → stored in storage/app/public/profile/
        if (\Storage::disk('public')->exists('profile/' . $this->avatar)) {
            return asset('storage/profile/' . $this->avatar);
        }
        // Seeded avatar in public/images/
        return asset('images/' . $this->avatar);
    }

    public function books()
    {
        return $this->hasMany(Book::class);
    }

    public function borrowRequests()
    {
        return $this->hasMany(BorrowRequest::class);
    }

    /**
     * Get reviews written by this user
     */
    public function reviews()
    {
        return $this->hasMany(Review::class, 'reviewer_id');
    }

    /**
     * Get reviews about this user (as a lender)
     */
    public function lenderReviews()
    {
        return $this->morphMany(Review::class, 'reviewable')->where('type', 'lender_review');
    }

    /**
     * Get messages sent by this user
     */
    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    /**
     * Get messages received by this user
     */
    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'recipient_id');
    }

    /**
     * Get notifications for this user
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Get unread notifications count (borrow requests belum dibaca + notifikasi sistem)
     */
    public function getUnreadNotificationsCountAttribute(): int
    {
        // Hitung borrow request yang belum dibaca dan belum di-dismiss oleh pemilik buku
        $unreadBorrowRequests = \App\Models\BorrowRequest::whereHas('book', function ($q) {
                $q->where('user_id', $this->id);
            })
            ->where('read_by_owner', false)
            ->where('dismissed_by_owner', false)
            ->count();

        // Tambah notifikasi sistem jika ada
        $systemNotifs = 0;
        try {
            $systemNotifs = $this->notifications()->whereNull('read_at')->count();
        } catch (\Exception $e) {
            // Tabel notifications mungkin belum ada
        }

        return $unreadBorrowRequests + $systemNotifs;
    }

    /**
     * Friend requests sent by this user
     */
    public function sentFriendRequests()
    {
        return $this->hasMany(Friend::class, 'user_id');
    }

    /**
     * Friend requests received by this user
     */
    public function receivedFriendRequests()
    {
        return $this->hasMany(Friend::class, 'friend_id');
    }

    /**
     * Get all accepted friends (both directions)
     */
    public function acceptedFriends()
    {
        // Friends where this user sent the request and it was accepted
        $sentAccepted = Friend::where('user_id', $this->id)
            ->where('status', 'accepted')
            ->pluck('friend_id');

        // Friends where this user received the request and it was accepted
        $receivedAccepted = Friend::where('friend_id', $this->id)
            ->where('status', 'accepted')
            ->pluck('user_id');

        $friendIds = $sentAccepted->merge($receivedAccepted)->unique();

        return User::whereIn('id', $friendIds)->get();
    }

    /**
     * Get pending friend requests count received by this user
     */
    public function getPendingFriendRequestsCountAttribute(): int
    {
        return Friend::where('friend_id', $this->id)
            ->where('status', 'pending')
            ->count();
    }

    /**
     * Get books in user's wishlist
     */
    public function wishlist()
    {
        return $this->belongsToMany(Book::class, 'book_wishlist')->withTimestamps();
    }

    /**
     * Check if book is in user's wishlist
     */
    public function hasInWishlist(Book $book): bool
    {
        return $this->wishlist()->where('book_id', $book->id)->exists();
    }

    /**
     * Add book to wishlist
     */
    public function addToWishlist(Book $book): void
    {
        if (!$this->hasInWishlist($book)) {
            $this->wishlist()->attach($book->id);
        }
    }

    /**
     * Remove book from wishlist
     */
    public function removeFromWishlist(Book $book): void
    {
        $this->wishlist()->detach($book->id);
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at'  => 'datetime',
            'password'          => 'hashed',
            'notif_borrow'      => 'boolean',
            'notif_message'     => 'boolean',
            'notif_return'      => 'boolean',
            'notif_updates'     => 'boolean',
            'public_profile'    => 'boolean',
            'show_location'     => 'boolean',
        ];
    }
}
