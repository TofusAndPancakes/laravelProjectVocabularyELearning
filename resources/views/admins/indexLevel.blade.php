@extends('layout')

@section('content')

<h1>Admin Main Menu</h1>
<!-- Create New Level -->
<a href="/Thesis-VocabularyWebApp/vocabwebapp/public/admin/level/create">
    <p>Create New Level</p>
</a>


@unless(count($levels) == 0)
@foreach($levels as $level)
    <a href="/Thesis-VocabularyWebApp/vocabwebapp/public/admin/level/{{$level->id}}/vocabulary"><p>{{$level->leveltitle}}</p></a>
    <a href="/Thesis-VocabularyWebApp/vocabwebapp/public/admin/level/{{$level->id}}/edit">
        Edit
    </a>
    <form method="POST" action="/Thesis-VocabularyWebApp/vocabwebapp/public/admin/level/{{$level->id}}/delete">
        @csrf
        @method("DELETE")
        <button>
            <p>Delete</p>
        </button>
    </form>
@endforeach

@else
<p>No Levels Found</p>
@endunless

@endsection