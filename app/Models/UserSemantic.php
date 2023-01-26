<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSemantic extends Model
{
    use HasFactory;

    protected $table = 'usersemantics';

    protected $fillable = [
        'user_id', 'level_id', 'semantic_id', 'completion',
    ];

}
