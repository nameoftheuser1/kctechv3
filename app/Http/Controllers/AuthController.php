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
            return redirect()->intended('dashboard');
        } else {
            return back()->withErrors([
                'username' => 'The provided credentials do not match our records.',
            ])->withInput();
        }
    }
}
