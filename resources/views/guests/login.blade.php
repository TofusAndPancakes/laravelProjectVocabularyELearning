@extends('layout')

@section('content')

    <form method="POST" action="/Thesis-VocabularyWebApp/vocabwebapp/public/users/login">
        @csrf

        <div class="form">
            <label for="email">Email</label>
            <input type="email" name="email" value="{{old('email')}}"/>
            @error('email')
                <p>{{$message}}</p>
            @enderror
        </div>

        <div class="form">
            <label for="password">Password</label>
            <input type="password" name="password" value="{{old('password')}}"/>
            @error('password')
                <p>{{$message}}</p>
            @enderror
        </div>
        <div class="form">
            <button type="submit">Sign In</button>
        </div>
    </form>

@endsection