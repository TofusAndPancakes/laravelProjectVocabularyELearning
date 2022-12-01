@extends('layout')

@section('content')
<div class="indexBodySection">
    <div class="indexBodyMargin">
        <div class="adminFormContent">
            <form method="POST" action="/Thesis-VocabularyWebApp/vocabwebapp/public/admin/user/{{$user->id}}/update">
                @csrf
                <h1>Edit User</h1>
                <hr>
                <div class="formTitle"><h3>Name</h3></div>
                <div class="form">
                    <input type="text" name="name" value="{{$user->name}}"/>
                    @error('name')
                        <p>{{$message}}</p>
                    @enderror
                </div>
                <div class="formTitle"><h3>Email</h3></div>
                <div class="form">
                    <input type="email" name="email" value="{{$user->email}}"/>
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
                <div class="formTitle"><h3>Confirm Password</h3></div>
                <div class="form">
                    <input type="password" name="password_confirmation" value="{{old('password_confirmation')}}"/>
                    @error('password_confirmation')
                        <p>{{$message}}</p>
                    @enderror
                </div>
                <div class="formTitle"><h3>Admin [0 - Normal, 1 - Admin]</h3></div>
                <div class="form">
                    <input type="integer" name="admin" value="{{$user->admin}}"/>
                    @error('admin')
                        <p>{{$message}}</p>
                    @enderror
                </div>
                <div class="formTitle"><h3>Group</h3></div>
                <div class="form">
                    <input type="text" name="group" value="{{$user->group}}"/>
                    @error('group')
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