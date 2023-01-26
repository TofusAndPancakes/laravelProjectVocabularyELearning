@extends('layout')

@section('content')
<style>
  .displayTutorialNoneStyle {
      visibility: hidden;
      opacity: 0;
      display: none;
      transition: visibility 0.3s, opacity 0.3s;
  }

  .displayTutorialStyle {
      visibility: visible !important;
      opacity: 1 !important;
      display: flex;
  }
</style>

<div class="indexBodySection">
    <div class="indexBodyMarginColumn">
        <div class="indexBodySegment">
            @if(session()->has('message'))
            <p>{{session('message')}}</p>
            @endif
        </div>

        <div class="tutorialBoxSegment" id="tutorialBoxSegment">
            <div class="tutorialBoxClose">
                <img src="{{asset('images/close.svg')}}"" onclick='tutorialBoxClose()'>
            </div>
            <h1>Menu Tutorial</h1>
            <hr>
            <p>Welcome to the Indonesian English E-learning Vocabulary Application. 
                The application aims to aid your Indonesian vocabulary memorization using Mnemonic Keyword and Semantic Mapping. </p>
            <br>
            <p>To start, press the [Lesson] button. This starts a new Lesson session. The lesson will teach you the vocabulary you will be learning using the techniques mentioned. 
                After a Lesson Session, a Review Session will automatically start to help you recall the words you have learned.</p>
            <br>
            <p>You will learn more on how to do Lesson and Review when start your first Lesson and Review Sessions.</p>
        </div>

        <div class="indexBodySegment">
            <!-- Learn -->
            <div class="learnSection">
                @unless($newLessons == null)
                @if($userLevel == 2)
                    <a href={{route('semantic')}}><p>Semantic Lesson</p></a>
                    <p>Start a new semantic lesson session.</p>
                    <p>{{$newLessons}} semantic(s) exercise available!</p>
                @else
                    <a href={{route('lesson')}}><p>Lesson</p></a>
                    <p>Start a new lesson session.</p>
                    <p>{{$newLessons}} lesson(s) available!</p>
                @endif
                @else
                @if($userLevel == 2)
                <p>Semantic Lesson</p>
                <p>Currently unavailable.</p>
                @else
                <p>Lesson</p>
                <p>Currently unavailable.</p>
                @endif
                @endunless
                
            </div>

            <!-- Reviews -->
            <div class="reviewSection">
                @unless($newReviews == null)
                <a href={{route('review')}}><p>Review</p></a>
                <p>Start a new review session.</p>
                <p>{{$newReviews}} review(s) available!</p>
                @else
                <p>Review</p>
                <p>Currently unavailable.</p>
                @endunless
            </div>
        </div>
    </div>
</div>

<div class="tutorialBoxModalSection" id="modalArea">
    <div class="tutorialBoxModal">
        <div class="tutorialBoxModalText">
        <h1>Note!</h1>
        <hr>
        <p>You can turn of tutorials by going to the footer on the bottom and pressing [Tutorial].</p> 
        </div>

        <div class="tutorialBoxModalButton">
        <button class="formButton tutorialBoxModalButtonMargin" onclick='tutorialModalClose()'>I Understand</button>
        <button class="formButton tutorialBoxModalButtonMargin" onclick='tutorialModalCloseNever()'>Never Show Again</button>
        </div>
    </div>
</div>

<script>

//Tutorial
const tutorial = document.getElementById('tutorialBoxSegment');
const tutorialClass = tutorial.classList;

const neverShowTutorial = document.getElementById('modalArea');
const neverShowTutorialClass = neverShowTutorial.classList;

//Check if Tutorial is Enabled?
if (localStorage.getItem('vocabAppTutorial') == 0){
    tutorialClass.toggle('displayTutorialStyle');    
}

function tutorialBoxClose(){
    if (localStorage.getItem('vocabAppTutorialFooterInfo') == 0){
        neverShowTutorialClass.toggle('displayTutorialStyle');
    } else {
        tutorialModalSingleClose();
    }
}

function tutorialModalSingleClose(){
    tutorialClass.toggle('displayTutorialStyle');
}

function tutorialModalClose(){
    tutorialClass.toggle('displayTutorialStyle');
    neverShowTutorialClass.toggle('displayTutorialStyle');
}

function tutorialModalCloseNever(){
    tutorialClass.toggle('displayTutorialStyle');
    neverShowTutorialClass.toggle('displayTutorialStyle');
    localStorage.setItem('vocabAppTutorialFooterInfo', '1');
}
</script>
@endsection