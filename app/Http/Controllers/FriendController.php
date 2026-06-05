<?php

namespace App\Http\Controllers;

use App\Models\Friend;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FriendController extends Controller
{
    /**
     * Show the Friends page with accepted friends + stats.
     */
    public function index()
    {
        $user = Auth::user();

        // Get all accepted friends (both directions)
        $sentAcceptedIds     = Friend::where('user_id', $user->id)->where('status', 'accepted')->pluck('friend_id');
        $receivedAcceptedIds = Friend::where('friend_id', $user->id)->where('status', 'accepted')->pluck('user_id');
        $friendIds           = $sentAcceptedIds->merge($receivedAcceptedIds)->unique();
        $friends             = User::whereIn('id', $friendIds)->get();

        // Pending requests received (others sent to me)
        $pendingReceived = Friend::where('friend_id', $user->id)
            ->where('status', 'pending')
            ->with('user')
            ->get();

        // Pending requests sent (I sent, waiting)
        $pendingSent = Friend::where('user_id', $user->id)
            ->where('status', 'pending')
            ->with('friend')
            ->get();

        // Stats
        $totalBooksListed = $friends->sum('books_listed');
        $totalExchanges   = $friends->sum('exchanges');

        return view('pages.friends', compact(
            'friends',
            'pendingReceived',
            'pendingSent',
            'totalBooksListed',
            'totalExchanges'
        ));
    }

    /**
     * Send a friend request to another user.
     */
    public function store(Request $request)
    {
        $request->validate(['friend_id' => 'required|exists:users,id']);

        $userId   = Auth::id();
        $friendId = $request->friend_id;

        if ($userId === (int) $friendId) {
            return back()->with('error', 'Tidak bisa menambah diri sendiri sebagai teman.');
        }

        // Check if already friends or request exists
        $exists = Friend::where(function ($q) use ($userId, $friendId) {
            $q->where('user_id', $userId)->where('friend_id', $friendId);
        })->orWhere(function ($q) use ($userId, $friendId) {
            $q->where('user_id', $friendId)->where('friend_id', $userId);
        })->first();

        if ($exists) {
            return back()->with('error', 'Permintaan pertemanan sudah ada.');
        }

        Friend::create([
            'user_id'   => $userId,
            'friend_id' => $friendId,
            'status'    => 'pending',
        ]);

        return back()->with('success', 'Permintaan pertemanan dikirim!');
    }

    /**
     * Accept a friend request.
     */
    public function accept(Friend $friend)
    {
        // Only the recipient can accept
        if ($friend->friend_id !== Auth::id()) {
            abort(403);
        }

        $friend->update(['status' => 'accepted']);

        return back()->with('success', 'Permintaan pertemanan diterima!');
    }

    /**
     * Reject / cancel a friend request.
     */
    public function reject(Friend $friend)
    {
        // Only the recipient can reject, or sender can cancel
        if ($friend->friend_id !== Auth::id() && $friend->user_id !== Auth::id()) {
            abort(403);
        }

        $friend->delete();

        return back()->with('success', 'Permintaan pertemanan ditolak.');
    }

    /**
     * Search for users to add as friends (JSON API).
     */
    public function search(Request $request)
    {
        $q      = $request->get('q', '');
        $userId = Auth::id();

        if (strlen($q) < 2) {
            return response()->json(['users' => []]);
        }

        $users = User::where('id', '!=', $userId)
            ->where(function ($query) use ($q) {
                $query->where('name', 'LIKE', "%{$q}%")
                      ->orWhere('email', 'LIKE', "%{$q}%");
            })
            ->limit(8)
            ->get();

        // Get all friend relationships involving current user
        $friendships = Friend::where('user_id', $userId)
            ->orWhere('friend_id', $userId)
            ->get();

        $result = $users->map(function ($user) use ($userId, $friendships) {
            // Find if friendship exists
            $friendship = $friendships->first(function ($f) use ($user, $userId) {
                return ($f->user_id === $userId && $f->friend_id === $user->id)
                    || ($f->user_id === $user->id && $f->friend_id === $userId);
            });

            return [
                'id'            => $user->id,
                'name'          => $user->name,
                'occupation'    => $user->occupation,
                'avatar_url'    => $user->avatar_url,
                'friend_status' => $friendship ? $friendship->status : null,
            ];
        });

        return response()->json(['users' => $result]);
    }

    /**
     * Remove an existing friendship.
     */
    public function destroy(Friend $friend)
    {
        if ($friend->user_id !== Auth::id() && $friend->friend_id !== Auth::id()) {
            abort(403);
        }

        $friend->delete();

        return back()->with('success', 'Teman dihapus.');
    }
}
