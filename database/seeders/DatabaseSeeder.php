<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Level;
use App\Models\Vocabulary;
use App\Models\UserLevel;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //Create 2 random users
        \App\Models\User::factory(2)->create();

        //Create Level 1
        $level = Level::create( [
            'leveltitle' => 1, 
        ]);

        //Create Level 1 Contents
        Vocabulary::create([
            'level_id' => $level->id,
            'language1' => 'Sayur',
            'language2' => 'Vegetable',
            'mnemonics' => 'Mnemonic of Vegetable',
            'mnemoniclist' => 'Mnemonic List of Vegetable',
            'semanticlist' => 'Word Grouping of Vegetable',
        ]);

        Vocabulary::create([
            'level_id' => $level->id,
            'language1' => 'Minyak',
            'language2' => 'Oil',
            'mnemonics' => 'Mnemonic of Oil',
            'mnemoniclist' => 'Mnemonic List of Oil',
            'semanticlist' => 'Word Grouping of Oil',
        ]);

        Vocabulary::create([
            'level_id' => $level->id,
            'language1' => 'Daging',
            'language2' => 'Meat',
            'mnemonics' => 'Mnemonic of Meat',
            'mnemoniclist' => 'Mnemonic List of Meat',
            'semanticlist' => 'Word Grouping of Meat',
        ]);

    }
}
