@extends('layout')

@section('content')
<div class="indexBodySection">
    <div class="indexBodyMarginColumn">
        <div class="indexBodySegment">
            <div class="adminTitleSplit">
                <div class="adminTitleMain">
                    <h1>User Menu</h1>
                </div>
                <div class="adminTitleButton">
                    <!-- Create New Level -->
                    <button class="formButton">
                        <a href={{route('admin.user.create')}}>
                            <p>Create New User</p>
                        </a>
                    </button>
                </div>
            </div>
        </div>

        <div class="adminContentRow">
            @unless(count($users) == 0)
            <div class="adminBoxContentRow">
                <table class="table">
                    <thead>
                        <tr>
                        <th scope="col">No.</th>
                        <th scope="col">Username</th>
                        <th scope="col">Options</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $user)
                        <tr>
                        <th scope="row">{{$loop->iteration}}</th>
                        <td>{{$user->name}}</td>
                        <td>
                            <button class="formButton adminBoxContentRowSegmentMarginButton">
                            <a href={{route('admin.user.edit', ['user' => $user->id])}}>
                                Edit
                            </a>
                            </button>
                            <form method="POST" action={{route('admin.user.delete', ['user' => $user->id])}}>
                                @csrf
                                @method("DELETE")
                                <button class="formButton">
                                    <p>Delete</p>
                                </button>
                            </form>
                        </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            @else
                
            <div class="adminBoxContentRow">
                <div class="adminBoxContentRowSegment">
                    <p>No Users Found</p>
                </div>
            </div>
            @endunless
        </div>

        </div>
    </div>

    </div>
</div>

@endsection