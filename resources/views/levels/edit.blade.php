@extends('layout')

@section('content')
<div class="indexBodySection">
    <div class="indexBodyMargin">
        <div class="adminFormContent">
            <form method="POST" action={{route('admin.level.update', ['level' => $level->id])}}>
                @csrf
                <h1>Edit Level</h1>
                <hr>
                <div class="formTitle"><h3>Level Title [Integer]</h3></div>
                <div class="form">
                    <input type="leveltitle" name="leveltitle" value="{{$level->leveltitle}}"/>
                    @error('leveltitle')
                        <p>{{$message}}</p>
                    @enderror
                </div>
                <div class="form formMargin">
                    <button class="formButton" type="submit">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection