<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Friend extends Model
{
    protected $fillable = [
        'user_id',
        'friend_id',
        'status',
    ];

    /**
     * The user who sent the friend request
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * The user who received the friend request
     */
    public function friend()
    {
        return $this->belongsTo(User::class, 'friend_id');
    }
}
