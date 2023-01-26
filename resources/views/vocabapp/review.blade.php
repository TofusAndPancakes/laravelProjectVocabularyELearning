@extends('layout')

@section('content')
<script>
//Similarity Function, inspired by Levenshtein distance, Credit to overlord1234
//https://stackoverflow.com/questions/10473745/compare-strings-javascript-return-of-likely

function similarity(s1, s2) {
    var longer = s1;
    var shorter = s2;
    if (s1.length < s2.length) {
        longer = s2;
        shorter = s1;
    }
    var longerLength = longer.length;
    if (longerLength == 0) {
        return 1.0;
    }
    return (longerLength - editDistance(longer, shorter)) / parseFloat(longerLength);
}

function editDistance(s1, s2) {
    s1 = s1.toLowerCase();
    s2 = s2.toLowerCase();

    var costs = new Array();
    for (var i = 0; i <= s1.length; i++) {
    var lastValue = i;
    for (var j = 0; j <= s2.length; j++) {
        if (i == 0)
        costs[j] = j;
        else {
        if (j > 0) {
            var newValue = costs[j - 1];
            if (s1.charAt(i - 1) != s2.charAt(j - 1))
            newValue = Math.min(Math.min(newValue, lastValue),
                costs[j]) + 1;
            costs[j - 1] = lastValue;
            lastValue = newValue;
        }
        }
    }
    if (i > 0)
        costs[s2.length] = lastValue;
    }
    return costs[s2.length];
}

//Take the Array Content!
var newReviewPHP = @json($newReviews);
var newReviewList = [];
var reviewRecordList = [];

var reviewCounter = 0; //Initialized here because only needed once

//Make the Array into Lesson Array and Review Array
for (var i = 0; i <= {{count($newReviews)}}-1; i++) {

    //English to Indonesian
    const newReviewListPush = {
        language1: newReviewPHP[i]['language1'],
        language2: newReviewPHP[i]['language2'],
        mnemoniclist: newReviewPHP[i]['mnemoniclist'],
        semanticlist: newReviewPHP[i]['semanticlist'],
        test_type: 1,
        record_id: reviewCounter,
    }

    newReviewList.push(newReviewListPush);

    //Make Javascript Object
    //Indonesian to English
    const newReviewListPushInverse = {
        language1: newReviewPHP[i]['language1'],
        language2: newReviewPHP[i]['language2'],
        mnemoniclist: newReviewPHP[i]['mnemoniclist'],
        semanticlist: newReviewPHP[i]['semanticlist'],
        test_type: 2,
        record_id: reviewCounter,
    }

    newReviewList.push(newReviewListPushInverse);

    //Make A Review Record that Keeps track!
    const reviewRecordListPush = {
        entry_id: newReviewPHP[i]['id'],
        success_lang1: 0,
        success_lang2: 0,
        attempts_lang1: 0,
        attempts_lang2: 0,
        complete: 0,
    }

    reviewRecordList.push(reviewRecordListPush);
    
    reviewCounter = reviewCounter+1;
}


//Shuffle the newReviewList
//Sorting Script, Initialize every Variable!
var i = newReviewList.length; //Length
var j = 0; //RandomNumber
var arrayTemporary;

//Fisher Yates
while(--i > 0){
    j = Math.floor(Math.random() * (i+1));
    //Swamping the Arrays
    //Language 1
    arrayTemporary = newReviewList[j]['language1'];
    newReviewList[j]['language1'] = newReviewList[i]['language1'];
    newReviewList[i]['language1'] = arrayTemporary;

    //Language 2
    arrayTemporary = newReviewList[j]['language2'];
    newReviewList[j]['language2'] = newReviewList[i]['language2'];
    newReviewList[i]['language2'] = arrayTemporary;

    //MnemonicsList
    arrayTemporary = newReviewList[j]['mnemoniclist'];
    newReviewList[j]['mnemoniclist'] = newReviewList[i]['mnemoniclist'];
    newReviewList[i]['mnemoniclist'] = arrayTemporary;

    //SemanticList
    arrayTemporary = newReviewList[j]['semanticlist'];
    newReviewList[j]['semanticlist'] = newReviewList[i]['semanticlist'];
    newReviewList[i]['semanticlist'] = arrayTemporary;

    //Test Type
    arrayTemporary = newReviewList[j]['test_type'];
    newReviewList[j]['test_type'] = newReviewList[i]['test_type'];
    newReviewList[i]['test_type'] = arrayTemporary;

    //Record ID
    arrayTemporary = newReviewList[j]['record_id'];
    newReviewList[j]['record_id'] = newReviewList[i]['record_id'];
    newReviewList[i]['record_id'] = arrayTemporary;
}


/*
//Quick Examination in Console!
for (var i = 0; i < newReviewList.length; i++) {
    console.log(newReviewList[i]['test_type']);
    console.log(newReviewList[i]['language1']);
}

for (var i = 0; i < reviewRecordList.length; i++) {
    console.log(reviewRecordList[i]['complete']);
    console.log(reviewRecordList[i]['language1']);
}
*/

</script>

<style>
    .displayNoneStyle {
        visibility: hidden;
        opacity: 0;
        transition: visibility 0.3s, opacity 0.3s;
    }

    .displayStyle {
        visibility: visible !important;
        opacity: 1 !important;
    }

    .reviewQuickAccessDrop {
        height: auto !important;
        min-height: 30px !important;
    }

    .displayQuickAccessStyle {
        display: flex !important;
        visibility: visible !important;
        opacity: 1 !important;
    }

    .displayTutorialStyle {
      visibility: visible !important;
      opacity: 1 !important;
      display: flex;
    }

</style>
<div class="indexBodySection">
    <div class="indexBodyMarginColumnFull">
        <!-- Review Area -->
        <div id="reviewArea" class="reviewAreaSection">
            <div class="reviewQuestionSection">
                <div class="reviewAreaProgress">
                    <div class="reviewAreaProgressBar" id="progressBar">
                        &nbsp;
                    </div>
                    <p id="progressBarText">Progress</p>
                </div>

                <h2 id="questionArea"></h2>

                <div class="reviewAreaCorrection" id="reviewAreaCorrection">
                    <div class="reviewAreaCorrectionBox" id="reviewAreaCorrectionBox">
                        <p id="reviewAreaCorrectionText">Correct</p>
                    </div>
                    <div class="reviewAreaCorrectionNote">
                        <p>Click [Submit] to Continue</p>
                    </div>
                </div>
            </div>
            <div class="reviewInstructionSection">
                <p>Answer</p>
                <p id="resultArea"></p>
            </div>
            <form class="reviewAnswerSection" id="myForm">
                <div class="reviewAnswerInput">
                    <input class="reviewInput" id="answer" name="answer" value="" placeholder="Type Answer Here!">
                    <button class="reviewButton" type="submit"><p>Submit</p></button>
                </div>
            </form>
            <div class="reviewQuickAccessSection" id="reviewQuickAccessSection">
                <div class="reviewQuickAccess" id="reviewQuickAccess" onclick="revealQuickAccess()">
                    <p>Reveal Details</p>
                </div>
                <div class="reviewQuickAccessDropdown" id="reviewQuickAccessDropdown">
                    <p id="reviewQuickAccessText">Details of Mnemonics and Semantic Mapping are shown here!</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="reviewFinishSection" id="reviewModalArea">
    <div class="reviewFinishModal">
        <div class="reviewFinishModalText">
            <h1>Review Complete!</h1>
            <hr>
            <p>Please click [Submit Result] bellow to continue!</p>
        </div>
        <div class="reviewFinishModalButton">
            <form method="POST" action={{route('review.result')}}>
                @csrf
                <div class="form">
                    <input type="hidden" id="reviewRecordListArray" name="reviewRecordListArray" value="">
                    <div class="form">
                        <button class="formButton" type="submit">Submit Result</button>
                    </div>
                </div>
            </form>
        </div>
    </div>  
</div>


<!-- Review Tutorial -->
<div class="tutorialBoxModalSection" id="modalArea">
    <div class="tutorialBoxWideModal">
        <div class="tutorialBoxModalText">
        <h1>Review Tutorial</h1>
        <hr>
        <p>Welcome to a Review Session. In a Review Session, you will be shown Indonesian or English words. Your goal is to answer Indonesian with it's English counterpart
            and answer English with it's Indonesian counterpart.</p>
        <br>
        <p>Type your answer in the [Input] provided and once your are confident, press [Enter] or click [Submit] to check the answer. Aim to get all of your answers correct first try,
            but if you got it wrong, there are no penalties! If you have difficulties, you can click [Reveal Details] to see the Mnemonic or Semantic Mapping again.</p>
        <br>
        <p>Each Review session has 5 words you will recall. Once you have answered them all, follow the instructions on the pop up to [Submit Result]. 
            You will be redirected to [Main Menu] and you can start another Review Session.</p> 
        <br>
        </div>

        <div class="tutorialBoxModalButton">
        <button class="formButton tutorialBoxModalButtonMargin" onclick='tutorialBoxToggle()'>Ok!</button>
        </div>
    </div>
</div>

<script>
//Tutorial
const tutorial = document.getElementById('modalArea');
const tutorialClass = tutorial.classList;

function tutorialBoxToggle(){
    tutorialClass.toggle('displayTutorialStyle');
}

//Check if Tutorial is Enabled?
if (localStorage.getItem('vocabAppTutorial') == 0){
    tutorialBoxToggle(); 
}

//Progress Bar
const progressBar = document.getElementById('progressBar');
const progressBarText = document.getElementById('progressBarText');

var reviewCompletionProgress = 0;

//Updates the Bar on top of the Review, takes the reviewCounter Variable used at the start!
function progressBarUpdate(){
    //Update the Text
    progressBarText.textContent = reviewCompletionProgress + "/" + reviewCounter + " Progress" ;

    //Update the Visual
    var progressBarCalculation = (reviewCompletionProgress/reviewCounter)*100;
    progressBar.style.width = progressBarCalculation + "vw";
}

//Correction Result
const reviewAreaCorrection = document.getElementById('reviewAreaCorrection');
const reviewAreaCorrectionClass = reviewAreaCorrection.classList;

const reviewAreaCorrectionBox = document.getElementById('reviewAreaCorrectionBox');
const reviewAreaCorrectionText = document.getElementById('reviewAreaCorrectionText');

function reviewAreaCorrect(){
    reviewAreaCorrectionText.textContent = "CORRECT";
    reviewAreaCorrectionBox.style.backgroundColor = "#77f78e";
    reviewAreaCorrectionClass.toggle('displayStyle');
}

function reviewAreaIncorrect(){
    reviewAreaCorrectionText.textContent = "INCORRECT";
    reviewAreaCorrectionBox.style.backgroundColor = "#de3737";
    reviewAreaCorrectionClass.toggle('displayStyle');
}

function reviewAreaCorrectionReset(){
    reviewAreaCorrectionClass.toggle('displayStyle');
}

//Quick Access
const reviewQuickAccessSection = document.getElementById('reviewQuickAccessSection');

const reviewQuickAccess = document.getElementById('reviewQuickAccess');
const reviewQuickAccessClass = reviewQuickAccess.classList;

const reviewQuickAccessDropdown = document.getElementById('reviewQuickAccessDropdown');
const reviewQuickAccessDropdownClass = reviewQuickAccessDropdown.classList;

const reviewQuickAccessText = document.getElementById('reviewQuickAccessText');

//Quick Access state, if it's 1 then it is currently shown, if it's 0 then it is not shown.
var reviewQuickAccessState = 0;

function revealQuickAccess(){
    reviewQuickAccessDropdownClass.toggle('reviewQuickAccessDrop');
    if (reviewQuickAccessState == 0){
        reviewQuickAccessState = 1;
    } else {
        reviewQuickAccessState = 0;
    }
}

function revealQuickAccessReset(){
    if (reviewQuickAccessState == 1){
        reviewQuickAccessDropdownClass.toggle('reviewQuickAccessDrop');
        reviewQuickAccessState = 0;
    }
}

function revealQuickAccessReveal(){
    reviewQuickAccessClass.toggle('displayQuickAccessStyle');
}

//Tutorial 
/*
//Temporary Debugging
var reviewRecordResult = JSON.stringify(reviewRecordList);
document.getElementById('reviewRecordListArray').value = reviewRecordResult;
*/

// -- Review Script -- 
//Locate the Answer
const questionArea = document.getElementById('questionArea');
//Locate the Result
const resultArea = document.getElementById('resultArea');

//Locate the Completion Modal
const reviewModalArea = document.getElementById('reviewModalArea');
const reviewModalClass = reviewModalArea.classList;

//Count the Total of the Review Entries
const review_total = newReviewList.length;
var review_current = 0;

//User Reviewing (After Correct Set or After wrong Answer)
var review_pause_state = 0;

function nextEntry() {
//Reveal Details Addon
if (newReviewList[review_current]['mnemoniclist'] == "Semantic Lesson") {
    //Group 2 Students
    reviewQuickAccessText.textContent = newReviewList[review_current]['semanticlist'];
} else {
    //Group 1 and other Students
    reviewQuickAccessText.textContent = newReviewList[review_current]['mnemoniclist'];
}


//console.log("Test Type being checked  " + newReviewList[review_current]['test_type'] + " Review Current " + review_current);
if (newReviewList[review_current]['test_type'] == 1){
    //console.log(newReviewList[review_current]['language1']);
    //console.log(newReviewList[review_current]['language2']);
    questionArea.textContent = newReviewList[review_current]['language1'];
    //console.log(review_current);
    //console.log(newReviewList[review_current]['language1']);
} else if (newReviewList[review_current]['test_type'] == 2){
    questionArea.textContent = newReviewList[review_current]['language2'];
    //console.log(review_current);
    //console.log(newReviewList[review_current]['language1']);
}
}


//Get the Answer and Check the Answer
//Credit to Kevin Farrugia
//https://stackoverflow.com/questions/3547035/getting-html-form-values
const form_clear = document.getElementById('answer');

function getData(form) {
    var formData = new FormData(form);
    //console.log("Test Type being checked  " + newReviewList[review_current]['test_type'] + "Test Type being checked  " + newReviewList[review_current]['test_type'] + " Review Current " + review_current);

    //Check if Value is Correct
    if (newReviewList[review_current]['test_type'] == 1){
    var similarity_result = similarity(newReviewList[review_current]['language2'],formData.get("answer"));
    //console.log("Check the Answer! " + formData.get("answer") + " Answer Sheet " + newReviewList[review_current]['language2'] + " Percentage " + similarity_result);

    } else if (newReviewList[review_current]['test_type'] == 2){
    var similarity_result = similarity(newReviewList[review_current]['language1'],formData.get("answer"));
    //console.log("Check the Answer! " + formData.get("answer") + " Answer Sheet " + newReviewList[review_current]['language1'] + " Percentage " + similarity_result);
    }
    
    //Get the Review Record
    var record_id = newReviewList[review_current]['record_id'];

    //Record the Attempt
    if (newReviewList[review_current]['test_type'] == 1){
    reviewRecordList[record_id]['attempts_lang1'] = reviewRecordList[record_id]['attempts_lang1'] + 1;
    } else if (newReviewList[review_current]['test_type'] == 2){
    reviewRecordList[record_id]['attempts_lang2'] = reviewRecordList[record_id]['attempts_lang2'] + 1;
    }

    if (similarity_result > 0.80){
        //console.log("correct");
        //Check if you have completed a set?
        
        //If you have not gotten any answers correct yet
        if (reviewRecordList[record_id]['success_lang1'] == 0 && reviewRecordList[record_id]['success_lang2'] == 0){
        //console.log("Case1");
        //If you are correct on language 1
        if (newReviewList[review_current]['test_type'] == 1){
            
            reviewRecordList[record_id]['success_lang1'] = 1;
            //console.log("Case1 - Test Type 1" + reviewRecordList[record_id]['success_lang1'] + reviewRecordList[record_id]['success_lang2']);

        } else if (newReviewList[review_current]['test_type'] == 2){
            //Else if you are correct on language 2
            reviewRecordList[record_id]['success_lang2'] = 1;
            //console.log("Case1 - Test Type 2" + reviewRecordList[record_id]['success_lang1'] + reviewRecordList[record_id]['success_lang2']);

        }
        } else if (reviewRecordList[record_id]['success_lang1'] == 1 || reviewRecordList[record_id]['success_lang2'] == 1) {
            console.log("Case2");
            //If you have one of them correct already, that means success!
            if (newReviewList[review_current]['test_type'] == 1){
            
            reviewRecordList[record_id]['success_lang1'] = 1;
            //console.log("Case2 - Test Type 1" + reviewRecordList[record_id]['success_lang1'] + reviewRecordList[record_id]['success_lang2']);

            } else if (newReviewList[review_current]['test_type'] == 2){
            //Else if you are correct on language 2
            reviewRecordList[record_id]['success_lang2'] = 1;
            //console.log("Case2 - Test Type 2" + reviewRecordList[record_id]['success_lang1'] + reviewRecordList[record_id]['success_lang2']);
            }
            
            //If you got both answers correct 
            if (reviewRecordList[record_id]['success_lang1'] == 1 && reviewRecordList[record_id]['success_lang1'] == 1) {
            //Did you get them correctly consecutively?
            if (reviewRecordList[record_id]['attempts_lang1'] > 1 || reviewRecordList[record_id]['attempts_lang2'] > 1) {
                //If you got one of them wrong, count as fail... (Took multiple attempts)
                //console.log("Case3 - Sorry...");
                reviewRecordList[record_id]['complete'] = 0;
            } else {
                //console.log("Case3 - Success! You got it right!");
                reviewRecordList[record_id]['complete'] = 1;
            }

            }
            
            review_pause_state = 1;
            reviewAreaCorrect();
            reviewCompletionProgress++;
            progressBarUpdate();
            revealQuickAccessReveal()

        }

    } else {
        //If the answer is wrong!
        //Goals, copy this entry to the end of the array
        
        const newReviewListPush = {
        language1: newReviewList[review_current]['language1'],
        language2: newReviewList[review_current]['language2'],
        mnemoniclist: newReviewList[review_current]['mnemoniclist'],
        semanticlist: newReviewList[review_current]['semanticlist'],
        test_type: newReviewList[review_current]['test_type'],
        record_id: newReviewList[review_current]['record_id'],
        }

        newReviewList.push(newReviewListPush);

        //Initiated Failed Pause State
        review_pause_state = 2;
        reviewAreaIncorrect();
        revealQuickAccessReveal()

    }

    if (review_pause_state > 0) {
    //Dont do anything, we dont want to remove the text!
    } else {
        review_current++;
    if (review_current < newReviewList.length){
        //If it is not the last one!
        nextEntry();
    } else {
        //console.log("Stop!");
        endReview();
    }

    resultArea.textContent = "";
    form_clear.value= "";
    }
}

function getCorrect(form) {
    var formData = new FormData(form);

    resultArea.textContent = "";
    form_clear.value= "";
    
    //If review_pause_state = 1, means correct answer
    if (review_pause_state == 1 ){

    
    } else if (review_pause_state == 2){
    //If review_pause_state = 2, means incorrect answer
    }

    //Turn off Pause State
    review_pause_state = 0;

    //Continue the Reviews like usual
    review_current++;
    if (review_current < newReviewList.length){
    //If it is not the last one!
    nextEntry();
    } else {
        //console.log("Stop!");
        review_state = 0;

        reviewModalClass.toggle('reviewFinishSectionDisplay');

        var reviewRecordResult = JSON.stringify(reviewRecordList);
        document.getElementById('reviewRecordListArray').value = reviewRecordResult;
    }
}

function endReview() {
    //Quick Tester for Values
    while(i < reviewRecordList.length){
    console.log("Language1 = " + reviewRecordList[i]['language1']);
    console.log("Success_lang1 = " + reviewRecordList[i]['success_lang1']);
    console.log("Success_lang2 = " + reviewRecordList[i]['success_lang2']);
    console.log("Attempts_lang1 = " + reviewRecordList[i]['attempts_lang1']);
    console.log("Attempts_lang2 = " + reviewRecordList[i]['attempts_lang2']);
    console.log("Complete = " + reviewRecordList[i]['complete']);
    i++;
    }

}

document.getElementById("myForm").addEventListener("submit", function (event) {
    event.preventDefault();
    //If it is in paused mode
    if (review_pause_state > 0){
        //Enter custom code!
        //console.log("getCorrect() Triggered");
        reviewAreaCorrectionReset();
        revealQuickAccessReset()
        revealQuickAccessReveal();
        getCorrect(event.target);
        } else {
        //Else if it is not in pause mode, then it's next entry time.
        //console.log("getData() Triggered");
        getData(event.target);
        }
});

nextEntry();
progressBarUpdate();

</script>
@endsection