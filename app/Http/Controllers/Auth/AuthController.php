<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Redirect;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest', ['except' => ['logout']]);
    }

    public function showLoginForm() {
        session(['url.intended' => url()->previous()]);
        return view('auth.login');
    }

    public function login(Request $request)
    {
         $request->validate([
            'email' => 'required',
            'password' => 'required|string',
        ]);

        $remember_me  = ( !empty( $request->remember_me ) )? TRUE : FALSE;
   
        $credentials = $request->only('email', 'password');
        $user = User::where(["email" => $credentials['email']])->first();
        if(!isset($user)) {
          return redirect()->back()->withInput()->with('error', 'Wrong Credentials');
        }

        if(!isset($user) || ( isset($user->status) && $user->status == 0)) { //in-active
          return redirect()->back()->withInput()->with('error', 'You account has been deactivated, kindly contact admin');
        }
        if (Auth::attempt($credentials)) {
          Auth::login($user, $remember_me);
          return Redirect::to(session('url.intended'));
          session()->forget('url.intended');
        }
  
        return redirect("login")->withInput()->with('error', 'You have entered invalid credentials');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
