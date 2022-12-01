@extends('layout')

@section('content')
<div class="indexBodySection">
    <div class="indexBodyMarginColumn">
        <div class="indexBodySegment">
            <div class="adminTitleSplit">
                <div class="adminTitleMain">
                    <h1>Level Menu</h1>
                </div>
                <div class="adminTitleButton">
                    <!-- Create New Level -->
                    <button class="formButton">
                        <a href={{route('admin.level.create')}}>
                            <p>Create New Level</p>
                        </a>
                    </button>
                </div>
            </div>
        </div>

        <div class="adminContentRow">
                @unless(count($levels) == 0)
                @foreach($levels as $level)
                <div class="adminBoxContentRow">
                    <div class="adminBoxContentRowSegment">
                        <p>{{$level->leveltitle}}</p></a>
                    </div>
                    <div class="adminBoxContentRowSegment">
                        <button class="formButton adminBoxContentRowSegmentMarginButton">
                            <a href={{route('admin.vocabulary', ['level' => $level->id])}}>
                                Add or Update Vocabularies
                            </a>
                        </button>
                        <button class="formButton adminBoxContentRowSegmentMarginButton">
                            <a href={{route('admin.level.edit', ['level' => $level->id])}}>
                                Edit
                            </a>
                        </button>
                        <form method="POST" action={{route('admin.level.delete', ['level' => $level->id])}}>
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
                        <p>No Levels Found</p>
                    </div>
                </div>
                @endunless
            </div>

            </div>
        </div>

    </div>
</div>

@endsection