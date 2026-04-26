<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookCatalogController;
use App\Http\Controllers\BorrowRequestController;
use App\Http\Controllers\MessagesController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AuthController;
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

// --- AUTHENTICATION ---
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
});
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// --- AUTH PROTECTED ROUTES ---
Route::middleware('auth')->group(function () {
    // --- 3. BORROW REQUESTS ---
    Route::post('/books/{book}/borrow',              [BorrowRequestController::class, 'store'])->name('borrow.request');
    Route::patch('/borrow-requests/{borrowRequest}/approve', [BorrowRequestController::class, 'approve'])->name('borrow.approve');
    Route::patch('/borrow-requests/{borrowRequest}/reject',  [BorrowRequestController::class, 'reject'])->name('borrow.reject');
    Route::patch('/borrow-requests/{borrowRequest}/returned',[BorrowRequestController::class, 'markReturned'])->name('borrow.returned');
    Route::delete('/borrow-requests/{borrowRequest}',        [BorrowRequestController::class, 'destroy'])->name('borrow.request.destroy');

    // --- 4. MESSAGES (Inbox = borrow requests from others) ---
    Route::get('/messages',         [MessagesController::class, 'index'])->name('messages');
    Route::get('/messages/{userId}', [MessagesController::class, 'show'])->name('messages.show');

    // --- 5. BORROW (my borrowed books list) ---
    Route::get('/borrow', [BorrowRequestController::class, 'showBorrowed'])->name('borrow');
    Route::get('/borrow/add', fn() => 'Form Tambah Buku')->name('borrow.add');

    // --- 6. LENT (my books catalog) CRUD ---
    Route::get('/lent',           [BookCatalogController::class, 'index'])->name('lent');
    Route::post('/lent',          [BookCatalogController::class, 'store'])->name('lent.store');
    Route::put('/lent/{book}',    [BookCatalogController::class, 'update'])->name('lent.update');
    Route::delete('/lent/{book}', [BookCatalogController::class, 'destroy'])->name('lent.destroy');

    // --- 7. PROFILE ---
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/photo', [ProfileController::class, 'uploadPhoto'])->name('profile.photo');

    // --- 8. SETTINGS ---
    Route::get('/settings', function () {
        return view('pages.settings');
    })->name('settings');
    Route::post('/settings/language', function () {
        session(['locale' => request('language', 'id')]);
        return back()->with('success', 'Bahasa berhasil diubah.');
    })->name('settings.language');
    Route::post('/settings/notifications', function () {
        return back()->with('success', 'Preferensi notifikasi disimpan.');
    })->name('settings.notifications');
    Route::post('/settings/privacy', function () {
        return back()->with('success', 'Pengaturan privasi diperbarui.');
    })->name('settings.privacy');
});

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