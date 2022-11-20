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
    
    //Review
    const newReviewPush = {
        id: i,
        entry_id: newLessonPHP[i]['id'],
        language1: newLessonPHP[i]['language1'],
        language2: newLessonPHP[i]['language2'],
        mnemonics: newLessonPHP[i]['mnemonics'],
        mnemoniclist: newLessonPHP[i]['mnemoniclist'],
        semanticlist: newLessonPHP[i]['semanticlist'],
        success_lang1: 0,
        success_lang2: 0,
        attempts_lang1: 0,
        attempts_lang2: 0,
        complete: 0,
        }

    newReviewList.push(newReviewPush);
}



</script>

<h1>Review</h1>
@endsection