<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

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

    public function showResetForm($token)
    {
        return view('auth.passwords-reset', ['token' => $token]);
    }

    public function reset(Request $request)
    {
        // Validate only the password fields
        $request->validate([
            'password' => 'required|min:8|confirmed',
            'token' => 'required'
        ]);

        // Get the authenticated user
        $user = Auth::user();

        // Check if the authenticated user is an admin
        if (!$user || $user->role !== 'admin') {
            throw ValidationException::withMessages([
                'email' => ['Only administrators can reset their password.'],
            ]);
        }

        // Attempt to reset the user's password
        $status = Password::reset(
            [
                'email' => $user->email,
                'password' => $request->password,
                'password_confirmation' => $request->password_confirmation,
                'token' => $request->token,
            ],
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();
            }
        );

        // Check the result and return an appropriate response
        if ($status == Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('status', __($status));
        }

        // If the reset failed, throw a validation exception
        throw ValidationException::withMessages([
            'email' => [trans($status)],
        ]);
    }
}
