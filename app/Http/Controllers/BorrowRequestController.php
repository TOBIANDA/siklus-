<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BorrowRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BorrowRequestController extends Controller
{
    /**
     * Display borrowed books page with statistics.
     * Shows books the current user has borrowed from others.
     */
    public function showBorrowed()
    {
        // Fetch all borrow requests for the current user
        $borrowRequests = BorrowRequest::with('book')->where('email', Auth::user()->email)->get();
        
        // Organize requests by status for the view
        $onReadBooks = [];
        $pendingBooks = [];
        $finishedBooks = [];
        
        foreach ($borrowRequests as $request) {
            $itemData = [
                'id'              => $request->id,
                'title'           => $request->book->title,
                'author'          => $request->book->author,
                'cover'           => $request->book->cover,
                'borrow_date'     => $request->borrow_date->format('m/d/Y'),
                'return_date'     => $request->return_date ? $request->return_date->format('m/d/Y') : 'TBD',
                'lender_name'     => $request->book->owner_name,
                'lender_avatar'   => $request->book->owner_avatar,
                'lender_id'       => $request->book->id, // Using book id as identifier
                'status'          => $request->status,
            ];
            
            if ($request->status === 'approved') {
                $itemData['status'] = 'onread';
                $itemData['statusLabel'] = 'On Read';
                $onReadBooks[] = $itemData;
            } elseif ($request->status === 'pending') {
                $itemData['status'] = 'appeal';
                $itemData['statusLabel'] = 'Appealed';
                $pendingBooks[] = $itemData;
            } else { // rejected or returned
                $itemData['status'] = 'finish';
                $itemData['statusLabel'] = 'Finished';
                $finishedBooks[] = $itemData;
            }
        }
        
        // Combine all items maintaining order
        $items = array_merge($onReadBooks, $pendingBooks, $finishedBooks);
        
        // Calculate statistics
        $stats = [
            'books_read'   => count($finishedBooks),
            'on_read'      => count($onReadBooks),
            'pending'      => count($pendingBooks),
            'trust_score'  => 4.6, // TODO: Calculate based on lender ratings
        ];
        
        return view('pages.borrow', compact('items', 'stats'));
    }

    /**
     * Store a new borrow request from the book detail page modal.
     */
    public function store(Request $request, Book $book)
    {
        $validated = $request->validate([
            'message'       => 'nullable|string|max:500',
            'borrow_date'   => 'required|date',
            'return_date'   => 'required|date|after_or_equal:borrow_date',
        ]);

        $validated['book_id']       = $book->id;
        $validated['borrower_name'] = Auth::user()->name;
        $validated['full_name']     = Auth::user()->name;
        $validated['email']         = Auth::user()->email;
        $validated['phone']         = '-'; // Default since Users table lacks phone number
        $validated['status']        = 'pending';

        BorrowRequest::create($validated);

        // Increment borrow_count for popularity tracking
        $book->increment('borrow_count');

        return redirect()->route('book.show', $book->id)
            ->with('success', 'Permintaan peminjaman berhasil dikirim! Tunggu konfirmasi dari pemilik buku.');
    }

    /**
     * Approve a borrow request (from messages/inbox).
     * Also updates the book status to "on_loan".
     */
    public function approve(BorrowRequest $borrowRequest)
    {
        $borrowRequest->update(['status' => 'approved', 'read_by_owner' => true]);

        // Update book status
        $borrowRequest->book->update(['book_status' => 'on_loan']);

        // Reject all other pending requests for this book
        BorrowRequest::where('book_id', $borrowRequest->book_id)
            ->where('id', '!=', $borrowRequest->id)
            ->where('status', 'pending')
            ->update(['status' => 'rejected']);

        return redirect()->route('messages.show', urlencode($borrowRequest->email))
            ->with('success', 'Permintaan peminjaman disetujui!');
    }

    /**
     * Reject a borrow request.
     */
    public function reject(BorrowRequest $borrowRequest)
    {
        $borrowRequest->update(['status' => 'rejected', 'read_by_owner' => true]);

        return redirect()->route('messages.show', urlencode($borrowRequest->email))
            ->with('success', 'Permintaan peminjaman ditolak.');
    }

    /**
     * Mark a book as returned (from lent page or messages).
     */
    public function markReturned(BorrowRequest $borrowRequest)
    {
        $borrowRequest->update(['status' => 'returned']);
        $borrowRequest->book->update(['book_status' => 'returned']);

        return redirect()->back()->with('success', 'Buku berhasil ditandai sudah dikembalikan!');
    }

    /**
     * Cancel / delete a borrow request.
     */
    public function destroy(BorrowRequest $borrowRequest)
    {
        $borrowRequest->delete();
        return redirect()->back()->with('success', 'Permintaan peminjaman dibatalkan!');
    }
}
