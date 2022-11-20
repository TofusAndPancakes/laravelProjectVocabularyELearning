@extends('layout')

@section('content')
<p>Edit Vocabulary</p>
    <form method="POST" action="/Thesis-VocabularyWebApp/vocabwebapp/public/admin/level/{{$level->id}}/vocabulary/{{$vocabulary->id}}/update">
        @csrf

        <div class="form">
            <label for="language1">Language1</label>
            <input type="language1" name="language1" value="{{$vocabulary->language1}}"/>
            @error('language1')
                <p>{{$message}}</p>
            @enderror
        </div>

        <div class="form">
            <label for="language2">Language2</label>
            <input type="language2" name="language2" value="{{$vocabulary->language2}}"/>
            @error('language2')
                <p>{{$message}}</p>
            @enderror
        </div>

        <div class="form">
            <label for="mnemonics">Mnemonics</label>
            <input type="mnemonics" name="mnemonics" value="{{$vocabulary->mnemonics}}"/>
            @error('mnemonics')
                <p>{{$message}}</p>
            @enderror
        </div>

        <div class="form">
            <label for="mnemoniclist">Mnemonic List</label>
            <input type="mnemoniclist" name="mnemoniclist" value="{{$vocabulary->mnemoniclist}}"/>
            @error('mnemoniclist')
                <p>{{$message}}</p>
            @enderror
        </div>

        <div class="form">
            <label for="semanticlist">Semantic List</label>
            <input type="semanticlist" name="semanticlist" value="{{$vocabulary->semanticlist}}"/>
            @error('semanticlist')
                <p>{{$message}}</p>
            @enderror
        </div>

        <div class="form">
            <button type="submit">Edit Vocabulary</button>
        </div>
    </form>
@endsection