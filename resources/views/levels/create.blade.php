@extends('layout')

@section('content')
<div class="indexBodySection">
    <div class="indexBodyMargin">
        <div class="adminFormContent">
            <form method="POST" action="/Thesis-VocabularyWebApp/vocabwebapp/public/admin/level/store">
                @csrf
                <h1>Create New Level</h1>
                <hr>
                <div class="formTitle"><h3>Level Title [Integer]</h3></div>
                <div class="form">
                    <input type="leveltitle" name="leveltitle" value="{{old('leveltitle')}}"/>
                    @error('leveltitle')
                        <p>{{$message}}</p>
                    @enderror
                </div>
                <div class="form formMargin">
                    <button class="formButton" type="submit">Create</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection