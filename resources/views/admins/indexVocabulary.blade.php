@extends('layout')

@section('content')

<h1>Admin Vocabulary</h1>
<!-- Create New Level -->
<a href="/Thesis-VocabularyWebApp/vocabwebapp/public/admin/level/{{$level->id}}/vocabulary/create">
    <p>Create New Vocabulary</p>
</a>


@unless(count($vocabularies) == 0)
@foreach($vocabularies as $vocabulary)
    <p>{{$vocabulary->language1}}</p>
    <p>{{$vocabulary->language2}}</p>
    <p>{{$vocabulary->mnemonics}}</p>
    <a href="/Thesis-VocabularyWebApp/vocabwebapp/public/admin/level/{{$level->id}}/vocabulary/{{$vocabulary->id}}/edit">
        Edit
    </a>
    <form method="POST" action="/Thesis-VocabularyWebApp/vocabwebapp/public/admin/level/{{$level->id}}/vocabulary/{{$vocabulary->id}}/delete">
        @csrf
        @method("DELETE")
        <button>
            <p>Delete</p>
        </button>
    </form>
        
        
@endforeach

@else
<p>No Vocabulary Found</p>
@endunless

@endsection