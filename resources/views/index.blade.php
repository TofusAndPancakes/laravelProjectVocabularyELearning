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
            <div class="boxContent">
                <h1>Welcome to the Language App</h1>
                <hr>
                <p>Please login through the navigation bar to the top right of the website. Good luck!</p>
            </div>
        </div>

    </div>
</div>

@endsection