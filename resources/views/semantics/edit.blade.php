@extends('layout')

@section('content')
<div class="indexBodySection">
    <div class="indexBodyMargin">
        <div class="adminFormContent">
            <form method="POST" action={{route('admin.semantic.update', ['level' => $level->id, 'semantic' => $semantic->id])}}>
                @csrf
                <h1>Edit Vocabulary</h1>
                <hr>
                <div class="formTitle"><h3>L2 - Studied Language</h3></div>
                <div class="form">
                    <input type="semanticlanguage1" name="semanticlanguage1" value="{{$semantic->semanticlanguage1}}"/>
                    @error('semanticlanguage1')
                        <p>{{$message}}</p>
                    @enderror
                </div>

                <div class="formTitle"><h3>L1 - Native Language, English</h3></div>
                <div class="form">
                    <input type="semanticlanguage2" name="semanticlanguage2" value="{{$semantic->semanticlanguage2}}"/>
                    @error('semanticlanguage2')
                        <p>{{$message}}</p>
                    @enderror
                </div>

                <div class="formTitle"><h3>Vocabulary ID</h3></div>
                <div class="form">
                    <input type="semanticdata_id" name="semanticdata_id" value="{{$semantic->semanticdata_id}}"/>
                    @error('semanticdata_id')
                        <p>{{$message}}</p>
                    @enderror
                </div>

                <div class="form formMargin">
                    <button class="formButton" type="submit">Edit Semantic</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection