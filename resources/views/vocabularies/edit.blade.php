@extends('layout')

@section('content')
<p>Edit Vocabulary</p>
    <form method="POST" action="/Thesis-VocabularyWebApp/vocabwebapp/public/admin/level/{{$level->id}}/update">
        @csrf

        <div class="form">
            <label for="leveltitle">LevelTitle - Integer</label>
            <input type="leveltitle" name="leveltitle" value="{{$level->leveltitle}}"/>
            @error('leveltitle')
                <p>{{$message}}</p>
            @enderror
        </div>
        <div class="form">
            <button type="submit">Edit the Level</button>
        </div>
    </form>
@endsection