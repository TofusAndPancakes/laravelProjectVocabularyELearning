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
                <p>If you have received the login details from the owner, please enter the [Login] page through the navigation bar on the top.</p>
            </div>
        </div>
    </div>
</div>

@endsection