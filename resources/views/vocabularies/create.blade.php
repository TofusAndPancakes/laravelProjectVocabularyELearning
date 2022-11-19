@extends('layout')

@section('content')
<p>Create New Vocabulary</p>
    <form method="POST" action="">
        @csrf

        <div class="form">
            <label for="language1">Language1</label>
            <input type="language1" name="language1" value="{{old('language1')}}"/>
            @error('language1')
                <p>{{$message}}</p>
            @enderror
        </div>

        <div class="form">
            <label for="language2">Language2</label>
            <input type="language2" name="language2" value="{{old('language2')}}"/>
            @error('language2')
                <p>{{$message}}</p>
            @enderror
        </div>

        <div class="form">
            <label for="mnemonics">Mnemonics</label>
            <input type="mnemonics" name="mnemonics" value="{{old('mnemonics')}}"/>
            @error('mnemonics')
                <p>{{$message}}</p>
            @enderror
        </div>

        <div class="form">
            <label for="mnemoniclist">Mnemonic List</label>
            <input type="mnemoniclist" name="mnemoniclist" value="{{old('mnemoniclist')}}"/>
            @error('mnemoniclist')
                <p>{{$message}}</p>
            @enderror
        </div>

        <div class="form">
            <label for="semanticlist">Semantic List</label>
            <input type="semanticlist" name="semanticlist" value="{{old('semanticlist')}}"/>
            @error('semanticlist')
                <p>{{$message}}</p>
            @enderror
        </div>

        <div class="form">
            <button type="submit">Create New Vocabulary</button>
        </div>
    </form>
@endsection