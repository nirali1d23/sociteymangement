<?php

namespace App\Http\Controllers\AdminPanel;
use Auth;
use App\Http\Model\User;
use Hash;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
class AuthController extends Controller
{
    // public function login()
    // {
    //     return view('Auth.login');
    // }

    public function authlogin(Request $request)
    {
     

        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
 
            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }
}
