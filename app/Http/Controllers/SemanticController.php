<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Level;
use App\Models\Semantic;
use App\Models\Vocabulary;

class SemanticController extends Controller
{
    //Create a New Semantic
    public function create(Level $level){
        return view('semantics.create', ['level' => $level]);
    }

    //Store the Created New Semantic
    public function store(Request $request, Level $level){
        $formFields = $request->validate([
            'semanticlanguage1' => 'required',
            'semanticlanguage2' => 'required',
            'semanticdata_id' => 'required',
        ]);

        $level_id = $level->id;

        $formFields['level_id'] = $level_id;

        Semantic::create($formFields);

        return redirect('/admin/level/'.$level_id.'/vocabulary')->with('message', 'Semantic Created Succesfully!');
    }

    //Show the Edit Form
    public function edit(Level $level, Semantic $semantic){
        return view('semantics.edit', ['level' => $level, 'semantic' => $semantic,]);
    }

    //Update the Vocabulary
    public function update(Request $request, Level $level, Semantic $semantic){

        if($semantic->level_id != $level->id){
            abort(403, 'Unauthorized Action');
        }

        $formFields = $request->validate([
            'semanticlanguage1' => 'required',
            'semanticlanguage2' => 'required',
            'semanticdata_id' => 'required',
        ]);

        $level_id = $level->id;

        $formFields['level_id'] = $level_id;

        $semantic->update($formFields);

        return redirect('/admin/level/'.$level_id.'/vocabulary')->with('message', 'Semantic Updated Succesfully!');
    }

    //Delete the Vocabulary
    public function delete(Level $level, Semantic $semantic){
        if($semantic->level_id != $level->id){
            abort(403, 'Unauthorized Action');
        }

        $level_id = $level->id;

        $semantic->delete();

        return redirect('/admin/level/'.$level_id.'/vocabulary')->with('message', 'Semantic Deleted Succesfully!');
    }
}
