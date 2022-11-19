@extends('layout')

@section('content')

<h1>Main Menu</h1>
<div class="">
    <!-- Login -->
    <a href="/Thesis-VocabularyWebApp/vocabwebapp/public/login">
        <p>Login</p>
    </a>

    <!-- Admin Interface -->
    <a href="/Thesis-VocabularyWebApp/vocabwebapp/public/admin">
        <p>Admin</p>
    </a>

    <!-- Logout -->
    <form class="inline" method="POST" action="/Thesis-VocabularyWebApp/vocabwebapp/public/logout">
        @csrf
        <button type="submit">
            Logout
        </button>
    </form>

    @if(session()->has('message'))
    <p>{{session('message')}}</p>
    @endif
</div>

@endsection