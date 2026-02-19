<?php

namespace App\Http\Controllers\AdminPanel;
use Auth;
use App\Http\Model\User;
    use Illuminate\Support\Facades\Hash;
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
    public function singout(Request $request)
    {
            Auth::logout();
            return redirect('/');
          
    }



public function changePassword(Request $request)
{
    $request->validate([
        'current_password' => 'required',
        'new_password' => 'required|min:6|confirmed',
    ]);

    $user = Auth::user(); // ✅ Works ONLY with web + auth middleware

    dd($user);

    if (!Hash::check($request->current_password, $user->password)) {
        return response()->json([
            'message' => 'Current password is incorrect'
        ], 422);
    }

    $user->password = Hash::make($request->new_password);
    $user->save();

    return response()->json([
        'message' => 'Password updated successfully'
    ]);
}
}
