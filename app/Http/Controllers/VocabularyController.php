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
    public function store(Request $request, Level $level){
        $formFields = $request->validate([
            'language1' => 'required',
            'language2' => 'required',
            'mnemonics' => 'required',
            'mnemoniclist' => 'present|string|max:255',
            'semanticlist' => 'required',
        ]);

        $level_id = $level->id;

        $formFields['level_id'] = $level_id;

        Vocabulary::create($formFields);

        return redirect('/admin/level/'.$level_id.'/vocabulary')->with('message', 'Vocabulary Created Succesfully!');
    }

    //Show the Edit Form
    public function edit(Level $level, Vocabulary $vocabulary){
        return view('vocabularies.edit', ['level' => $level, 'vocabulary' => $vocabulary,]);
    }

    //Update the Vocabulary
    public function update(Request $request, Level $level, Vocabulary $vocabulary){

        if($vocabulary->level_id != $level->id){
            abort(403, 'Unauthorized Action');
        }

        $formFields = $request->validate([
            'language1' => 'required',
            'language2' => 'required',
            'mnemonics' => 'required',
            'mnemoniclist' => 'nullable',
            'semanticlist' => 'required',
        ]);

        $level_id = $level->id;

        $formFields['level_id'] = $level_id;

        $vocabulary->update($formFields);

        return redirect('/admin/level/'.$level_id.'/vocabulary')->with('message', 'Vocabulary Updated Succesfully!');
    }

    //Delete the Vocabulary
    public function delete(Level $level, Vocabulary $vocabulary){
        if($vocabulary->level_id != $level->id){
            abort(403, 'Unauthorized Action');
        }

        $level_id = $level->id;

        $vocabulary->delete();

        return redirect('/admin/level/'.$level_id.'/vocabulary')->with('message', 'Vocabulary Deleted Succesfully!');
    }
}
