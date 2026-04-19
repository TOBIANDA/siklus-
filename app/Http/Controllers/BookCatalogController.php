<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookCatalogController extends Controller
{
    /**
     * Show the lent (my books) page.
     */
    public function index()
    {
        $books = Book::with('borrowRequests')->latest()->get();
        return view('pages.lent', compact('books'));
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

        // Default owner info (can be tied to auth user later)
        $validated['owner_name']   = 'Me';
        $validated['owner_avatar'] = 'avatar_user.png';

        Book::create($validated);

        return redirect()->route('lent')->with('success', 'Buku berhasil ditambahkan ke katalog!');
    }

    /**
     * Update a book in the catalog.
     */
    public function update(Request $request, Book $book)
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

        $book->update($validated);

        return redirect()->route('lent')->with('success', 'Buku berhasil diperbarui!');
    }

    /**
     * Delete a book from the catalog.
     */
    public function destroy(Book $book)
    {
        $book->delete();
        return redirect()->route('lent')->with('success', 'Buku berhasil dihapus dari katalog!');
    }
}
