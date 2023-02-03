<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use App\Models\Level;
use App\Models\UserLevel;
use App\Models\Vocabulary;
use App\Models\Semantic;
use App\Models\UserVocabulary;
use App\Models\UserSemantic;
use App\Models\User;
use Carbon\Carbon;

class WebAppController extends Controller
{
    public function index(){
        //Check for If Levels Have been Downloaded
        $userLevel = UserLevel::where('user_id', auth()->id())->first();
        $level = Level::where('leveltitle', $userLevel->currentlevel)->first();

        //If User is in Group 2 (Semantic)
        if (Auth::user()->group == 2){
            //Check for New Semantic Lessons
            //Check for New Lessons
            if ($level != null){
            
                if ($userLevel->currentlevelimport == 0){
                    //User hasnt imported
                    //Search in Vocabulary Table How Much!
                    $newLessons = Semantic::where('level_id', $level->id)->count();
                } else {
                    //User has imported
                    //Note that memorizationLevel = 0 means no learning has been completed yet...
                    $newLessons = UserSemantic::where('level_id', $level->id)
                                                            ->where('user_id', auth()->id())
                                                            ->where('completion', 0)->count();
                }    
            } else {
                //dd("no dice!");
                //There is no next level!
                $newLessons = null;
            }

        } else {
        //Else, they default to Group 1 (Mnemonics)
            //Check for New Lessons
            if ($level != null){
            
                if ($userLevel->currentlevelimport == 0){
                    //User hasnt imported
                    //Search in Vocabulary Table How Much!
                    $newLessons = Vocabulary::where('level_id', $level->id)->count();
                } else {
                    //User has imported
                    //Note that memorizationLevel = 0 means no learning has been completed yet...
                    $newLessons = UserVocabulary::where('level_id', $level->id)
                                                            ->where('user_id', auth()->id())
                                                            ->where('memorizationLevel', 'basic0')->count();
                }    
            } else {
                //dd("no dice!");
                //There is no next level!
                $newLessons = null;
            }
        }

       
        
        //Check for Available Lessons! <> is equivalent !=
        $newReviews = UserVocabulary:: where('user_id', auth()->id())
                                                ->where('memorizationLevel', '<>', 'basic0')
                                                ->where('nextReview', '<=', Carbon::now()->timestamp)
                                                ->count();
        //Add Current Time Later!

        return view('vocabapp.indexUser', [
            'newLessons' => $newLessons,
            'newReviews' => $newReviews,
            'userLevel' => Auth::user()->group,
        ]);
    }

    public function lesson(){
        //Check for If Levels Have been Downloaded
        $userLevel = UserLevel::where('user_id', auth()->id())->first();
        $level = Level::where('leveltitle', $userLevel->currentlevel)->first();

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
                                        ->where('memorizationLevel', 'basic0')
                                        ->oldest('updated_at')->take(6)->get();

        return view('vocabapp.lesson', [
            'newLessons' => $newLessons,
        ]);
    }

    public function semantic(){
        //Check for If Levels Have been Downloaded
        $userLevel = UserLevel::where('user_id', auth()->id())->first();
        $level = Level::where('leveltitle', $userLevel->currentlevel)->first();

        //Import all of them in!
        if ($userLevel->currentlevelimport == 0){
            $newLessons = Semantic::where('level_id', $level->id)->get();
            //Count How Much is there!
            $newLessonsCount = $newLessons->count();

            foreach($newLessons as $newLesson){
                //Split the Array! Not sure why but the last index will give blank.
                $semanticL1Split = preg_split("/\s*(?:,|$)\s*/", $newLesson->semanticlanguage1);
                $semanticL2Split = preg_split("/\s*(?:,|$)\s*/", $newLesson->semanticlanguage2);

                //Create User Semantic!
                $formFields['user_id'] = auth()->id();
                $formFields['level_id'] = $level->id;
                $formFields['semantic_id'] = $newLesson->id;

                //NextReview temporarily zero...
                UserSemantic::create($formFields);

                $counter = 0;
                $nextTitle = 0;
                $nextSegment = 0;

                //Temporarily Storing the Semantic List Title
                $semanticTitleL1;
                $semanticTitleL2;
                $semanticSegmentL1;
                $semanticSegmentL2;


                foreach($semanticL1Split as $semanticL1){

                    if ($nextTitle == 1){
                        //The Next Entry is SemanticTitle
                        $semanticTitleL1 = $semanticL1;
                        $semanticTitleL2 = $semanticL2Split[$counter];
                        $nextTitle = 0;
                        $counter++;
                    } else if ($nextSegment == 1){
                        //The Next Entry is SemanticSegment
                        $semanticSegmentL1 = $semanticL1;
                        $semanticSegmentL2 = $semanticL2Split[$counter];
                        $nextSegment = 0;
                        $counter++;
                    } else if ($semanticL1 == "SemanticTitle"){
                        //The ENtry is SemanticTitle
                        $nextTitle = 1;
                        $counter++;
                        continue;
                    } else if ($semanticL1 == "SemanticSegment"){
                        //The Entry is SematicSegment
                        $nextSegment = 1;
                        $counter++;
                        continue;
                    } else if ($semanticL1 == "Seperator"){
                        //If it is Seperator, Just Skip!
                        $counter++;
                        continue;
                    } else if ($semanticL1 == "") {
                        //If it is Empty, just continue...
                        $counter++;
                        continue;
                    } else {
                    //This means that this is an entry!
                    //Make User Vocabulary
                    $formFields['user_id'] = auth()->id();
                    $formFields['level_id'] = $level->id;
                    $formFields['semantic_id'] = $newLesson->id;
                    $formFields['language1'] = $semanticL1;
                    $formFields['language2'] = $semanticL2Split[$counter];
                    $formFields['mnemonics'] = "Semantic Lesson";
                    $formFields['mnemoniclist'] = "Semantic Lesson";
                    $formFields['semanticlist'] = $semanticTitleL1 . " (".$semanticTitleL2.") - " . $semanticSegmentL1 . " (".$semanticSegmentL2.") - " . $semanticL1;
                    $formFields['nextReview'] = Carbon::now()->timestamp;

                    //NextReview temporarily zero...
                    UserVocabulary::create($formFields);
                    $counter++;
                    }
                }

            }
        
            //Dont Forget to Set CurrentLevelImport to 1!
            UserLevel::where('user_id', auth()->id())->first()->update([
                'currentlevelimport' => 1,
            ]);
            
        }

        //What to Complete!
        $newSemantic = UserSemantic::where('user_id', auth()->id())
                                        ->where('level_id', $level->id)
                                        ->where('completion', 0)
                                        ->oldest('updated_at')->first();

        //The Semantic Table Data
        $semanticData = Semantic::where('id', $newSemantic->semantic_id)->first();

        //Review Contents for Later!
        $newLessons = UserVocabulary::where('user_id', auth()->id())
                                        ->where('level_id', $level->id)
                                        ->where('semantic_id', $newSemantic->semantic_id)
                                        ->where('memorizationLevel', 'basic0')
                                        ->oldest('updated_at')->get();

        //dd($newLessons);

        return view('vocabapp.semantic', [
            'newSemantic' => $newSemantic,
            'semanticData' => $semanticData,
            'newLessons' => $newLessons,
        ]);
    }

    public function result(Request $request){

        //Check if User has reached minimum level to next level!
        $userLevel = UserLevel::where('user_id', auth()->id())->first();
        $level = Level::where('leveltitle', $userLevel->currentlevel)->first();
        $currentLevelProgressTotal = 0;

        if ($level != null){
            $vocabularyLevelContent = Vocabulary::where('level_id', $level->id)->count();
        }
        

        //Defining the Parameters
        /*
        $reviewSetting = array([
            "basic0"  => array("nextLevel" => "basic1", "nextReview" => 60, 'nextLevelReview' =>120,),
            "basic1"  => array("nextLevel" => "basic2", "nextReview" => 120,'nextLevelReview' =>180,),
            "basic2"  => array("nextLevel" => "basic3", "nextReview" => 180,'nextLevelReview' =>240,),
            "basic3"  => array("nextLevel" => "basic4", "nextReview" => 240,'nextLevelReview' =>300,),
            "basic4"  => array("nextLevel" => "basic5", "nextReview" => 300,'nextLevelReview' =>300,),
            "basic5"  => array("nextLevel" => "intermediate1", "nextReview" => 300,'nextLevelReview' =>300,),
            "intermediate1"  => array("nextLevel" => "max", "nextReview" => 5000,'nextLevelReview' =>5000,),
        ]);
        */

        //Testing Version!
        $reviewSetting = array([
            "basic0"  => array("nextLevel" => "basic1", "nextReview" => 1, 'nextLevelReview' =>1,),
            "basic1"  => array("nextLevel" => "basic2", "nextReview" => 1,'nextLevelReview' =>1,),
            "basic2"  => array("nextLevel" => "basic3", "nextReview" => 1,'nextLevelReview' =>1,),
            "basic3"  => array("nextLevel" => "basic4", "nextReview" => 1,'nextLevelReview' =>1,),
            "basic4"  => array("nextLevel" => "basic5", "nextReview" => 1,'nextLevelReview' =>1,),
            "basic5"  => array("nextLevel" => "intermediate1", "nextReview" => 1,'nextLevelReview' =>1,),
            "intermediate1"  => array("nextLevel" => "max", "nextReview" => 1,'nextLevelReview' =>1,),
        ]);

        $reviewRecordBreak = json_decode($request->reviewRecordListArray, true);
        
        //Validation
        $validator = Validator::make($reviewRecordBreak, [
            '*.entry_id' => 'required|integer',
            '*.success_lang1' => 'required|integer',
            '*.success_lang2' => 'required|integer',
            '*.attempts_lang1' => 'required|integer',
            '*.attempts_lang2' => 'required|integer',
            '*.complete' => 'required|integer',
        ]);

        if($validator->fails()){
            abort(403, 'Unauthorized Action');
        }

        //Check The Values!
        foreach($reviewRecordBreak as $review){
            $idValidation = UserVocabulary::where('id', $review['entry_id'])->where('user_id', auth()->id())->first();
            if ($idValidation == null){
                //Fails because someone has changed the ID done something! Dont add it in and just go to the next one!
            } else {
                //First check if Attempt 1 and Success 1 make sense, to avoid people adjusting values.
                if ($review['attempts_lang1'] == 1 and $review['success_lang1'] == 1){
                    //Second check if Attempt 2 and Success 2 make sense!
                    if ($review['attempts_lang2'] == 1 and $review['success_lang2'] == 1){

                        //Use the ID Validation and Update through it!
                        $idValidation->attempts_lang1 = $idValidation->attempts_lang1 + $review['attempts_lang1'];
                        $idValidation->success_lang1 = $idValidation->success_lang1 + $review['success_lang1'];
                        $idValidation->attempts_lang2 = $idValidation->attempts_lang2 + $review['attempts_lang2'];
                        $idValidation->success_lang2 = $idValidation->success_lang2 + $review['success_lang2'];

                        //Next Memorization Level
                        if ($reviewSetting[0][$idValidation->memorizationLevel]['nextLevel'] == 'max'){
                            //Just update the time!
                            $idValidation->nextReview = Carbon::now()->timestamp + $reviewSetting[0][$idValidation->memorizationLevel]['nextLevelReview'];

                        } else if ($reviewSetting[0][$idValidation->memorizationLevel]['nextLevel'] == 'basic5'){

                            $idValidation->memorizationLevel = $reviewSetting[0][$idValidation->memorizationLevel]['nextLevel'];
                            $idValidation->nextReview = Carbon::now()->timestamp + $reviewSetting[0][$idValidation->memorizationLevel]['nextLevelReview'];

                            //Add to the UserLevel CurrentLevelProgress
                            if ($level != null){
                                $currentLevelProgressTotal = $currentLevelProgressTotal+1;
                            }

                        } else {

                            $idValidation->memorizationLevel = $reviewSetting[0][$idValidation->memorizationLevel]['nextLevel'];
                            $idValidation->nextReview = Carbon::now()->timestamp + $reviewSetting[0][$idValidation->memorizationLevel]['nextLevelReview'];
                        }

                        $idValidation->save();

                    } else {
                        //Use the ID Validation and Update through it!
                        $idValidation->attempts_lang1 = $idValidation->attempts_lang1 + $review['attempts_lang1'];
                        $idValidation->success_lang1 = $idValidation->success_lang1 + $review['success_lang1'];
                        $idValidation->attempts_lang2 = $idValidation->attempts_lang2 + $review['attempts_lang2'];
                        $idValidation->success_lang2 = $idValidation->success_lang2 + $review['success_lang2'];

                        //Stay at the Same Memorization Level
                        $idValidation->nextReview = Carbon::now()->timestamp + $reviewSetting[0][$idValidation->memorizationLevel]['nextReview'];
                        $idValidation->save();
                    }

                } else {
                    //Use the ID Validation and Update through it!
                    $idValidation->attempts_lang1 = $idValidation->attempts_lang1 + $review['attempts_lang1'];
                    $idValidation->success_lang1 = $idValidation->success_lang1 + $review['success_lang1'];
                    $idValidation->attempts_lang2 = $idValidation->attempts_lang2 + $review['attempts_lang2'];
                    $idValidation->success_lang2 = $idValidation->success_lang2 + $review['success_lang2'];

                    //Stay at the Same Memorization Level
                    $idValidation->nextReview = Carbon::now()->timestamp + $reviewSetting[0][$idValidation->memorizationLevel]['nextReview'];
                    $idValidation->save();
                }
            }
        }

        //Adds up all the progess to next level!
        if ($level != null){
                $userLevel->currentlevelprogress = $userLevel->currentlevelprogress + $currentLevelProgressTotal;

            if (($userLevel->currentlevelprogress + $currentLevelProgressTotal) >= $vocabularyLevelContent){
                //If the currentlevelprogress is equal or larger dan the count of vocabulary content on that level, you can move up!
                $userLevel->currentlevel = $userLevel->currentlevel+1;
                $userLevel->currentlevelprogress = 0;
                $userLevel->currentlevelimport = 0;
                $userLevel->save();
            } else {
                $userLevel->save();
            }
        }
        

        return redirect('/menu');
    }

    public function resultLesson(Request $request){
        //Testing Version!
        $reviewSetting = array([
            "basic0"  => array("nextLevel" => "basic1", "nextReview" => 1, 'nextLevelReview' =>1,),
            "basic1"  => array("nextLevel" => "basic2", "nextReview" => 1,'nextLevelReview' =>1,),
            "basic2"  => array("nextLevel" => "basic3", "nextReview" => 1,'nextLevelReview' =>1,),
            "basic3"  => array("nextLevel" => "basic4", "nextReview" => 1,'nextLevelReview' =>1,),
            "basic4"  => array("nextLevel" => "basic5", "nextReview" => 1,'nextLevelReview' =>1,),
            "basic5"  => array("nextLevel" => "intermediate1", "nextReview" => 1,'nextLevelReview' =>1,),
            "intermediate1"  => array("nextLevel" => "max", "nextReview" => 1,'nextLevelReview' =>1,),
        ]);

        $reviewRecordBreak = json_decode($request->reviewRecordListArray, true);
        
        //Validation
        $validator = Validator::make($reviewRecordBreak, [
            '*.entry_id' => 'required|integer',
            '*.success_lang1' => 'required|integer',
            '*.success_lang2' => 'required|integer',
            '*.attempts_lang1' => 'required|integer',
            '*.attempts_lang2' => 'required|integer',
            '*.complete' => 'required|integer',
        ]);

        if($validator->fails()){
            abort(403, 'Unauthorized Action');
        }

        //Check The Values!
        foreach($reviewRecordBreak as $review){
            $idValidation = UserVocabulary::where('id', $review['entry_id'])->where('user_id', auth()->id())->first();
            if ($idValidation == null){
                //Fails because someone has changed the ID done something! Dont add it in and just go to the next one!
            } else {
                //Use the ID Validation and Update through it!
                $idValidation->attempts_lang1 = $idValidation->attempts_lang1 + $review['attempts_lang1'];
                $idValidation->success_lang1 = $idValidation->success_lang1 + $review['success_lang1'];
                $idValidation->attempts_lang2 = $idValidation->attempts_lang2 + $review['attempts_lang2'];
                $idValidation->success_lang2 = $idValidation->success_lang2 + $review['success_lang2'];

                $idValidation->memorizationLevel = $reviewSetting[0][$idValidation->memorizationLevel]['nextLevel'];
                $idValidation->nextReview = Carbon::now()->timestamp + $reviewSetting[0][$idValidation->memorizationLevel]['nextLevelReview'];   
            }

            $idValidation->save();

            
        }

        return redirect('/menu');
    }

    public function resultSemantic(Request $request){
        
        //Testing Version!
        $reviewSetting = array([
            "basic0"  => array("nextLevel" => "basic1", "nextReview" => 1, 'nextLevelReview' =>1,),
            "basic1"  => array("nextLevel" => "basic2", "nextReview" => 1,'nextLevelReview' =>1,),
            "basic2"  => array("nextLevel" => "basic3", "nextReview" => 1,'nextLevelReview' =>1,),
            "basic3"  => array("nextLevel" => "basic4", "nextReview" => 1,'nextLevelReview' =>1,),
            "basic4"  => array("nextLevel" => "basic5", "nextReview" => 1,'nextLevelReview' =>1,),
            "basic5"  => array("nextLevel" => "intermediate1", "nextReview" => 1,'nextLevelReview' =>1,),
            "intermediate1"  => array("nextLevel" => "max", "nextReview" => 1,'nextLevelReview' =>1,),
        ]);

        $reviewRecordBreak = json_decode($request->reviewRecordListArray, true);
        
        //Validation
        $validator = Validator::make($reviewRecordBreak, [
            '*.entry_id' => 'required|integer',
            '*.success_lang1' => 'required|integer',
            '*.success_lang2' => 'required|integer',
            '*.attempts_lang1' => 'required|integer',
            '*.attempts_lang2' => 'required|integer',
            '*.complete' => 'required|integer',
        ]);

        if($validator->fails()){
            abort(403, 'Unauthorized Action');
        }

        //Storage to find userSemantic
        $userSemanticIDStorage = 0;
        $userSemanticAcquired = 0;

        //Check The Values!
        foreach($reviewRecordBreak as $review){
            $idValidation = UserVocabulary::where('id', $review['entry_id'])->where('user_id', auth()->id())->first();
            if ($idValidation == null){
                //Fails because someone has changed the ID done something! Dont add it in and just go to the next one!
            } else {
                //Use the ID Validation and Update through it!
                $idValidation->attempts_lang1 = $idValidation->attempts_lang1 + $review['attempts_lang1'];
                $idValidation->success_lang1 = $idValidation->success_lang1 + $review['success_lang1'];
                $idValidation->attempts_lang2 = $idValidation->attempts_lang2 + $review['attempts_lang2'];
                $idValidation->success_lang2 = $idValidation->success_lang2 + $review['success_lang2'];

                $idValidation->memorizationLevel = $reviewSetting[0][$idValidation->memorizationLevel]['nextLevel'];
                $idValidation->nextReview = Carbon::now()->timestamp + $reviewSetting[0][$idValidation->memorizationLevel]['nextLevelReview'];   
                
                if ($userSemanticAcquired == 0){
                    //Do this operation only once!
                    $userSemanticIDStorage = $idValidation->semantic_id;
                    $userSemanticAcquired = 1;
                }
            
            }

            $idValidation->save();
        }

        //Update the User Semantic Mapping!
        $userSemanticUpdate = userSemantic::where('semantic_id', $userSemanticIDStorage)->where('user_id', auth()->id())->first();
        $userSemanticUpdate->completion = 1;
        $userSemanticUpdate->save();

        return redirect('/menu');
    }

    public function review(){
        //Check user Level
        $userLevel = UserLevel::where('user_id', auth()->id())->first();
        $level = Level::where('leveltitle', $userLevel->currentlevel)->first();

        //Take 5 of them!
        $newReviews = UserVocabulary::where('user_id', auth()->id())
                                    ->where('memorizationLevel', '<>', 'basic0')
                                    ->where('memorizationLevel', '<>', 'max')
                                    ->where('nextReview', '<=', Carbon::now()->timestamp)
                                    ->oldest('updated_at')->take(12)->get();

        return view('vocabapp.review', [
        'newReviews' => $newReviews,
        ]);
    }
}



