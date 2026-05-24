<?php

namespace App\Http\Controllers;

use App\Models\BorrowRequest;
use App\Models\Message;
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

        return BorrowRequest::with(['book.user', 'user'])
            ->where(function ($query) use ($userId, $userEmail) {
                // Incoming: My books being requested
                $query->whereHas('book', fn($q) => $q->where('user_id', $userId))
                      // Outgoing: Books I am requesting (match by user_id OR email for backward compat)
                      ->orWhere('user_id', $userId)
                      ->orWhere('email', $userEmail);
            })
            ->orderByDesc('created_at');
    }

    /**
     * Identify the email of the "other person" in the thread.
     */
    private function getThreadKey($req, $myUserId) {
        if ($req->book->user_id === $myUserId) {
            // I am the owner → thread key is borrower's email
            return $req->email ?? ($req->user->email ?? 'unknown');
        } else {
            // I am the borrower → thread key is owner's email
            return $req->book->user->email ?? 'unknown';
        }
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

    /**
     * Send a message via API - for real-time chat
     */
    public function store()
    {
        $validated = request()->validate([
            'recipient_id' => 'required|exists:users,id',
            'content' => 'required|string|max:5000',
            'borrow_request_id' => 'nullable|exists:borrow_requests,id'
        ]);

        $message = Message::create([
            'sender_id' => Auth::id(),
            'recipient_id' => $validated['recipient_id'],
            'content' => $validated['content'],
            'borrow_request_id' => $validated['borrow_request_id'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => $message->load('sender', 'recipient')
        ], 201);
    }

    /**
     * Get message history between current user and recipient
     */
    public function getHistory($recipientId)
    {
        $userId = Auth::id();

        $messages = Message::where(function ($query) use ($userId, $recipientId) {
            $query->where(function ($q) use ($userId, $recipientId) {
                $q->where('sender_id', $userId)->where('recipient_id', $recipientId);
            })->orWhere(function ($q) use ($userId, $recipientId) {
                $q->where('sender_id', $recipientId)->where('recipient_id', $userId);
            });
        })
        ->orderBy('created_at', 'asc')
        ->with('sender', 'recipient')
        ->get();

        return response()->json([
            'success' => true,
            'messages' => $messages
        ]);
    }

    /**
     * Mark message as read
     */
    public function markAsRead($messageId)
    {
        $message = Message::find($messageId);

        if (!$message) {
            return response()->json([
                'success' => false,
                'message' => 'Message not found'
            ], 404);
        }

        if ($message->recipient_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $message->update(['read_at' => now()]);

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }
}
