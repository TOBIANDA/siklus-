<?php

namespace App\Http\Controllers;

use App\Models\BorrowRequest;
use Illuminate\Support\Facades\Auth;

class MessagesController extends Controller
{
    /**
     * Build the base query: requests where I am either the book owner OR the borrower.
     */
    private function baseQuery()
    {
        $userId = Auth::id();
        $userEmail = Auth::user()->email;

        return BorrowRequest::with(['book.user'])
            ->where(function ($query) use ($userId, $userEmail) {
                // Incoming: My books being requested
                $query->whereHas('book', fn($q) => $q->where('user_id', $userId))
                      // Outgoing: Books I am requesting
                      ->orWhere('email', $userEmail);
            })
            ->orderByDesc('created_at');
    }

    /**
     * Identify the email of the "other person" in the thread.
     */
    private function getThreadKey($req, $myUserId) {
        return $req->book->user_id === $myUserId ? $req->email : ($req->book->user->email ?? 'unknown');
    }

    public function index()
    {
        $userId = Auth::id();
        $allRequests = $this->baseQuery()->get();

        // Group into unique threads by the other person's email
        $requests = $allRequests->unique(function ($req) use ($userId) {
            return $this->getThreadKey($req, $userId);
        })->values();

        $activeRequest = $requests->first();

        // If I'm the owner, mark latest incoming message as read
        if ($activeRequest && $activeRequest->book->user_id === $userId && !$activeRequest->read_by_owner) {
            $activeRequest->update(['read_by_owner' => true]);
        }

        return view('pages.messages', compact('requests', 'activeRequest'));
    }

    public function show(string $email)
    {
        $userId = Auth::id();
        $email = urldecode($email);

        $allRequests = $this->baseQuery()->get();

        $requests = $allRequests->unique(function ($req) use ($userId) {
            return $this->getThreadKey($req, $userId);
        })->values();

        // The active request for this thread contact email
        $activeRequest = $allRequests->first(function ($req) use ($userId, $email) {
            return $this->getThreadKey($req, $userId) === $email;
        });

        if ($activeRequest && $activeRequest->book->user_id === $userId && !$activeRequest->read_by_owner) {
            $activeRequest->update(['read_by_owner' => true]);
        }

        return view('pages.messages', compact('requests', 'activeRequest'));
    }
}
