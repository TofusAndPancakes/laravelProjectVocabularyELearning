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
var newLessonPHP = @json($newLessons);
var newLessonList = [];
var newReviewList = [];
var reviewRecordList = [];

var reviewCounter = 0; //Initialized here because only needed once

//Make the Array into Lesson Array and Review Array
for (var i = 0; i <= {{count($newLessons)}}-1; i++) {
    //Lesson
    const newLessonPush = {
        id: i,
        language1: newLessonPHP[i]['language1'],
        language2: newLessonPHP[i]['language2'],
        mnemonics: newLessonPHP[i]['mnemonics'],
        mnemoniclist: newLessonPHP[i]['mnemoniclist'],
        semanticlist: newLessonPHP[i]['semanticlist'],
        }

    newLessonList.push(newLessonPush);

    //English to Indonesian
    const newReviewListPush = {
        language1: newLessonPHP[i]['language1'],
        language2: newLessonPHP[i]['language2'],
        mnemoniclist: newLessonPHP[i]['mnemoniclist'],
        semanticlist: newLessonPHP[i]['semanticlist'],
        test_type: 1,
        record_id: reviewCounter,
    }

    newReviewList.push(newReviewListPush);

    //Make Javascript Object
    //Indonesian to English
    const newReviewListPushInverse = {
        language1: newLessonPHP[i]['language1'],
        language2: newLessonPHP[i]['language2'],
        mnemoniclist: newLessonPHP[i]['mnemoniclist'],
        semanticlist: newLessonPHP[i]['semanticlist'],
        test_type: 2,
        record_id: reviewCounter,
    }

    newReviewList.push(newReviewListPushInverse);

    //Make A Review Record that Keeps track!
    const reviewRecordListPush = {
        entry_id: newLessonPHP[i]['id'],
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

</style>

<h1>Review</h1>
<!-- Lesson Area -->
<div id="lessonArea">

  <p id='lessonLanguage1'></p>
  <p id='lessonLanguage2'></p>
  <p id='lessonMnemonics'></p>

  <button onclick='previousLessonButton()'>Previous</button>
  <button onclick='nextLessonButton()'>Next</button>
</div>

<!-- Review Area -->
<div id="reviewArea" class="displayNoneStyle">
  <div class="container">
    <h2 id="questionArea"></h2>
    <p>Question Above</p>
    <p id="resultArea"></p>
  </div>

  <form id="myForm">  
      <label>Answer<input id="answer" name="answer" value=""></label>
      <input type="submit" value="Submit">
  </form>
</div>

<div id="modalArea" class="displayNoneStyle">
  <div>Are you ready for the Review?</div>
  <button onclick='loadReviewCancel()'>No</button>
  <button onclick='loadReview()'>Yes</button>
</div>

<form method="POST" action="/Thesis-VocabularyWebApp/vocabwebapp/public/lesson/result">
  @csrf
  <div class="form">
      <input type="hidden" id="reviewRecordListArray" name="reviewRecordListArray" value="">
    <div class="form">
      <button type="submit">Submit Result</button>
  </div>
</div>

</form>

<script>


var reviewRecordResult = JSON.stringify(reviewRecordList);
document.getElementById('reviewRecordListArray').value = reviewRecordResult;

//-- Lesson Script --
//State of the Web Application
var lesson_state = 1;
var review_state = 0;
var readyReviewModalOpen = 0;

const readyReview = document.getElementById('modalArea');
const readyReviewClass = readyReview.classList;

//Review Variables just to Prepare!
//Locate the Answer
const questionArea = document.getElementById('questionArea');
//Locate the Result
const resultArea = document.getElementById('resultArea');
//Located the ReviewArea (Special for Lesson!)
const reviewArea = document.getElementById('reviewArea');
const reviewAreaClass = reviewArea.classList;

//Ready Review Function
function readyReviewModal(){
  if (readyReviewModalOpen == 0){
    readyReviewClass.toggle('displayStyle');
    readyReviewModalOpen = 1;
    console.log("I'm here!");
  }
}

//What to do when enter is pressed in lesson!
var lessonNextEnter = function(e){
    if (e.key === 'Enter') {
      nextLessonButton();
    }
};

//The trigger for the next Lesson!
window.addEventListener('keydown', lessonNextEnter);

//End the lesson by removing the event Listener!
function lessonStateEnd(){
    window.removeEventListener('keydown', lessonNextEnter);
}

//What is the current lesson, start from 0!
var lesson_current = 0;
//Set the Locations of the Text
const lessonLanguage1 = document.getElementById('lessonLanguage1');
const lessonLanguage2 = document.getElementById('lessonLanguage2');
const lessonMnemonics = document.getElementById('lessonMnemonics');

function nextLesson(){
    lessonLanguage1.textContent = newLessonList[lesson_current]['language1'];
    lessonLanguage2.textContent = newLessonList[lesson_current]['language2'];
    lessonMnemonics.textContent = newLessonList[lesson_current]['mnemonics'];
}

function previousLessonButton(){
    if (lesson_current == 0){
      lesson_current = newLessonList.length-1;
      //console.log(lesson_current);
      nextLesson();
    } else {
      lesson_current = lesson_current-1;
      //console.log(lesson_current);
      nextLesson();
    }
}

function nextLessonButton(){
    if (lesson_current == newLessonList.length-1){
      //You have gone through all Lessons, are you ready for review?
      readyReviewModal();
      //console.log('Hit the Limit!');

    } else {
      lesson_current = lesson_current+1;
      //console.log(lesson_current);
      nextLesson();
    }
}

//Call it once at the start! (Lesson)
nextLesson();

function loadReviewCancel(){
    readyReviewClass.toggle('displayStyle');
    readyReviewModalOpen = 0;
    console.log("Cancelling");
}

function loadReview(){
    readyReviewClass.toggle('displayStyle');
    lesson_state = 0;
    lessonStateEnd();

    review_state = 1;
    console.log("Starting Review!");

    //Initializing Review
    reviewAreaClass.toggle('displayStyle');
    nextEntry();
}

// -- Review Script -- 

//Count the Total of the Review Entries
const review_total = newReviewList.length;
var review_current = 0;

//User Reviewing (After Correct Set or After wrong Answer)
var review_pause_state = 0;

function nextEntry (){
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
          resultArea.textContent = "CORRECT";
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
      resultArea.textContent = "INCORRECT";

  }

  if (review_pause_state > 0) {
    //Dont do anything, we dont want to remove the text!
  } else {
      review_current++;
    if (review_current < newReviewList.length){
      //If it is not the last one!
      nextEntry();
    } else {
        console.log("Stop!");
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
      console.log("Stop!");
      review_state = 0;
      endReview();

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
  //If it still in Lesson mode!
  if (review_state == 0){
    //Do something?
  } else {
    //If it is in paused mode
    if (review_pause_state > 0){
        //Enter custom code!
        //console.log("getCorrect() Triggered");
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