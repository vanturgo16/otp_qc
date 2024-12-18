<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// Model
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        return view('auth.login');
        if (app()->environment('production')) {
            return redirect('https://sso.olefinatifaplas.my.id/login');
        } else {
            return redirect('http://127.0.0.1:8000/login');
        }
    }

    public function postlogin(Request $request)
    {
        // dd("asdf");
        $email = $request->email;
        $password = $request->password;
        $credentials = [
            'email' => $email,
            'password' => $password
        ];
        $dologin = Auth::attempt($credentials);
        if ($dologin) {
            $checkstatus = User::where('email', $request->email)->first()->is_active;
            if ($checkstatus == 1) {
                return redirect()->route('dashboard')->with('success', 'Successfully Entered The Application');
            } else {
                return redirect()->route('login')->with('fail', 'Your Account Is Innactive');
            }
        } else {
            return redirect()->route('login')->with('fail', 'Wrong Email or Password');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        if (app()->environment('production')) {
            return redirect('https://sso.olefinatifaplas.my.id/login');
        } else {
            return redirect('http://127.0.0.1:8000/login');
        }
        // return redirect()->route('login')->with('success','Success Logout');
    }
}
