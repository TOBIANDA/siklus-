<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BorrowRequest;
use Illuminate\Http\Request;

class BorrowRequestController extends Controller
{
    /**
     * Store a new borrow request from the book detail page modal.
     */
    public function store(Request $request, Book $book)
    {
        $validated = $request->validate([
            'full_name'     => 'required|string|max:255',
            'phone'         => 'required|string|max:50',
            'email'         => 'required|email|max:255',
            'message'       => 'nullable|string|max:500',
            'borrow_date'   => 'required|date',
            'return_date'   => 'required|date|after_or_equal:borrow_date',
        ]);

        $validated['book_id']       = $book->id;
        $validated['borrower_name'] = $validated['full_name'];
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

        return redirect()->route('messages.show', $borrowRequest->email)
            ->with('success', 'Permintaan peminjaman disetujui!');
    }

    /**
     * Reject a borrow request.
     */
    public function reject(BorrowRequest $borrowRequest)
    {
        $borrowRequest->update(['status' => 'rejected', 'read_by_owner' => true]);

        return redirect()->route('messages.show', $borrowRequest->email)
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
