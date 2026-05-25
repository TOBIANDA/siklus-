<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookCatalogController extends Controller
{
    /**
     * Show the Upload Book page (create form).
     */
    public function create()
    {
        return view('pages.book_create');
    }

    /**
     * Show the lent (my books) page with statistics and books organized by status.
     */
    public function index()
    {
        $books = Book::with('borrowRequests')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();
        
        // Organize books by status with efficient processing
        $onLoanBooks = [];
        $pendingBooks = [];
        $finishedBooks = [];
        
        foreach ($books as $book) {
            // Count pending requests from already-loaded relations
            $pendingCount = $book->borrowRequests
                ->where('status', 'pending')
                ->count();
            
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
            
            // Get active borrow request from already-loaded relations
            $activeBorrow = $book->borrowRequests
                ->where('status', 'approved')
                ->first();
            
            if ($activeBorrow) {
                $itemData['borrow_date']  = $activeBorrow->borrow_date->format('m/d/Y');
                $itemData['return_date']  = $activeBorrow->return_date ? $activeBorrow->return_date->format('m/d/Y') : 'TBD';
                $itemData['borrower_name'] = $activeBorrow->borrower_name;
                $itemData['borrower_email'] = $activeBorrow->email;
            }
            
            // Categorize by status
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

        $validated['user_id']      = Auth::id();
        $validated['owner_name']   = Auth::user()->name;
        $validated['owner_avatar'] = Auth::user()->avatar ?? 'avatar_user.png';

        $book = Book::create($validated);

        // --- AJAX / JSON response ---
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Buku berhasil ditambahkan ke katalog!',
                'book'    => [
                    'id'          => $book->id,
                    'title'       => $book->title,
                    'author'      => $book->author,
                    'category'    => $book->category,
                    'location'    => $book->location,
                    'cover_url'   => $book->cover_url,
                    'book_status' => $book->book_status,
                    'edit_url'    => '#modal-edit-' . $book->id,
                    'delete_url'  => '#modal-del-' . $book->id,
                ],
            ]);
        }

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
     * Live search endpoint — returns JSON (for AJAX fetch calls).
     * GET /api/books/search?q=...&category=...&sort=...
     */
    public function liveSearch(Request $request)
    {
        $query    = trim($request->get('q', ''));
        $category = $request->get('category', '');
        $sort     = $request->get('sort', 'popular'); // popular | newest | rating

        $books = Book::query();

        if ($query !== '') {
            $books->where(function ($q) use ($query) {
                $q->where('title',    'LIKE', "%{$query}%")
                  ->orWhere('author',   'LIKE', "%{$query}%")
                  ->orWhere('category', 'LIKE', "%{$query}%");
            });
        }

        if ($category !== '') {
            $books->where('category', $category);
        }

        match ($sort) {
            'newest'  => $books->latest(),
            'rating'  => $books->orderByDesc('rating'),
            default   => $books->orderByDesc('borrow_count'),
        };

        $results = $books->limit(30)->get()->map(function (Book $book) {
            return [
                'id'          => $book->id,
                'title'       => $book->title,
                'author'      => $book->author,
                'category'    => $book->category,
                'cover_url'   => $book->cover_url,
                'rating'      => number_format($book->rating, 1),
                'borrow_count'=> $book->borrow_count,
                'book_status' => $book->book_status,
                'status_label'=> $book->book_status_label,
                'url'         => route('book.show', $book->id),
            ];
        });

        // Get distinct categories for the filter dropdown
        $categories = Book::select('category')->distinct()->orderBy('category')->pluck('category');

        return response()->json([
            'success'    => true,
            'query'      => $query,
            'total'      => $results->count(),
            'results'    => $results,
            'categories' => $categories,
        ]);
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
