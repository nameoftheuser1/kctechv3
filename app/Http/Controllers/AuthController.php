<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $fields = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);

        if (Auth::attempt(['username' => $fields['username'], 'password' => $fields['password']])) {
            $user = Auth::user();

            if ($user->role == 'user') {
                return redirect()->route('reservations.index');
            }

            return redirect()->intended('dashboard');
        } else {
            return back()->withErrors([
                'username' => 'The provided credentials do not match our records.',
            ])->withInput();
        }
    }


    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }

    /**
     * Send the reset password link to the configured email.
     */
    public function sendResetLink(Request $request)
    {
        // Retrieve the email from the settings table
        $adminUser = DB::table('users')->where('role', 'admin')->first();

        if (!$adminUser) {
            return response()->json(['message' => 'Admin user not found.'], 404);
        }

        // Retrieve the email of the admin user
        $email = $adminUser->email;

        // Attempt to send the reset link
        $response = Password::sendResetLink(['email' => $email]);

        if ($response === Password::RESET_LINK_SENT) {
            return response()->json(['message' => 'Reset link sent to your email!'], 200);
        }

        return response()->json(['message' => 'Failed to send reset link.'], 500);
    }
}
