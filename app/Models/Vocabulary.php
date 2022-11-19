<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vocabulary extends Model
{
    use HasFactory;

    protected $fillable = [
        'language1', 'language2', 'mnemonics', 'mnemonicslist', 'semanticlist',
    ];

    //Relationship to Level
    public function level(){
        return $this->belongTo(Level::class, 'level_id');
    }
}
