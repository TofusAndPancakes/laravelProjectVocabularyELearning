<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    use HasFactory;

    protected $fillable = [
        'leveltitle',
    ];

    //Relationship to Vocabularies
    public function vocabularies(){
        return $this->hasMany(Vocabulary::class, 'level_id');
    }
}
