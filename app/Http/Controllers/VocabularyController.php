<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Level;
use App\Models\Vocabulary;

class VocabularyController extends Controller
{
    //Create a New Vocabulary
    public function create(Level $level){
        return view('vocabularies.create', ['level' => $level]);
    }

    //Store the Created New Vocabulary
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

    //Update the Vocabulary
    public function update(Request $request, Level $level){
        $formFields = $request->validate([
            'leveltitle' => ['required', Rule::unique('levels', 'leveltitle')],
        ]);

        $level->update($formFields);

        return redirect('/admin')->with('message', 'Level Updated Succesfully!');
    }

    //Delete the Vocabulary
    public function delete(Level $level){
        $level->delete();

    }
}
