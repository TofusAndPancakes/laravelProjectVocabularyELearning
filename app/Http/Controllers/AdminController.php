<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Level;
use App\Models\Vocabulary;

class AdminController extends Controller
{
    public function indexLevel(){
        $levelContents = Level::all();
        return view('admins.indexLevel', ['levels' => $levelContents]); 
    }

    public function indexVocabulary(Level $level){
        return view('admins.indexVocabulary', [
            'level'=> $level,
            'vocabularies' => Vocabulary::where('level_id', $level->id)->get(),
        ]);
    }

    public function indexUser(){
        $userContents = User::all();
        return view('admins.indexUser', [
            'users'=> $userContents,
        ]);
    }
}
