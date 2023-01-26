<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Semantic extends Model
{
    use HasFactory;

    protected $table = 'semantics';

    protected $fillable = [
        'level_id', 'semanticlanguage1', 'semanticlanguage2', 'semanticdata_id',
    ];

    //Relationship to Level
    public function level(){
        return $this->belongTo(Level::class, 'level_id');
    }
}
