<?php

namespace App\Http\Controllers;

use App\Models\BorrowRequest;
use Illuminate\Http\Request;

class MessagesController extends Controller
{
    /**
     * Show the messages/inbox page.
     * The "conversations" are each unique borrower who has sent a request.
     */
    public function index()
    {
        // Group requests by borrower email so each person = 1 thread
        $requests = BorrowRequest::with('book')
            ->orderByDesc('created_at')
            ->get()
            ->unique('email');   // one thread per borrower email

        $activeRequest = $requests->first();

        // Mark as read when opened
        if ($activeRequest) {
            BorrowRequest::where('email', $activeRequest->email)
                ->update(['read_by_owner' => true]);
        }

        return view('pages.messages', compact('requests', 'activeRequest'));
    }

    /**
     * Open a specific conversation thread by borrower email.
     */
    public function show(string $email)
    {
        $requests = BorrowRequest::with('book')
            ->orderByDesc('created_at')
            ->get()
            ->unique('email');

        $activeRequest = BorrowRequest::with('book')
            ->where('email', $email)
            ->latest()
            ->first();

        // Mark thread as read
        if ($activeRequest) {
            BorrowRequest::where('email', $email)
                ->update(['read_by_owner' => true]);
        }

        return view('pages.messages', compact('requests', 'activeRequest'));
    }
}
