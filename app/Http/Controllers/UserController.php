<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\UserLevel;

class UserController extends Controller
{   
    //Create a New User
    public function create(){
        return view('users.create');
    }

    //Store the Created New User
    public function store(Request $request){
        $formFields = $request->validate([
            'name' => ['required', 'min:3'],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'password' => 'required|confirmed|min:6',
            'admin' => 'required|integer',
            'group' => 'required',
        ]);

        //Hash Password
        $formFields['password'] = bcrypt($formFields['password']);

        //Create User
        $user = User::create($formFields);

        return redirect('/admin/user')->with('message', 'User Created Succesfully!');
    }

    //Show the Edit Form
    public function edit(User $user){
        return view('users.edit', ['user' => $user]);
    }

    //Update the User
    public function update(Request $request, User $user){
        $formFields = $request->validate([
            'name' => ['required', 'min:3'],
            'email' => ['required', 'email'],
            'password' => 'required|confirmed|min:6',
            'admin' => 'required|integer',
            'group' => 'required',
        ]);

        $formFields['password'] = bcrypt($formFields['password']);

        $user->update($formFields);

        return redirect('/admin/user')->with('message', 'User Updated Succesfully!');
    }

    //Delete the User
    public function delete(User $user){
        $user->delete();

        return redirect('/admin/user')->with('message', 'User Deleted Succesfully!');
    }
    
    
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
