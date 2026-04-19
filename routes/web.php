<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookCatalogController;
use App\Http\Controllers\BorrowRequestController;
use App\Http\Controllers\MessagesController;
use App\Models\Book;

// --- 1. HOME ---
Route::get('/', function () {
    $popularBooks     = Book::orderByDesc('borrow_count')->limit(8)->get();
    $recommendedBooks = Book::orderByDesc('rating')->limit(8)->get();
    return view('pages.home', compact('popularBooks', 'recommendedBooks'));
})->name('home');

// --- 2. BOOK DETAIL ---
Route::get('/books/{book}', function (Book $book) {
    return view('pages.book', compact('book'));
})->name('book.show');

// --- 3. BORROW REQUESTS ---
Route::post('/books/{book}/borrow',              [BorrowRequestController::class, 'store'])->name('borrow.request');
Route::patch('/borrow-requests/{borrowRequest}/approve', [BorrowRequestController::class, 'approve'])->name('borrow.approve');
Route::patch('/borrow-requests/{borrowRequest}/reject',  [BorrowRequestController::class, 'reject'])->name('borrow.reject');
Route::patch('/borrow-requests/{borrowRequest}/returned',[BorrowRequestController::class, 'markReturned'])->name('borrow.returned');
Route::delete('/borrow-requests/{borrowRequest}',        [BorrowRequestController::class, 'destroy'])->name('borrow.request.destroy');

// --- 4. MESSAGES (Inbox = borrow requests from others) ---
Route::get('/messages',         [MessagesController::class, 'index'])->name('messages');
Route::get('/messages/{email}', [MessagesController::class, 'show'])->name('messages.show');

// --- 5. BORROW (my borrowed books list) ---
Route::get('/borrow', function () {
    $items = [
        [
            'title'        => 'The Little Prince',
            'author'       => 'Antoine De Saint Exupery',
            'borrow_date'  => '4/4/2026',
            'return_date'  => '4/5/2026',
            'cover'        => 'cover_little_prince.png',
            'lender_name'  => 'Adidharma',
            'lender_avatar'=> 'avatar_adidharma.jpg',
            'lender_id'    => 1,
            'status'       => 'onread',
            'statusLabel'  => 'On Read',
        ],
    ];
    return view('pages.borrow', compact('items'));
})->name('borrow');

// --- 6. LENT (my books catalog) CRUD ---
Route::get('/lent',           [BookCatalogController::class, 'index'])->name('lent');
Route::post('/lent',          [BookCatalogController::class, 'store'])->name('lent.store');
Route::put('/lent/{book}',    [BookCatalogController::class, 'update'])->name('lent.update');
Route::delete('/lent/{book}', [BookCatalogController::class, 'destroy'])->name('lent.destroy');

// --- 7. PROFILE ---
Route::get('/profile', fn() => view('pages.profile'))->name('profile');
Route::put('/profile/update', fn() => back()->with('success', 'Profil berhasil diperbarui!'))->name('profile.update');
Route::post('/profile/photo', fn() => back()->with('success', 'Foto profil berhasil diganti!'))->name('profile.photo');

// --- 8. SEARCH ---
Route::get('/search', function () {
    $query   = request('q', '');
    $results = collect();

    if ($query) {
        $results = Book::where('title', 'LIKE', "%{$query}%")
            ->orWhere('author', 'LIKE', "%{$query}%")
            ->orWhere('category', 'LIKE', "%{$query}%")
            ->orderByDesc('borrow_count')
            ->get();
    }

    return view('pages.search', compact('query', 'results'));
})->name('search');

Route::get('/borrow/add', fn() => 'Form Tambah Buku')->name('borrow.add');