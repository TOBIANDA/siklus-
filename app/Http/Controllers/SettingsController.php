<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SettingsController extends Controller
{
    /**
     * Show settings page
     */
    public function show()
    {
        return view('pages.settings');
    }

    /**
     * Save language preference
     */
    public function saveLanguage(Request $request)
    {
        $validated = $request->validate([
            'language' => 'required|in:id,en',
        ]);

        session(['locale' => $validated['language']]);
        Auth::user()->update(['language_preference' => $validated['language']]);

        return response()->json(['success' => true, 'message' => 'Bahasa berhasil diubah.']);
    }

    /**
     * Save appearance (theme + text size) — both stored individually
     */
    public function saveAppearance(Request $request)
    {
        $validated = $request->validate([
            'theme'     => 'required|in:light,dark',
            'text_size' => 'required|in:small,normal,large',
        ]);

        Auth::user()->update([
            'theme_preference' => $validated['theme'],
            'text_size'        => $validated['text_size'],
        ]);

        return response()->json([
            'success'   => true,
            'message'   => 'Tampilan berhasil diubah.',
            'theme'     => $validated['theme'],
            'text_size' => $validated['text_size'],
        ]);
    }

    /**
     * Save notification preferences
     */
    public function saveNotifications(Request $request)
    {
        $fields = ['notif_borrow', 'notif_message', 'notif_return', 'notif_updates'];
        $data   = [];

        foreach ($fields as $field) {
            // Accept both boolean (JSON) and string ('true'/'1')
            $val = $request->input($field);
            $data[$field] = filter_var($val, FILTER_VALIDATE_BOOLEAN);
        }

        Auth::user()->update($data);

        return response()->json(['success' => true, 'message' => 'Notifikasi berhasil disimpan.']);
    }

    /**
     * Save privacy preferences
     */
    public function savePrivacy(Request $request)
    {
        $fields = ['public_profile', 'show_location'];
        $data   = [];

        foreach ($fields as $field) {
            $val = $request->input($field);
            $data[$field] = filter_var($val, FILTER_VALIDATE_BOOLEAN);
        }

        Auth::user()->update($data);

        return response()->json(['success' => true, 'message' => 'Pengaturan privasi disimpan.']);
    }

    /**
     * Change password
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password'          => 'required',
            'new_password'              => 'required|min:8|confirmed',
            'new_password_confirmation' => 'required',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['success' => false, 'message' => 'Password saat ini tidak sesuai.'], 422);
        }

        $user->update(['password' => Hash::make($request->new_password)]);

        return response()->json(['success' => true, 'message' => 'Password berhasil diubah.']);
    }
}
