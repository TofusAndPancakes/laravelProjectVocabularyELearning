@extends('layout')

@section('content')
<div class="indexBodySection">
    <div class="indexBodyMargin">
        <div class="adminFormContent">
            <form method="POST" action="/Thesis-VocabularyWebApp/vocabwebapp/public/admin/level/{{$level->id}}/vocabulary/store">
                @csrf
                <h1>Create New Vocabulary</h1>
                <hr>
                <div class="formTitle"><h3>L2 - Studied Language</h3></div>
                <div class="form">
                    <input type="language1" name="language1" value="{{old('language1')}}"/>
                    @error('language1')
                        <p>{{$message}}</p>
                    @enderror
                </div>

                <div class="formTitle"><h3>L2 - Native Language, English</h3></div>
                <div class="form">
                    <input type="language2" name="language2" value="{{old('language2')}}"/>
                    @error('language2')
                        <p>{{$message}}</p>
                    @enderror
                </div>

                <div class="formTitle"><h3>Mnemonics</h3></div>
                <div class="form">
                    <input type="mnemonics" name="mnemonics" value="{{old('mnemonics')}}"/>
                    @error('mnemonics')
                        <p>{{$message}}</p>
                    @enderror
                </div>

                <div class="formTitle"><h3>Mnemonics List</h3></div>
                <div class="form">
                    <input type="mnemoniclist" name="mnemoniclist" value="{{old('mnemoniclist')}}"/>
                    @error('mnemoniclist')
                        <p>{{$message}}</p>
                    @enderror
                </div>

                <div class="formTitle"><h3>Semantic List</h3></div>
                <div class="form">
                    <input type="semanticlist" name="semanticlist" value="{{old('semanticlist')}}"/>
                    @error('semanticlist')
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