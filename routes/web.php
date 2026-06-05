<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookCatalogController;
use App\Http\Controllers\BorrowRequestController;
use App\Http\Controllers\MessagesController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\FriendController;
use App\Models\Book;

// --- 1. HOME ---
Route::get('/', function () {
    if (!auth()->check()) {
        return redirect()->route('login');
    }
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
    Route::patch('/borrow-requests/{borrowRequest}/dismiss', [BorrowRequestController::class, 'dismiss'])->name('borrow.dismiss');
    Route::patch('/borrow-requests/{borrowRequest}/status', [BorrowRequestController::class, 'updateStatus'])->name('borrow.updateStatus');

    // --- 4. MESSAGES (Inbox = borrow requests from others) ---
    Route::get('/messages',         [MessagesController::class, 'index'])->name('messages');
    Route::get('/messages/{userId}', [MessagesController::class, 'show'])->name('messages.show');
    
    // --- 4.1 MESSAGES API (For Real-time WebSocket Chat) ---
    Route::post('/api/messages/send', [MessagesController::class, 'store'])->name('messages.store');
    Route::get('/api/messages/{recipientId}/history', [MessagesController::class, 'getHistory'])->name('messages.history');
    Route::patch('/api/messages/{messageId}/read', [MessagesController::class, 'markAsRead'])->name('messages.read');

    // --- 5. BORROW (my borrowed books list) ---
    Route::get('/borrow', [BorrowRequestController::class, 'showBorrowed'])->name('borrow');
    Route::get('/borrow/add', fn() => redirect()->route('lent')->with('open_add', true))->name('borrow.add');

    // --- 6. LENT (my books catalog) CRUD ---
    Route::get('/lent/create',    [BookCatalogController::class, 'create'])->name('lent.create');
    Route::get('/lent',           [BookCatalogController::class, 'index'])->name('lent');
    Route::post('/lent',          [BookCatalogController::class, 'store'])->name('lent.store');
    Route::put('/lent/{book}',    [BookCatalogController::class, 'update'])->name('lent.update');
    Route::patch('/lent/{book}/status', [BookCatalogController::class, 'updateStatus'])->name('lent.updateStatus');
    Route::delete('/lent/{book}', [BookCatalogController::class, 'destroy'])->name('lent.destroy');

    // --- 7. PROFILE ---
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/photo', [ProfileController::class, 'uploadPhoto'])->name('profile.photo');

    // --- 8. SETTINGS ---
    Route::get('/settings',                  [SettingsController::class, 'show'])->name('settings');
    Route::post('/settings/language',        [SettingsController::class, 'saveLanguage'])->name('settings.language');
    Route::post('/settings/appearance',      [SettingsController::class, 'saveAppearance'])->name('settings.appearance');
    Route::post('/settings/notifications',   [SettingsController::class, 'saveNotifications'])->name('settings.notifications');
    Route::post('/settings/privacy',         [SettingsController::class, 'savePrivacy'])->name('settings.privacy');
    Route::post('/settings/password',        [SettingsController::class, 'changePassword'])->name('settings.password');

    // --- 9. FRIENDS ---
    Route::get('/friends',                   [FriendController::class, 'index'])->name('friends');
    Route::post('/friends',                  [FriendController::class, 'store'])->name('friends.store');
    Route::get('/friends/search',            [FriendController::class, 'search'])->name('friends.search');
    Route::patch('/friends/{friend}/accept', [FriendController::class, 'accept'])->name('friends.accept');
    Route::delete('/friends/{friend}/reject',[FriendController::class, 'reject'])->name('friends.reject');
    Route::delete('/friends/{friend}',       [FriendController::class, 'destroy'])->name('friends.destroy');
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

// --- 9. LIVE SEARCH API (returns JSON, used by AJAX fetch) ---
Route::get('/api/books/search', [BookCatalogController::class, 'liveSearch'])->name('books.live-search');