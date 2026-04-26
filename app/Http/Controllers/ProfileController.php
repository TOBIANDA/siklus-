<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Tampilkan halaman profile
     */
    public function show()
    {
        $user = Auth::user();

        // Sample collection just for UI display if needed
        // Or fetch from actual books
        $collection = $user->books()->take(4)->get()->map(function($book) {
            return [
                'id' => $book->id,
                'title' => $book->title,
                'author' => $book->author,
                'cover' => $book->cover,
            ];
        });

        // Sample badges
        $badges = [
            ['name' => 'Top Lender', 'desc' => 'Dipinjam 20+ kali', 'icon' => '📚', 'bg' => '#1a1a1a'],
            ['name' => 'Fast Responder', 'desc' => 'Membalas < 2 jam', 'icon' => '⚡', 'bg' => '#1a1a1a'],
            ['name' => 'Trusted', 'desc' => 'Rating > 4.5', 'icon' => '⭐', 'bg' => '#1a1a1a']
        ];

        return view('profile', compact('user', 'collection', 'badges'));
    }

    /**
     * Show edit profile page (Settings).
     */
    public function edit()
    {
        $user = Auth::user();
        return view('pages.profile_edit', compact('user'));
    }

    /**
     * Update profil
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'occupation' => 'nullable|string|max:255',
            'bio'        => 'nullable|string|max:1000',
            'province'   => 'nullable|string|max:100',
            'city'       => 'nullable|string|max:100',
        ]);

        $user = Auth::user();
        $user->update($validated);

        return back()->with('success', 'Profil berhasil diperbarui!');
    }

    /**
     * Upload foto profil
     */
    public function uploadPhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('profile', 'public');
            
            $user = Auth::user();
            $user->update(['avatar' => basename($path)]);
            
            return back()->with('success', 'Foto profil berhasil diperbarui!');
        }

        return back()->with('error', 'Gagal upload foto');
    }
}
