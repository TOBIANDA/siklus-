<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * This is a pivot model for the book_wishlist table
 * Not used directly, but can be referenced for additional logic if needed
 */
class BookWishlist
{
    // Define the relationship in User and Book models instead
    // User: belongsToMany('books', 'book_wishlist')
    // Book: belongsToMany('users', 'book_wishlist')
}
