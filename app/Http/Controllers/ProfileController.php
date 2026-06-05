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

        // Real book count from DB
        $booksCount = $user->books()->count();

        // Fetch user's books for curated collection display
        $collection = $user->books()->latest()->take(4)->get()->map(function($book) {
            return [
                'id'        => $book->id,
                'title'     => $book->title,
                'author'    => $book->author,
                'cover'     => $book->cover,
                'cover_url' => $book->cover_url,
            ];
        });

        // Sample badges
        $badges = [
            ['name' => 'Top Lender',      'desc' => 'Dipinjam 20+ kali', 'icon' => '📚', 'bg' => '#EFF6FF'],
            ['name' => 'Fast Responder',  'desc' => 'Membalas < 2 jam',  'icon' => '⚡', 'bg' => '#FEF3C7'],
            ['name' => 'Trusted Lender',  'desc' => 'Rating > 4.5',      'icon' => '⭐', 'bg' => '#D1FAE5'],
        ];

        return view('profile', compact('user', 'collection', 'badges', 'booksCount'));
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
            try {
                // Store file in storage/app/public/profile/
                $file = $request->file('photo');
                $filename = 'avatar_' . Auth::id() . '_' . time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('profile', $filename, 'public');
                
                if (!$path) {
                    throw new \Exception('Gagal menyimpan file');
                }
                
                $user = Auth::user();
                $user->update(['avatar' => basename($path)]);
                
                // Refresh user instance to reflect new avatar
                $user->refresh();
                
                return back()->with('success', 'Foto profil berhasil diperbarui!');
            } catch (\Exception $e) {
                return back()->with('error', 'Gagal upload foto: ' . $e->getMessage());
            }
        }

        return back()->with('error', 'Gagal upload foto');
    }
}
