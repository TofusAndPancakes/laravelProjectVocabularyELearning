<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Level;

class LevelController extends Controller
{
    //Create a New Level
    public function create(){
        return view('levels.create');
    }

    //Store the Created New Level
    public function store(Request $request){
        $formFields = $request->validate([
            'leveltitle' => ['required', Rule::unique('levels', 'leveltitle')],
        ]);

        Level::create($formFields);

        return redirect('/admin')->with('message', 'Level Created Succesfully!');
    }

    //Show the Edit Form
    public function edit(Level $level){
        return view('levels.edit', ['level' => $level]);
    }

    //Update the Level
    public function update(Request $request, Level $level){
        $formFields = $request->validate([
            'leveltitle' => ['required', Rule::unique('levels', 'leveltitle')],
        ]);

        $level->update($formFields);

        return redirect('/admin')->with('message', 'Level Updated Succesfully!');
    }

    //Delete the Level
    public function delete(Level $level){
        $level->delete();

        return redirect('/admin')->with('message', 'Level Deleted Succesfully!');
    }
}
