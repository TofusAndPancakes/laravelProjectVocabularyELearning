<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLevel extends Model
{
    use HasFactory;

    protected $table = 'userlevels';

    protected $fillable = [
        'user_id', 'currentlevel', 'currentlevelprogress', 'currentlevelimport'
    ];

    //Relationship to Level
    public function user(){
        return $this->belongTo(User::class, 'user_id');
    }
}
