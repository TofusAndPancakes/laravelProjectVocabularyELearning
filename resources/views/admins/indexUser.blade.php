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
                        <a href="/Thesis-VocabularyWebApp/vocabwebapp/public/admin/user/create">
                            <p>Create New User</p>
                        </a>
                    </button>
                </div>
            </div>
        </div>

        <div class="adminContentRow">
                @unless(count($users) == 0)
                @foreach($users as $user)
                <div class="adminBoxContentRow">
                    <div class="adminBoxContentRowSegment">
                        <p>{{$user->name}}</p>
                    </div>
                    <div class="adminBoxContentRowSegment">
                        <button class="formButton adminBoxContentRowSegmentMarginButton">
                            <a href="/Thesis-VocabularyWebApp/vocabwebapp/public/admin/user/{{$user->id}}/edit">
                                Edit
                            </a>
                        </button>
                        <form method="POST" action="/Thesis-VocabularyWebApp/vocabwebapp/public/admin/user/{{$user->id}}/delete">
                            @csrf
                            @method("DELETE")
                            <button class="formButton">
                                <p>Delete</p>
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
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