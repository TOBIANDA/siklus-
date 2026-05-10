<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookCatalogController extends Controller
{
    /**
     * Show the lent (my books) page with statistics and books organized by status.
     */
    public function index()
    {
        $books = Book::with('borrowRequests')->where('user_id', Auth::id())->latest()->get();
        
        // Organize books by status
        $onLoanBooks = [];
        $pendingBooks = [];
        $finishedBooks = [];
        
        foreach ($books as $book) {
            // Count pending requests for this book
            $pendingCount = $book->borrowRequests->where('status', 'pending')->count();
            
            $itemData = [
                'id'           => $book->id,
                'title'        => $book->title,
                'author'       => $book->author,
                'cover'        => $book->cover,
                'category'     => $book->category,
                'location'     => $book->location,
                'description'  => $book->description,
                'rating'       => $book->rating,
                'book_status'  => $book->book_status,
                'pending_count' => $pendingCount,
            ];
            
            // Get active borrow request if exists
            $activeBorrow = $book->borrowRequests()->where('status', 'approved')->first();
            if ($activeBorrow) {
                $itemData['borrow_date']  = $activeBorrow->borrow_date->format('m/d/Y');
                $itemData['return_date']  = $activeBorrow->return_date ? $activeBorrow->return_date->format('m/d/Y') : 'TBD';
                $itemData['borrower_name'] = $activeBorrow->borrower_name;
                $itemData['borrower_email'] = $activeBorrow->email;
            }
            
            if ($book->book_status === 'on_loan') {
                $onLoanBooks[] = $itemData;
            } elseif ($pendingCount > 0) {
                $pendingBooks[] = $itemData;
            } else {
                $finishedBooks[] = $itemData;
            }
        }
        
        // Combine all items maintaining order
        $items = array_merge($onLoanBooks, $pendingBooks, $finishedBooks);
        
        // Calculate statistics
        $stats = [
            'books_loaned' => $books->count(),
            'on_loan'      => count($onLoanBooks),
            'pending'      => count($pendingBooks),
        ];
        
        return view('pages.lent', compact('items', 'stats', 'books'));
    }

    /**
     * Store a new book in the catalog.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'author'      => 'required|string|max:255',
            'category'    => 'required|string|max:100',
            'description' => 'nullable|string',
            'location'    => 'nullable|string|max:255',
            'cover'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('cover')) {
            $file     = $request->file('cover');
            $filename = 'book_' . time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images'), $filename);
            $validated['cover'] = $filename;
        }

        // Set user_id to current authenticated user
        $validated['user_id']      = Auth::id();
        $validated['owner_name']   = Auth::user()->name;
        $validated['owner_avatar'] = Auth::user()->avatar ?? 'avatar_user.png';

        Book::create($validated);

        return redirect()->route('lent')->with('success', 'Buku berhasil ditambahkan ke katalog!');
    }

    /**
     * Update a book in the catalog.
     */
    public function update(Request $request, Book $book)
    {
        // Authorization check - ensure user owns this book
        if ($book->user_id !== Auth::id()) {
            return redirect()->route('lent')->with('error', 'Unauthorized');
        }

        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'author'      => 'required|string|max:255',
            'category'    => 'required|string|max:100',
            'description' => 'nullable|string',
            'location'    => 'nullable|string|max:255',
            'cover'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('cover')) {
            $file     = $request->file('cover');
            $filename = 'book_' . time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images'), $filename);
            $validated['cover'] = $filename;
        }

        $book->update($validated);

        return redirect()->route('lent')->with('success', 'Buku berhasil diperbarui!');
    }

    /**
     * Delete a book from the catalog.
     */
    public function destroy(Book $book)
    {
        // Authorization check - ensure user owns this book
        if ($book->user_id !== Auth::id()) {
            return redirect()->route('lent')->with('error', 'Unauthorized');
        }

        $book->delete();
        return redirect()->route('lent')->with('success', 'Buku berhasil dihapus dari katalog!');
    }
}
