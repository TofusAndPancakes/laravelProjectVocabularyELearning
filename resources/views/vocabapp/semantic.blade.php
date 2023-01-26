@extends('layout')

@section('content')

<script>
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

//Organizing Review Data for Mindmap
var newLessonSemanticPHP = @json($semanticData);
var newLessonPHP = @json($newLessons);

var newLessonSemanticTitleL1;
var newLessonSemanticTitleL2;
var newLessonSemanticList = [];
var newReviewList = [];
var reviewRecordList = [];

var reviewCounter = 0; //Initialized here because only needed once
var internalCounter = 0;

//Splitting the Semantic Lesson
var regexSetting = /\s*(?:,|$)\s*/;

/*Coder's note, when writing Mnemonic Lesson, Language 1 and language 2 is swapped because of a misunderstanding, 
I did properly here now but I have to swap it because of the misunderstanding before.. */

var semanticCategoryLanguage1List = newLessonSemanticPHP['semanticlanguage2'];
var semanticCategoryLanguage2List = newLessonSemanticPHP['semanticlanguage1'];

var semanticCategoryLanguage1ListSplit = semanticCategoryLanguage1List.split(regexSetting);
var semanticCategoryLanguage2ListSplit = semanticCategoryLanguage2List.split(regexSetting);

/*
for (var i = 0; i <= semanticCategoryLanguage1ListSplit.length - 1; i++) {
    console.log(semanticCategoryLanguage1ListSplit[i]);
}
console.log(semanticCategoryLanguage1ListSplit[11]);
*/

var newLessonSemanticTopicContainer;
var newLessonSemanticLanguage1Container = "";
var newLessonSemanticLanguage2Container = "";
var newLessonSemanticListIDContainer = "";

for (var i = 0; i < semanticCategoryLanguage1ListSplit.length; i++) {
    
    console.log(semanticCategoryLanguage1ListSplit[i]);

    //Check if it's SemanticTitle
    if (semanticCategoryLanguage1ListSplit[i] == "SemanticTitle") {
        newLessonSemanticTitleL1 = semanticCategoryLanguage1ListSplit[i + 1];
        newLessonSemanticTitleL2 = semanticCategoryLanguage2ListSplit[i + 1];
        //console.log(newLessonSemanticTitle);
        //Skip 2 iterations due to how Information is stored!
        i = i + 2; //Add two entry!
    }

    //Check if it's Semantic Category
    if (semanticCategoryLanguage1ListSplit[i] == "SemanticSegment") {
        //Update the Container
        newLessonSemanticTopicL1Container = semanticCategoryLanguage1ListSplit[i + 1];
        newLessonSemanticTopicL2Container = semanticCategoryLanguage2ListSplit[i + 1];
        //console.log(newLessonSemanticTopicContainer);
        //Skip 2 iterations due to how Information is stored!
        i = i + 2; //Add two entry!

        internalCounter = 0;
        
        //While Loop here needs Seperator to end every Segment or else cause Infinite loop, not sure how to solve yet, BECAREFUL!
        while (semanticCategoryLanguage1ListSplit[i] != "Seperator" && semanticCategoryLanguage1ListSplit[i] != 'undefined'){

            if (internalCounter == 0){
                //Reset the Container!
                newLessonSemanticLanguage1Container = semanticCategoryLanguage1ListSplit[i];
                newLessonSemanticLanguage2Container = semanticCategoryLanguage2ListSplit[i];
                newLessonSemanticListIDContainer = reviewCounter;
            } else {
                newLessonSemanticLanguage1Container = newLessonSemanticLanguage1Container + ", " + semanticCategoryLanguage1ListSplit[i];
                newLessonSemanticLanguage2Container = newLessonSemanticLanguage2Container + ", " + semanticCategoryLanguage2ListSplit[i];
                newLessonSemanticListIDContainer = newLessonSemanticListIDContainer + ", " + reviewCounter;
            }
            

            //English to Indonesian
            const newReviewListPush = {
                language1: semanticCategoryLanguage1ListSplit[i],
                language2: semanticCategoryLanguage2ListSplit[i],
                semanticlist: newLessonPHP[reviewCounter]["semanticlist"],
                test_type: 1,
                record_id: reviewCounter,
            }

            newReviewList.push(newReviewListPush);

            //Make Javascript Object
            //Indonesian to English
            const newReviewListPushInverse = {
                language1: semanticCategoryLanguage1ListSplit[i],
                language2: semanticCategoryLanguage2ListSplit[i],
                semanticlist: newLessonPHP[reviewCounter]["semanticlist"],
                test_type: 2,
                record_id: reviewCounter,
            }

            newReviewList.push(newReviewListPushInverse);

            //Make A Review Record that Keeps track!
            const reviewRecordListPush = {
                entry_id: newLessonPHP[reviewCounter]["id"],
                success_lang1: 0,
                success_lang2: 0,
                attempts_lang1: 0,
                attempts_lang2: 0,
                complete: 0,
            }

            reviewRecordList.push(reviewRecordListPush);

            reviewCounter = reviewCounter + 1;
            internalCounter = internalCounter + 1;
            i++;


            //Emergency Loop Stopper!
            if (i >= semanticCategoryLanguage1ListSplit[i].split){
                break;
            }
        }

        //Make SemanticCategory!

        const semanticCategoryPush = {
            semanticcategoryL1: newLessonSemanticTopicL1Container,
            semanticcategoryL2: newLessonSemanticTopicL2Container,
            language1: newLessonSemanticLanguage1Container,
            language2: newLessonSemanticLanguage2Container,
            contentid: newLessonSemanticListIDContainer,
        }

        newLessonSemanticList.push(semanticCategoryPush);
    }

    //console.log(i);
    //console.log(semanticCategoryLanguage1ListSplit[i]);
}



//Shuffle the newReviewList
//Sorting Script, Initialize every Variable!
var i = newReviewList.length; //Length
var j = 0; //RandomNumber
var arrayTemporary;

//Fisher Yates
while (--i > 0) {
    j = Math.floor(Math.random() * (i + 1));
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


//ConsoleLogging the Arrays to See!
/*
for (var i = 0; i <= newLessonSemanticList.length - 1; i++) {
    console.log(i);
    console.log(newLessonSemanticList[i]['semanticcategory']);
    console.log(newLessonSemanticList[i]['language1']);
    console.log(newLessonSemanticList[i]['language2']);
    console.log(newLessonSemanticList[i]['contentid']);
}
*/
for (var i = 0; i <= newReviewList.length - 1; i++) {
    console.log(i);
    console.log(newReviewList[i]['language1']);
    console.log(newReviewList[i]['language2']);
    console.log(newReviewList[i]['semanticlist']);
    console.log(newReviewList[i]['record_id']);
}
/*
for (var i = 0; i <= reviewRecordList.length - 1; i++) {
    console.log(i);
    console.log(reviewRecordList[i]['entry_id']);
}
*/
</script>

</head>
<style>
    .displayNoneStyle {
        visibility: hidden;
        opacity: 0;
        display: none;
        transition: visibility 0.3s, opacity 0.3s;
    }

    .displayStyle {
        visibility: visible !important;
        opacity: 1 !important;
    }

    .displaySemanticStyle {
        display: block !important;
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
    <div class="indexBodyMarginRowFull" id="semanticArea">
        <!-- Semantic Mapping -->
        <div class="reviewAreaProgress">
            <div class="reviewAreaProgressBar" id="semanticProgressBar">
                &nbsp;
            </div>
            <p id="semanticProgressBarText">Progress</p>
        </div>
        <div class="semanticVocabList" id="vocabSegment">
            <div class="semanticVocabListTitle"><p>Vocabulary List</p></div>
        </div>

        <div class="semanticMappingArea" id="semanticMappingSegment">
            <div class="semanticShowClues">
                <p class="formButton" onclick="toggleClues()">Show Clues</p>
            </div>
            <div class="semanticStartReview" id="semanticStartReview">
                <p class="formButton" onclick="loadReviewShortcut()">Start Review</p>
            </div>
            <div class="semanticMappingCenter">
                <p id="semanticMappingTitle"></p>
                <hr>
                <p class="semanticMappingCenterSubtitle">Semantic Topic</p>
                <!-- Semantic Mapping Center Content -->
                <div class="semanticMappingNodes" id="nodeSM1">
                    <p id="categorySM1"></p>
                    <hr>
                    <form id="formSM1">
                        <label><span class="semanticMappingCenterSubtitle">Semantic Category<span><input id="answerSM1" name="answer" value="" placeholder="Type Indonesian Here!" class="semanticMappingNodesInput"></label>
                        <input class="semanticButton" type="submit" value="Check">
                    </form>
                    <div class="semanticMappingClues" id="clueSM1">
                        <p>Clues</p>
                        <hr>
                    </div>
                    <div class="semanticMappingReviewCorrectionBox" id="reviewAreaCorrectionBox1">
                        <p id="reviewAreaCorrectionText1">Correct</p>
                    </div>
                </div>
                <div class="semanticMappingNodes" id="nodeSM2">
                    <p id="categorySM2"></p>
                    <hr>
                    <form id="formSM2">
                        <label><span class="semanticMappingCenterSubtitle">Semantic Category<span><input id="answerSM2" name="answer" value="" placeholder="Type Indonesian Here!" class="semanticMappingNodesInput"></label>
                        <input class="semanticButton" type="submit" value="Check" placeholder="Type Relevant Vocabulary Here!">
                    </form>
                    <div class="semanticMappingClues" id="clueSM2">
                        <p>Clues</p>
                        <hr>
                    </div>
                    <div class="semanticMappingReviewCorrectionBox" id="reviewAreaCorrectionBox2">
                        <p id="reviewAreaCorrectionText2">Correct</p>
                    </div>
                </div>
                <div class="semanticMappingNodes" id="nodeSM3">
                    <p id="categorySM3"></p>
                    <hr>
                    <form id="formSM3">
                        <label><span class="semanticMappingCenterSubtitle">Semantic Category<span><input id="answerSM3" name="answer" value="" placeholder="Type Indonesian Here!" class="semanticMappingNodesInput"></label>
                        <input class="semanticButton" type="submit" value="Check">
                    </form>
                    <div class="semanticMappingClues" id="clueSM3">
                        <p>Clues</p>
                        <hr>
                    </div>
                    <div class="semanticMappingReviewCorrectionBox" id="reviewAreaCorrectionBox3">
                        <p id="reviewAreaCorrectionText3">Correct</p>
                    </div>
                </div>
                <div class="semanticMappingNodes" id="nodeSM4">
                    <p id="categorySM4"></p>
                    <hr>
                    <form id="formSM4">
                        <label><span class="semanticMappingCenterSubtitle">Semantic Category<span><input id="answerSM4" name="answer" value="" placeholder="Type Indonesian Here!" class="semanticMappingNodesInput"></label>
                        <input class="semanticButton" type="submit" value="Check">
                    </form>
                    <div class="semanticMappingClues" id="clueSM4">
                        <p>Clues</p>
                        <hr>
                    </div>
                    <div class="semanticMappingReviewCorrectionBox" id="reviewAreaCorrectionBox4">
                        <p id="reviewAreaCorrectionText4">Correct</p>
                    </div>
                </div>
                <div class="semanticMappingNodes" id="nodeSM5">
                    <p id="categorySM5"></p>
                    <hr>
                    <form id="formSM5">
                        <label><span class="semanticMappingCenterSubtitle">Semantic Category<span><input id="answerSM5" name="answer" value="" placeholder="Type Indonesian Here!" class="semanticMappingNodesInput"></label>
                        <input class="semanticButton" type="submit" value="Check">
                    </form>
                    <div class="semanticMappingClues" id="clueSM5">
                        <p>Clues</p>
                        <hr>
                    </div>
                    <div class="semanticMappingReviewCorrectionBox" id="reviewAreaCorrectionBox5">
                        <p id="reviewAreaCorrectionText5">Correct</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Review Section -->
    <div class="indexBodyMarginColumnFull displayNoneStyle" id="reviewArea">
        <div class="reviewAreaSection">
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
                    <button class="reviewButton" type="submit">
                        <p>Submit</p>
                    </button>
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

<div class="lessonFinishSection" id="modalArea" class="displayNoneStyle">
    <div class="lessonFinishModal">
        <div class="lessonFinishModalText">
            <h1>Semantic Lesson Complete!</h1>
            <hr>
            <p>Are you ready for the Review?</p>
            <br>
            <p>
            Clicking [No] will return you to your Semantic Mapping Session and you can click [Start Review] to start your review any time.</p>
            </p>
        </div>

        <div class="lessonFinishModalButton">
            <button class="formButton lessonFinishModalButtonMargin" onclick='loadReviewCancel()'>No</button>
            <button class="formButton lessonFinishModalButtonMargin" onclick='loadReview()'>Yes</button>
        </div>
    </div>
</div>


<!-- Review Finish Section -->
<div class="reviewFinishSection" id="reviewModalArea">
    <div class="reviewFinishModal">
        <div class="reviewFinishModalText">
            <h1>Review Complete!</h1>
            <hr>
            <p>Please press [Submit Result] bellow to continue!</p>
        </div>
        <div class="reviewFinishModalButton">
            <form method="POST" action={{route('semantic.result')}}>
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
</form>

<!-- Semantic Tutorial -->
<div class="tutorialBoxModalSection" id="modalAreaSemantic">
    <div class="tutorialBoxWideModal">
        <div class="tutorialBoxModalText">
            <h1>Lesson Tutorial (Semantic)</h1>
            <hr>
            <p>Welcome to a Semantic Session. In a Semantic Session, you will be given a vocabulary list to your left. Your goal is to write all
                the words in the vocabulary list into the mapping in the center of the website in it's relevant category.</p>
            <br>
            <p>Click [Check] to check if the vocabulary word you typed in that category is correct. If it is correct, the word will turn [Green] on the vocabulary list.
                If there are multiple words for that category remaning or you got it wrong in that category, the mapping will ask you to [Check Again].
            </p>
            <br>
            <p>You can also click [Show Clues] to see the English vocabulary words for each category if you have difficulties.</p>
            <br>
            <p>Once you have inputed all of the vocabulary in the vocabulary list correctly, follow the instructions in the pop up and click [Yes] when you are ready.</p>
        </div>

        <div class="tutorialBoxModalButton">
            <button class="formButton tutorialBoxModalButtonMargin" onclick='tutorialSemanticBoxToggle()'>Ok!</button>
        </div>
    </div>
</div>

<!-- Review Tutorial -->
<div class="tutorialBoxModalSection" id="modalAreaReview">
    <div class="tutorialBoxWideModal">
        <div class="tutorialBoxModalText">
            <h1>Review Tutorial</h1>
            <hr>
            <p>Welcome to a Review Session. In a Review Session, you will be shown Indonesian or English words. Your
                goal is to answer Indonesian with it's English counterpart
                and answer English with it's Indonesian counterpart.</p>
            <br>
            <p>Type your answer in the [Input] provided and once your are confident, press [Enter] or click [Submit] to check the answer. Aim to get all of your answers correct first try,
            but if you got it wrong, there are no penalties! If you have difficulties, you can click [Reveal Details] to see the Mnemonic or Semantic Mapping again.</p>  
            <br>
            <p>Each Review session has 5 words you will recall. Once you have answered them all, follow the instructions
                on the pop up to [Submit Result].
                You will be redirected to [Main Menu] and you can start another Review Session.</p>
            <br>
        </div>

        <div class="tutorialBoxModalButton">
            <button class="formButton tutorialBoxModalButtonMargin" onclick='tutorialReviewBoxToggle()'>Ok!</button>
        </div>
    </div>
</div>

<script>

//Semantic Tutorial
const tutorialSemantic = document.getElementById('modalAreaSemantic');
const tutorialSemanticClass = tutorialSemantic.classList;

function tutorialSemanticBoxToggle(){
    tutorialSemanticClass.toggle('displayTutorialStyle');
}

//Review Tutorial
const tutorialReview = document.getElementById('modalAreaReview');
const tutorialReviewClass = tutorialReview.classList;

function tutorialReviewBoxToggle(){
    tutorialReviewClass.toggle('displayTutorialStyle');
}


//Semantic Section
//Update the Title
const semanticMappingTitle = document.getElementById('semanticMappingTitle');
semanticMappingTitle.textContent = newLessonSemanticTitleL2 + " (" + newLessonSemanticTitleL1 + ")";

//Semantic Progress Bar
const semanticProgressBar = document.getElementById('semanticProgressBar');
const semanticProgressBarText = document.getElementById('semanticProgressBarText');

var semanticCounter = newLessonSemanticList.length;
var semanticCompletionProgress = 0;

//Updates the Bar on top of the Review, takes the reviewCounter Variable used at the start!
function semanticProgressBarUpdate() {
    //Update the Text
    semanticProgressBarText.textContent = semanticCompletionProgress + "/" + semanticCounter + " Progress";

    //Update the Visual
    var progressBarCalculation = (semanticCompletionProgress / semanticCounter) * 100;
    semanticProgressBar.style.width = progressBarCalculation + "vw";

    //Progress Bar checks if all of the Semantic Segments are Correct, if so, trigger the Load Review!
    if (semanticCompletionProgress >= semanticCounter){
            readyReviewClass.toggle('lessonFinishSectionDisplay');
            readyReviewModalOpen = 1;
    }
}

semanticProgressBarUpdate();

const clueSM1 = document.getElementById('clueSM1');
const clueSM2 = document.getElementById('clueSM2');
const clueSM3 = document.getElementById('clueSM3');
const clueSM4 = document.getElementById('clueSM4');
const clueSM5 = document.getElementById('clueSM5');

const clueSM1Class = clueSM1.classList;
const clueSM2Class = clueSM2.classList;
const clueSM3Class = clueSM3.classList;
const clueSM4Class = clueSM4.classList;
const clueSM5Class = clueSM5.classList;

//Semantic Mapping Clues
function toggleClues() {
    clueSM1Class.toggle('displaySemanticStyle');
    clueSM2Class.toggle('displaySemanticStyle');
    clueSM3Class.toggle('displaySemanticStyle');
    clueSM4Class.toggle('displaySemanticStyle');
    clueSM5Class.toggle('displaySemanticStyle');
}

//Vocab Segment
const vocabSegment = document.getElementById('vocabSegment');

for (var p = 0; p < {{count($newLessons)}}; p++) {
    vocabSegment.insertAdjacentHTML('beforeend',
    `<div class="semanticVocabListContent" id="vocabSegmentList`+ p + `">
        
        <p><span class="semanticVocabLang">IND </span>`+ newLessonPHP[p]["language1"] +`</p>
        <hr>
        <p><span class="semanticVocabLang">ENG </span>`+ newLessonPHP[p]["language2"] +`</p></div>`
    );
}

//Check Semantic Title
const semanticMappingSegment = document.getElementById('semanticMappingSegment');
const semanticMappingCenter = document.getElementById('semanticMappingCenter');

//Collect Semantic Mapping Nodes, Maximum 5
const nodeSM1 = document.getElementById('nodeSM1');
const nodeSM2 = document.getElementById('nodeSM2');
const nodeSM3 = document.getElementById('nodeSM3');
const nodeSM4 = document.getElementById('nodeSM4');
const nodeSM5 = document.getElementById('nodeSM5');

//Collect Semantic Mapping Input Nodes, Maximum 5
const categorySM1 = document.getElementById('categorySM1');
const categorySM2 = document.getElementById('categorySM2');
const categorySM3 = document.getElementById('categorySM3');
const categorySM4 = document.getElementById('categorySM4');
const categorySM5 = document.getElementById('categorySM5');

//Semantic Mapping Nodes Checker
var checkerSM1;
var checkerSM2;
var checkerSM3;
var checkerSM4;
var checkerSM5;

var checkerSM1VocabID;
var checkerSM2VocabID;
var checkerSM3VocabID;
var checkerSM4VocabID;
var checkerSM5VocabID;


//Semantic Mapping Correct Noter, 0 is Incomplete, 1 is Complete
var correctSM1 = 0;
var correctSM2 = 0;
var correctSM3 = 0;
var correctSM4 = 0;
var correctSM5 = 0;

var formAnswer;

//Semantic Mapping Correction Classes

const reviewAreaCorrectionBox1 = document.getElementById('reviewAreaCorrectionBox1');
const reviewAreaCorrectionBox1Class = reviewAreaCorrectionBox1.classList;

const reviewAreaCorrectionBox2 = document.getElementById('reviewAreaCorrectionBox2');
const reviewAreaCorrectionBox2Class = reviewAreaCorrectionBox2.classList;

const reviewAreaCorrectionBox3 = document.getElementById('reviewAreaCorrectionBox3');
const reviewAreaCorrectionBox3Class = reviewAreaCorrectionBox3.classList;

const reviewAreaCorrectionBox4 = document.getElementById('reviewAreaCorrectionBox4');
const reviewAreaCorrectionBox4Class = reviewAreaCorrectionBox4.classList;

const reviewAreaCorrectionBox5 = document.getElementById('reviewAreaCorrectionBox5');
const reviewAreaCorrectionBox5Class = reviewAreaCorrectionBox5.classList;

const reviewAreaCorrectionText1 = document.getElementById('reviewAreaCorrectionText1');
const reviewAreaCorrectionText2 = document.getElementById('reviewAreaCorrectionText2');
const reviewAreaCorrectionText3 = document.getElementById('reviewAreaCorrectionText3');
const reviewAreaCorrectionText4 = document.getElementById('reviewAreaCorrectionText4');
const reviewAreaCorrectionText5 = document.getElementById('reviewAreaCorrectionText5');


//State of the Web Application (Review and when Semantic Lesson Ends)
var lesson_state = 1;
var review_state = 0;
var readyReviewModalOpen = 0;

const readyReview = document.getElementById('modalArea');
const readyReviewClass = readyReview.classList;

const semanticArea = document.getElementById('semanticArea');
const semanticAreaClass = semanticArea.classList;

//Review Variables just to Prepare!
//Locate the Answer
const questionArea = document.getElementById('questionArea');
//Locate the Result
const resultArea = document.getElementById('resultArea');
//Located the ReviewArea (Special for Lesson!)
const reviewArea = document.getElementById('reviewArea');
const reviewAreaClass = reviewArea.classList;

const reviewModal = document.getElementById('reviewModalArea');
const reviewModalClass = reviewModal.classList;

//Load Review
const semanticStartReview = document.getElementById('semanticStartReview');

//Ending Semantic Segment
function loadReviewCancel() {
    readyReviewClass.toggle('lessonFinishSectionDisplay');
    readyReviewModalOpen = 0;

    //Show LoadReviewShortcut
    semanticStartReview.style.display = "block";
    //console.log("Cancelling");
}

function loadReview() {
    readyReviewClass.toggle('lessonFinishSectionDisplay');
    lesson_state = 0;
    //lessonStateEnd();

    review_state = 1;
    console.log("Starting Review!");

    //Initializing Review
    semanticAreaClass.toggle('displayNoneStyle');
    reviewAreaClass.toggle('displayNoneStyle');
    nextEntry();
    progressBarUpdate();

    //Check if Tutorial is Enabled?
    if (localStorage.getItem('vocabAppTutorial') == 0) {
        tutorialReviewBoxToggle();
    }

}

//Shortcut Version opened using Start Review Button
function loadReviewShortcut(){
    review_state = 1;
    console.log("Starting Review!");

    //Initializing Review
    semanticAreaClass.toggle('displayNoneStyle');
    reviewAreaClass.toggle('displayNoneStyle');
    nextEntry();
    progressBarUpdate();

    //Check if Tutorial is Enabled?
    if (localStorage.getItem('vocabAppTutorial') == 0) {
        tutorialReviewBoxToggle();
    }
}


//Count how many Categories?
const semanticMappingCategoryTotal = newLessonSemanticList.length;
var semanticMappingCalcDiff = 6.28/semanticMappingCategoryTotal;

for (var p = 0; p < newLessonSemanticList.length; p++){

    //Split the Variables
    var SMContent = newLessonSemanticList[p]['language1'].split(",");

    //Calculate the Coordinates
    if (semanticMappingCategoryTotal == "1"){
        var SMSin = Math.sin(p*semanticMappingCalcDiff) * 200 - 9;
        var SMCos = Math.cos(p*semanticMappingCalcDiff) * 160 - 95;
    } else if (semanticMappingCategoryTotal == "2"){
        var SMSin = Math.sin(p*semanticMappingCalcDiff) * 200 - 9;
        var SMCos = Math.cos(p*semanticMappingCalcDiff) * 160 - 75;
    } else if (semanticMappingCategoryTotal == "3") {
        var SMSin = Math.sin(p*semanticMappingCalcDiff) * 200 - 9;
        var SMCos = Math.cos(p*semanticMappingCalcDiff) * 200 - 120; 
    } else if (semanticMappingCategoryTotal == "4") {
        var SMSin = Math.sin(p*semanticMappingCalcDiff) * 200 - 9;
        var SMCos = Math.cos(p*semanticMappingCalcDiff) * 180 - 65; 
    } else if (semanticMappingCategoryTotal == "5") {
        var SMSin = Math.sin(p*semanticMappingCalcDiff) * 200 - 9;
        var SMCos = Math.cos(p*semanticMappingCalcDiff) * 200 - 80; 
    }
    

    if (p == 0){
        //Insert and Activate the DIV
        categorySM1.textContent = newLessonSemanticList[p]["semanticcategoryL2"] + " (" + newLessonSemanticList[p]["semanticcategoryL1"] + ")";
        nodeSM1.style.display = 'block';

        nodeSM1.style.transform = "translate(" + SMSin + "px," + SMCos + "px)";

        //Insert the Checker Value!
        checkerSM1 = newLessonSemanticList[p]['language2'];
        checkerSM1VocabID = newLessonSemanticList[p]['contentid'];

        for (var i = 0; i < SMContent.length; i++) {
            clueSM1.insertAdjacentHTML('beforeend',
                `<p>` + SMContent[i] + `</p>`
            );
        };

    } else if (p == 1){
        //Insert and Activate the DIV
        categorySM2.textContent = newLessonSemanticList[p]["semanticcategoryL2"] + " (" + newLessonSemanticList[p]["semanticcategoryL1"] + ")";
        nodeSM2.style.display = 'block';

        nodeSM2.style.transform = "translate(" + SMSin + "px," + SMCos + "px)";

        //Insert the Checker Value!
        checkerSM2 = newLessonSemanticList[p]['language2'];
        checkerSM2VocabID = newLessonSemanticList[p]['contentid'];
        
        for (var i = 0; i < SMContent.length; i++) {
            clueSM2.insertAdjacentHTML('beforeend',
                `<p>` + SMContent[i] + `</p>`
            );
        };

    } else if (p == 2){
        //Insert and Activate the DIV
        categorySM3.textContent = newLessonSemanticList[p]["semanticcategoryL2"] + " (" + newLessonSemanticList[p]["semanticcategoryL1"] + ")";
        nodeSM3.style.display = 'block';

        nodeSM3.style.transform = "translate(" + SMSin + "px," + SMCos + "px)";

        //Insert the Checker Value!
        checkerSM3 = newLessonSemanticList[p]['language2'];
        checkerSM3VocabID = newLessonSemanticList[p]['contentid'];

        for (var i = 0; i < SMContent.length; i++) {
            clueSM3.insertAdjacentHTML('beforeend',
                `<p>` + SMContent[i] + `</p>`
            );
        };

    } else if (p == 3){
        //Insert and Activate the DIV
        categorySM4.textContent = newLessonSemanticList[p]["semanticcategoryL2"] + " (" + newLessonSemanticList[p]["semanticcategoryL1"] + ")";
        nodeSM4.style.display = 'block';

        nodeSM4.style.transform = "translate(" + SMSin + "px," + SMCos + "px)";

        //Insert the Checker Value!
        checkerSM4 = newLessonSemanticList[p]['language2'];
        checkerSM4VocabID = newLessonSemanticList[p]['contentid'];

        for (var i = 0; i < SMContent.length; i++) {
            clueSM4.insertAdjacentHTML('beforeend',
                `<p>` + SMContent[i] + `</p>`
            );
        };

    } else if (p == 4){
        //Insert and Activate the DIV
        categorySM5.textContent = newLessonSemanticList[p]["semanticcategoryL2"] + " (" + newLessonSemanticList[p]["semanticcategoryL1"] + ")";
        nodeSM5.style.display = 'block';

        nodeSM5.style.transform = "translate(" + SMSin + "px," + SMCos + "px)";

        //Insert the Checker Value!
        checkerSM5 = newLessonSemanticList[p]['language2'];
        checkerSM5VocabID = newLessonSemanticList[p]['contentid'];

        for (var i = 0; i < SMContent.length; i++) {
            clueSM5.insertAdjacentHTML('beforeend',
                `<p>` + SMContent[i] + `</p>`
            );
        };
    }

}

function getDataCheck(form, SMNumber, SMNumberID, SM) {
    var formData = new FormData(form);
    formAnswer = formData.get('answer');

    var regexSetting = /\s*(?:,|$)\s*/;

    var checkReference = SMNumber.split(regexSetting);
    var checkAnswer = formAnswer.split(regexSetting);

    //Check the Length, if its more than 1, that means it has multiple entries!
    if (SMNumberID.length > 1) {
         var checkAnswerID = SMNumberID.split(regexSetting);
    } else {
        var checkAnswerID = [SMNumberID];
    }
    
    //Checker Counter
    var checkCounter = 0;
    
    //Check Each of the Items in FormAnswer with every Check Reference Items
    for (var p = 0; p < checkAnswer.length; p++) {
        for (var i = 0; i < checkReference.length; i++) {
            similarity_result = similarity(checkReference[i], checkAnswer[p]);
            console.log("The Reference = " + checkReference[i] + "The Answer = " + checkAnswer[p] + " Similarity = " + similarity_result);
            if (similarity_result == 1){
                //Blur out the word in the Word List!
                var vocabSegmentID = document.getElementById("vocabSegmentList"+checkAnswerID[i]);
                vocabSegmentID.style.backgroundColor = "#77f78e";
                vocabSegmentID.style.color = "#ffffff";
                checkCounter++;
            }
        }
    }

    //If the Check Counter and the Total of Entries are the same, that means the data is correct!
    if (checkCounter == checkReference.length){
        if (SM == 0) {
            if (correctSM1 == 0){
                console.log("Category 1 is Complete!");
                correctSM1 = 1;
                semanticCompletionProgress++;
                semanticProgressBarUpdate();
                reviewAreaCorrectionBox1.style.display = 'block';
                reviewAreaCorrectionBox1.style.backgroundColor = '#77f78e';
                reviewAreaCorrectionText1.textContent = "CORRECT";
            }

        } else if (SM == 1){
            if (correctSM2 == 0) {
                console.log("Category 2 is Complete!");
                correctSM2 = 1;
                semanticCompletionProgress++;
                semanticProgressBarUpdate();
                reviewAreaCorrectionBox2.style.display = 'block';
                reviewAreaCorrectionBox2.style.backgroundColor = '#77f78e';
                reviewAreaCorrectionText2.textContent = "CORRECT";
            }
            
        } else if (SM == 2) {
            if (correctSM3 == 0){
                console.log("Category 3 is Complete!");
                correctSM3 = 1;
                semanticCompletionProgress++;
                semanticProgressBarUpdate();
                reviewAreaCorrectionBox3.style.display = 'block';
                reviewAreaCorrectionBox3.style.backgroundColor = '#77f78e';
                reviewAreaCorrectionText3.textContent = "CORRECT";
            }
            

        } else if (SM == 3) {
            if (correctSM4 == 0){
                console.log("Category 4 is Complete!");
                correctSM4 = 1;
                semanticCompletionProgress++;
                semanticProgressBarUpdate();
                reviewAreaCorrectionBox4.style.display = 'block';
                reviewAreaCorrectionBox4.style.backgroundColor = '#77f78e';
                reviewAreaCorrectionText4.textContent = "CORRECT";
            }
            

        } else if (SM == 4) {
            if (correctSM5 == 0) {
                console.log("Category 5 is Complete!");
                correctSM5 = 1;
                semanticCompletionProgress++;
                semanticProgressBarUpdate();
                reviewAreaCorrectionBox5.style.display = 'block';
                reviewAreaCorrectionBox5.style.backgroundColor = '#77f78e';
                reviewAreaCorrectionText5.textContent = "CORRECT";
            }
        }

    } else {
        if (SM == 0) {
            console.log("Category 1 Incomplete!");
            reviewAreaCorrectionBox1.style.display = 'block';
            reviewAreaCorrectionBox1.style.backgroundColor = '#ffc76e';
            reviewAreaCorrectionText1.textContent = "CHECK AGAIN";

        } else if (SM == 1) {
            console.log("Category 2 is Incomplete!");
            reviewAreaCorrectionBox2.style.display = 'block';
            reviewAreaCorrectionBox2.style.backgroundColor = '#ffc76e';
            reviewAreaCorrectionText2.textContent = "CHECK AGAIN";

        } else if (SM == 2) {
            console.log("Category 3 is Incomplete!");
            reviewAreaCorrectionBox3.style.display = 'block';
            reviewAreaCorrectionBox3.style.backgroundColor = '#ffc76e';
            reviewAreaCorrectionText3.textContent = "CHECK AGAIN";

        } else if (SM == 3) {
            console.log("Category 4 is Incomplete!");
            reviewAreaCorrectionBox4.style.display = 'block';
            reviewAreaCorrectionBox4.style.backgroundColor = '#ffc76e';
            reviewAreaCorrectionText4.textContent = "CHECK AGAIN";

        } else if (SM == 4) {
            console.log("Category 5 is Incomplete!");
            reviewAreaCorrectionBox5.style.display = 'block';
            reviewAreaCorrectionBox5.style.backgroundColor = '#ffc76e';
            reviewAreaCorrectionText5.textContent = "CHECK AGAIN";
        }
    }
}

/*
var testChecker = checkerSM1VocabID.split(regexSetting);

for (var i = 0; i < testChecker.length; i++) {
    console.log(testChecker[i]);
}
*/

document.getElementById("formSM1").addEventListener("submit", function (event) {
    event.preventDefault();
    console.log('Check Point 1!');
    getDataCheck(event.target, checkerSM1, checkerSM1VocabID, "0");
});

document.getElementById("formSM2").addEventListener("submit", function (event) {
    event.preventDefault();
    console.log('Check Point 2!');
    getDataCheck(event.target, checkerSM2, checkerSM2VocabID, "1");
});

document.getElementById("formSM3").addEventListener("submit", function (event) {
    event.preventDefault();
    console.log('Check Point 3!');
    getDataCheck(event.target, checkerSM3, checkerSM3VocabID, "2");
});

document.getElementById("formSM4").addEventListener("submit", function (event) {
    event.preventDefault();
    console.log('Check Point 4!');
    getDataCheck(event.target, checkerSM4, checkerSM4VocabID, "3");
});

document.getElementById("formSM5").addEventListener("submit", function (event) {
    event.preventDefault();
    console.log('Check Point 5!');
    getDataCheck(event.target, checkerSM5, checkerSM5VocabID,  "4");
});

//Check if Tutorial is Turned on?
if (localStorage.getItem('vocabAppTutorial') == 0){
    tutorialSemanticBoxToggle();
}

//Review Section
//Review Progress Bar
const progressBar = document.getElementById('progressBar');
const progressBarText = document.getElementById('progressBarText');

var reviewCompletionProgress = 0;

//Updates the Bar on top of the Review, takes the reviewCounter Variable used at the start!
function progressBarUpdate() {
    //Update the Text
    progressBarText.textContent = reviewCompletionProgress + "/" + reviewCounter + " Progress";

    //Update the Visual
    var progressBarCalculation = (reviewCompletionProgress / reviewCounter) * 100;
    progressBar.style.width = progressBarCalculation + "vw";
}

//Correction Result
const reviewAreaCorrection = document.getElementById('reviewAreaCorrection');
const reviewAreaCorrectionClass = reviewAreaCorrection.classList;

const reviewAreaCorrectionBox = document.getElementById('reviewAreaCorrectionBox');
const reviewAreaCorrectionText = document.getElementById('reviewAreaCorrectionText');

function reviewAreaCorrect() {
    reviewAreaCorrectionText.textContent = "CORRECT";
    reviewAreaCorrectionBox.style.backgroundColor = "#77f78e";
    reviewAreaCorrectionClass.toggle('displayStyle');
}

function reviewAreaIncorrect() {
    reviewAreaCorrectionText.textContent = "INCORRECT";
    reviewAreaCorrectionBox.style.backgroundColor = "#de3737";
    reviewAreaCorrectionClass.toggle('displayStyle');
}

function reviewAreaCorrectionReset() {
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

function revealQuickAccess() {
    reviewQuickAccessDropdownClass.toggle('reviewQuickAccessDrop');
    if (reviewQuickAccessState == 0) {
        reviewQuickAccessState = 1;
    } else {
        reviewQuickAccessState = 0;
    }
}

function revealQuickAccessReset() {
    if (reviewQuickAccessState == 1) {
        reviewQuickAccessDropdownClass.toggle('reviewQuickAccessDrop');
        reviewQuickAccessState = 0;
    }
}

function revealQuickAccessReveal() {
    reviewQuickAccessClass.toggle('displayQuickAccessStyle');
}

//Count the Total of the Review Entries
const review_total = newReviewList.length;
var review_current = 0;

//User Reviewing (After Correct Set or After wrong Answer)
var review_pause_state = 0;

//Placeholder Text Change!
const inputAnswer = document.getElementById('answer');

function nextEntry() {
    
    //console.log("Test Type being checked  " + newReviewList[review_current]['test_type'] + " Review Current " + review_current);
    if (newReviewList[review_current]['test_type'] == 1) {
        //console.log(newReviewList[review_current]['language1']);
        //console.log(newReviewList[review_current]['language2']);
        questionArea.textContent = newReviewList[review_current]['language1'];

        //Reveal Details Addon
        reviewQuickAccessText.textContent = newReviewList[review_current]['semanticlist'];

        //Change the Placeholder!
        inputAnswer.placeholder = "Type Answer in Indonesian here!";

        //console.log(review_current);
        //console.log(newReviewList[review_current]['language1']);
    } else if (newReviewList[review_current]['test_type'] == 2) {
        questionArea.textContent = newReviewList[review_current]['language2'];

        //Reveal Details Addon
        reviewQuickAccessText.textContent = newReviewList[review_current]['semanticlist'];

        //Change the Placeholder!
        inputAnswer.placeholder = "Type Answer in English here!";

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
    if (newReviewList[review_current]['test_type'] == 1) {
        var similarity_result = similarity(newReviewList[review_current]['language2'], formData.get("answer"));
        //console.log("Check the Answer! " + formData.get("answer") + " Answer Sheet " + newReviewList[review_current]['language2'] + " Percentage " + similarity_result);

    } else if (newReviewList[review_current]['test_type'] == 2) {
        var similarity_result = similarity(newReviewList[review_current]['language1'], formData.get("answer"));
        //console.log("Check the Answer! " + formData.get("answer") + " Answer Sheet " + newReviewList[review_current]['language1'] + " Percentage " + similarity_result);
    }

    //Get the Review Record
    var record_id = newReviewList[review_current]['record_id'];

    //Record the Attempt
    if (newReviewList[review_current]['test_type'] == 1) {
        reviewRecordList[record_id]['attempts_lang1'] = reviewRecordList[record_id]['attempts_lang1'] + 1;
    } else if (newReviewList[review_current]['test_type'] == 2) {
        reviewRecordList[record_id]['attempts_lang2'] = reviewRecordList[record_id]['attempts_lang2'] + 1;
    }

    if (similarity_result > 0.80) {
        //console.log("correct");
        //Check if you have completed a set?

        //If you have not gotten any answers correct yet
        if (reviewRecordList[record_id]['success_lang1'] == 0 && reviewRecordList[record_id]['success_lang2'] == 0) {
            //console.log("Case1");
            //If you are correct on language 1
            if (newReviewList[review_current]['test_type'] == 1) {

                reviewRecordList[record_id]['success_lang1'] = 1;
                //console.log("Case1 - Test Type 1" + reviewRecordList[record_id]['success_lang1'] + reviewRecordList[record_id]['success_lang2']);

            } else if (newReviewList[review_current]['test_type'] == 2) {
                //Else if you are correct on language 2
                reviewRecordList[record_id]['success_lang2'] = 1;
                //console.log("Case1 - Test Type 2" + reviewRecordList[record_id]['success_lang1'] + reviewRecordList[record_id]['success_lang2']);

            }
        } else if (reviewRecordList[record_id]['success_lang1'] == 1 || reviewRecordList[record_id]['success_lang2'] == 1) {
            console.log("Case2");
            //If you have one of them correct already, that means success!
            if (newReviewList[review_current]['test_type'] == 1) {

                reviewRecordList[record_id]['success_lang1'] = 1;
                //console.log("Case2 - Test Type 1" + reviewRecordList[record_id]['success_lang1'] + reviewRecordList[record_id]['success_lang2']);

            } else if (newReviewList[review_current]['test_type'] == 2) {
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
            //mnemoniclist: newReviewList[review_current]['mnemoniclist'],
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
        if (review_current < newReviewList.length) {
            //If it is not the last one!
            nextEntry();
        } else {
            console.log("Stop!");
            endReview();
        }

        resultArea.textContent = "";
        form_clear.value = "";
    }
}

function getCorrect(form) {
    var formData = new FormData(form);

    resultArea.textContent = "";
    form_clear.value = "";

    //If review_pause_state = 1, means correct answer
    if (review_pause_state == 1) {


    } else if (review_pause_state == 2) {
        //If review_pause_state = 2, means incorrect answer
    }

    //Turn off Pause State
    review_pause_state = 0;

    //Continue the Reviews like usual
    review_current++;
    if (review_current < newReviewList.length) {
        //If it is not the last one!
        nextEntry();
    } else {
        //console.log("Stop!");
        review_state = 0;
        endReview();

        reviewModalClass.toggle('reviewFinishSectionDisplay');

        var reviewRecordResult = JSON.stringify(reviewRecordList);
        document.getElementById('reviewRecordListArray').value = reviewRecordResult;
    }
}

function endReview() {
    console.log("Length of Review Record " + reviewRecordList.length);
    //Quick Tester for Values
    for (var i = 0; i < reviewRecordList.length; i++) {
        console.log("EntryID = " + reviewRecordList[i]['entry_id']);
        console.log("Success_lang1 = " + reviewRecordList[i]['success_lang1']);
        console.log("Success_lang2 = " + reviewRecordList[i]['success_lang2']);
        console.log("Attempts_lang1 = " + reviewRecordList[i]['attempts_lang1']);
        console.log("Attempts_lang2 = " + reviewRecordList[i]['attempts_lang2']);
        console.log("Complete = " + reviewRecordList[i]['complete']);
    }

}

document.getElementById("myForm").addEventListener("submit", function (event) {
    event.preventDefault();
    //If it still in Lesson mode!
    if (review_state == 0) {
        //Do something?
    } else {
        //If it is in paused mode
        if (review_pause_state > 0) {
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
    }
});

</script>



@endsection