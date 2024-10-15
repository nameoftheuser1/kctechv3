<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
}
