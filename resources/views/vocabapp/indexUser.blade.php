@extends('layout')

@section('content')
<div class="indexBodySection">
    <div class="indexBodyMarginColumn">
        <div class="indexBodySegment">
            @if(session()->has('message'))
            <p>{{session('message')}}</p>
            @endif
        </div>
        <div class="indexBodySegment">
            <!-- Learn -->
            <div class="learnSection">
                @unless($newLessons == null)
                <a href={{route('lesson')}}><p>Learn New Words</p></a>
                <p>{{$newLessons}} Lessons!</p>
                @else
                <p>Learn New Words</p>
                @endunless
                
            </div>

            <!-- Reviews -->
            <div class="reviewSection">
                @unless($newReviews == null)
                <a href={{route('review')}}><p>Do Review</p></a>
                <p>{{$newReviews}} Reviews!</p>
                @else
                <p>Do Review</p>
                @endunless
            </div>
            
        </div>
    </div>
</div>
@endsection