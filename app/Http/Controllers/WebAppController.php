<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Level;
use App\Models\UserLevel;
use App\Models\Vocabulary;
use App\Models\UserVocabulary;
use Carbon\Carbon;

class WebAppController extends Controller
{
    public function index(){
        //Check for If Levels Have been Downloaded
        $userLevel = UserLevel::where('user_id', auth()->id())->first();
        $level = Level::where('id', $userLevel->id)->first();

        //Check for New Lessons
        if ($userLevel->currentlevelimport == 0){
            //User hasnt imported
            //Search in Vocabulary Table How Much!
            $newLessons = Vocabulary::where('level_id', $level->id)->count();

        } else {
            //User has imported
            //Note that memorizationLevel = 0 means no learning has been completed yet...
            $newLessons = UserVocabulary::where('level_id', $level->id)
                                                    ->where('user_id', auth()->id())
                                                    ->where('memorizationLevel', 0)->count();
        }

        //Check for Available Lessons!
        $newReviews = UserVocabulary:: where('user_id', auth()->id())
                                                ->where('memorizationLevel', '>', 0)
                                                ->count();
        //Add Current Time Later!

        return view('users.indexUser', [
            'newLessons' => $newLessons,
            'newReviews' => $newReviews,
        ]);
    }

    public function lesson(){
        //Check for If Levels Have been Downloaded
        $userLevel = UserLevel::where('user_id', auth()->id())->first();
        $level = Level::where('id', $userLevel->id)->first();

        //We are limiting it to 5 at once!

        //Import all of them in!
        if ($userLevel->currentlevelimport == 0){
            $newLessons = Vocabulary::where('level_id', $level->id)->get();
            //Count How Much is there!
            $newLessonsCount = $newLessons->count();

            foreach($newLessons as $newLesson){
                $formFields['user_id'] = auth()->id();
                $formFields['level_id'] = $newLesson->level_id;
                $formFields['language1'] = $newLesson->language1;
                $formFields['language2'] = $newLesson->language2;
                $formFields['mnemonics'] = $newLesson->mnemonics;
                $formFields['mnemoniclist'] = $newLesson->mnemoniclist;
                $formFields['semanticlist'] = $newLesson->semanticlist;
                $formFields['nextReview'] = Carbon::now()->timestamp;

                //NextReview temporarily zero...
                UserVocabulary::create($formFields);
            }

            //Dont Forget to Set CurrentLevelImport to 1!
            UserLevel::where('user_id', auth()->id())->first()->update([
                'currentlevelimport' => 1,
            ]);
            
        }

        //Take 5 of them!
        $newLessons = UserVocabulary::where('user_id', auth()->id())
                                        ->where('level_id', $level->id)
                                        ->where('nextReview', '<=', Carbon::now()->timestamp)
                                        ->take(5)->get();

        return view('users.lesson', [
            'newLessons' => $newLessons,
        ]);
    }

    public function review(){
        
    }
}
