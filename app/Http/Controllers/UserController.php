<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserLevel;

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
        
            //Check if UserLevel is available and create it when needed?
            if (UserLevel::where('user_id', auth()->id())->exists()){
                return redirect('/menu')->with('message', 'You are logged in!');

            } else {
                //If its not available, create one quickly!
                $formFields['user_id'] = auth()->id();
                $formFields['currentlevel'] = 1;
                $formFields['currentlevelprogress'] = 0;
                $formFields['currentlevelimport'] = 0;

                UserLevel::create($formFields);

                return redirect('/menu')->with('message', 'You are logged in, User Level Created!');
            }

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
