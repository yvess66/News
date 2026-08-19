<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status == Password::RESET_LINK_SENT
                    ? back()->with('status', __($status))
                    : back()->withInput($request->only('email'))
                        ->withErrors(['email' => __($status)]);
    }

    /**
     * Get password hint for a user by email.
     */
    public function hint(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = \App\Models\User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Email tidak ditemukan dalam sistem.'
            ]);
        }

        if (!$user->password_hint) {
            return response()->json([
                'success' => false,
                'message' => 'Akun ini tidak memiliki hint password.'
            ]);
        }

        return response()->json([
            'success' => true,
            'hint' => $user->password_hint
        ]);
    }

    /**
     * Handle direct password reset with hint verification.
     */
    public function resetDirect(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'hint' => ['required', 'string'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $user = \App\Models\User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Email tidak ditemukan dalam sistem.'
            ]);
        }

        if (!$user->password_hint || strtolower($user->password_hint) !== strtolower($request->hint)) {
            return response()->json([
                'success' => false,
                'message' => 'Hint password tidak sesuai.'
            ]);
        }
        // Update password
        $user->password = Hash::make($request->password);
        $user->save();
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Password berhasil diperbarui!'
        ]);
    }
}
