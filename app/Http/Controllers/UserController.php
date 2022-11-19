<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    //Login
    public function login(){
            return view('guests.login');
    }

    //Authentication (Login Step 2)
    public function authenticate(Request $request){
        $formFields = $request->validate([
            'email' => ['required', 'email'],
            'password' => 'required',
        ]);

        if (auth()->attempt($formFields)) {
            $request->session()->regenerate();

            return redirect('/')->with('message', 'You are logged in!');
        } else {
            return back()->withErrors(['email' => 'Invalid Credentials'])->onlyInput('email');
        }
    }

    //Logout
    public function logout(Request $request){
        auth()->logout();

        //Resetting the Token!
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('message', 'User Logged out!');
    }
}
