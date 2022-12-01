@extends('layout')

@section('content')
<div class="indexBodySection">
    <div class="indexBodyMarginColumn">
        <div class="indexBodySegment">
            <div class="adminTitleSplit">
                <div class="adminTitleMain">
                    <h1>Level {{$level->leveltitle}} Vocabulary Menu</h1>
                </div>
                <div class="adminTitleButton">
                    <!-- Create New Vocabulary -->
                    <button class="formButton">
                    <a href={{route('admin.vocabulary.create', ['level' => $level->id])}}>
                        <p>Create New Vocabulary</p>
                    </a>
                    </button>
                </div>
            </div>
        </div>

        <div class="adminContentRow">
            @unless(count($vocabularies) == 0)
            @foreach($vocabularies as $vocabulary)
            <div class="adminBoxContentRow">
                <div class="adminBoxContentRowSegment">
                    <div class="adminBoxContentRowSegmentMargin">
                        <p>{{$vocabulary->language1}}</p>
                    </div>
                    <div class="adminBoxContentRowSegmentMargin">
                        <p>{{$vocabulary->language2}}</p>
                    </div>
                    <div class="adminBoxContentRowSegmentMargin">
                        <p>{{$vocabulary->mnemonics}}</p>
                    </div>
                    <div class="adminBoxContentRowSegmentMarginNone">
                        <p>{{$vocabulary->semanticlist}}</p>
                    </div>
                </div>
                <div class="adminBoxContentRowSegment">
                    <button class="formButton adminBoxContentRowSegmentMarginButton">
                        <a href={{route('admin.vocabulary.edit', ['level' => $level->id, 'vocabulary' => $vocabulary->id])}}>
                            Edit
                        </a>
                    </button>
                    <form method="POST" action={{route('admin.vocabulary.delete', ['level' => $level->id, 'vocabulary' => $vocabulary->id])}}>
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
                    <p>No Vocabulary Found</p>
                </div>
            </div>
            @endunless
        </div>
    </div>
</div>

@endsection