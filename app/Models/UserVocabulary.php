<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserVocabulary extends Model
{
    use HasFactory;

    protected $table = 'uservocabularies';

    protected $fillable = [
        'user_id', 'level_id', 'semantic_id', 'language1', 'language2', 'mnemonics', 'mnemoniclist', 'semanticlist',
        'attempt_lang1', 'success_lang1', 'attempt_lang2', 'sucess_lang2', 'memorizationLevel', 'nextReview',
    ];

}
