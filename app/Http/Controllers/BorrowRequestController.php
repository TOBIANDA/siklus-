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
            $book = $request->book;
            $itemData = [
                'id'              => $request->id,
                'title'           => $book->title,
                'author'          => $book->author,
                'cover'           => $book->cover,
                'borrow_date'     => $request->borrow_date->format('m/d/Y'),
                'return_date'     => $request->return_date ? $request->return_date->format('m/d/Y') : 'TBD',
                'lender_name'     => $book->owner_name,
                'lender_avatar'   => $book->user?->avatar ?? 'avatar_user.png',
                'lender_id'       => $book->user_id,
            ];

            if ($request->status === 'approved') {
                $itemData['status'] = 'onread';
                $itemData['statusLabel'] = __('borrow.on_read');
                $onReadBooks[] = $itemData;
            } elseif ($request->status === 'pending') {
                $itemData['status'] = 'appeal';
                $itemData['statusLabel'] = __('borrow.appealed');
                $pendingBooks[] = $itemData;
            } else {
                $itemData['status'] = 'finish';
                $itemData['statusLabel'] = __('borrow.finished');
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
        // Prevent self-borrow
        if ($book->user_id === Auth::id()) {
            return redirect()->route('book.show', $book->id)
                ->with('error', 'Anda tidak dapat meminjam buku milik Anda sendiri.');
        }

        $validated = $request->validate([
            'message'       => 'nullable|string|max:500',
            'borrow_date'   => 'required|date',
            'return_date'   => 'required|date|after_or_equal:borrow_date',
        ]);

        $user = Auth::user();
        $validated['book_id']       = $book->id;
        $validated['user_id']       = $user->id;
        $validated['borrower_name'] = $user->name;
        $validated['full_name']     = $user->name;
        $validated['email']         = $user->email;
        $validated['phone']         = $user->phone ?? '-'; // Use phone from user if available
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
     * Manual status update from Borrowed Books page (badge click).
     */
    public function updateStatus(Request $request, BorrowRequest $borrowRequest)
    {
        if ($borrowRequest->email !== Auth::user()->email) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'status' => 'required|in:appeal,approved,onread,finish',
        ]);

        $uiStatus = $validated['status'];
        $dbStatus = match ($uiStatus) {
            'appeal'   => 'pending',
            'approved', 'onread' => 'approved',
            'finish'   => 'returned',
        };

        $borrowRequest->update(['status' => $dbStatus]);

        $book = $borrowRequest->book;
        if ($book) {
            $bookStatus = match ($dbStatus) {
                'pending'  => $book->book_status === 'on_loan' ? 'available' : $book->book_status,
                'approved' => 'on_loan',
                'returned' => 'returned',
            };
            $book->update(['book_status' => $bookStatus]);
        }

        $statusLabel = match ($uiStatus) {
            'appeal'   => __('borrow.appealed'),
            'approved' => __('borrow.approved'),
            'onread'   => __('borrow.on_read'),
            'finish'   => __('borrow.finished'),
        };

        return response()->json([
            'success'      => true,
            'status'       => $uiStatus,
            'status_label' => $statusLabel,
            'status_class' => match ($uiStatus) {
                'appeal'   => 's-appeal',
                'approved' => 's-approved',
                'onread'   => 's-onread',
                default    => 's-finish',
            },
        ]);
    }

    /**
     * Cancel / delete a borrow request.
     */
    public function destroy(BorrowRequest $borrowRequest)
    {
        $borrowRequest->delete();
        return redirect()->back()->with('success', 'Permintaan peminjaman dibatalkan!');
    }

    /**
     * Dismiss (sembunyikan) notifikasi dari panel lonceng.
     * Hanya menandai dismissed_by_owner = true, tidak menghapus record.
     */
    public function dismiss(BorrowRequest $borrowRequest)
    {
        // Pastikan hanya pemilik buku yang bisa dismiss
        if ($borrowRequest->book?->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $borrowRequest->update([
            'dismissed_by_owner' => true,
            'read_by_owner'      => true,
        ]);

        // Hitung ulang jumlah notif yang belum dibaca
        $unreadCount = BorrowRequest::whereHas('book', fn($q) => $q->where('user_id', Auth::id()))
            ->where('read_by_owner', false)
            ->where('dismissed_by_owner', false)
            ->count();

        return response()->json([
            'success'      => true,
            'unread_count' => $unreadCount,
        ]);
    }
}
