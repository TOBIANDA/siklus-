<?php
// Quick debug script
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\BorrowRequest;
use App\Models\Book;
use App\Models\User;

echo "=== USERS ===\n";
User::all()->each(fn($u) => print("  [{$u->id}] {$u->name} <{$u->email}>\n"));

echo "\n=== BOOKS ===\n";
Book::all()->each(fn($b) => print("  [{$b->id}] \"{$b->title}\" owner=user_id:{$b->user_id}\n"));

echo "\n=== BORROW REQUESTS ===\n";
BorrowRequest::with('book')->get()->each(function($r) {
    $book = $r->book ? "\"{$r->book->title}\" (book.user_id={$r->book->user_id})" : 'BOOK NULL';
    print("  [{$r->id}] {$r->borrower_name} -> {$book} [{$r->status}]\n");
});
