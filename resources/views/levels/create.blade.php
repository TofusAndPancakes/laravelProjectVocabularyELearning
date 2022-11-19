@extends('layout')

@section('content')
<p>Create New Level</p>
    <form method="POST" action="/Thesis-VocabularyWebApp/vocabwebapp/public/admin/level/store">
        @csrf

        <div class="form">
            <label for="leveltitle">LevelTitle - Integer</label>
            <input type="leveltitle" name="leveltitle" value="{{old('leveltitle')}}"/>
            @error('leveltitle')
                <p>{{$message}}</p>
            @enderror
        </div>
        <div class="form">
            <button type="submit">Create New Level</button>
        </div>
    </form>
@endsection