@extends('layout')

@section('content')
<div class="indexBodySection">
    <div class="indexBodyMargin">
        <div class="loginContent">
            <form method="POST" action={{route('login.authenticate')}}>
                @csrf
                <h1>Login</h1>
                <hr>
                <div class="formTitle"><h3>Email</h3></div>
                <div class="form">
                    <input type="email" name="email" value="{{old('email')}}"/>
                    @error('email')
                        <p>{{$message}}</p>
                    @enderror
                </div>

                <div class="formTitle"><h3>Password</h3></div>
                <div class="form">
                    <input type="password" name="password" value="{{old('password')}}"/>
                    @error('password')
                        <p>{{$message}}</p>
                    @enderror
                </div>
                <div class="form formMargin">
                    <button class="formButton" type="submit"><p>Sign In</p></button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection