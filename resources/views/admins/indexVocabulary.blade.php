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
            <div class="adminBoxContentRow">
                <table class="table">
                    <thead>
                        <tr>
                        <th scope="col">No.</th>
                        <th scope="col">L2 - Studied Language</th>
                        <th scope="col" class="adminTableRowDisplayNone">L1 - Native Language</th>
                        <th scope="col" class="adminTableRowDisplayNone">Mnemonics</th>
                        <th scope="col" class="adminTableRowDisplayNone">Semantic List</th>
                        <th scope="col">Options</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($vocabularies as $vocabulary)
                        <tr>
                        <th scope="row">{{$loop->iteration}}</th>
                        <td>{{$vocabulary->language1}}</td>
                        <td class="adminTableRowDisplayNone">{{$vocabulary->language2}}</td>
                        <td class="adminTableRowDisplayNone">{{$vocabulary->mnemonics}}</td>
                        <td class="adminTableRowDisplayNone">{{$vocabulary->semanticlist}}</td>
                        <td>
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
                        </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="adminBoxContentRow">
                <div class="adminBoxContentRowSegment">
                    <p>No Vocabulary Found</p>
                </div>
            </div>
            @endunless
        </div>

        <div class="indexBodySegment">
            <div class="adminTitleSplit">
                <div class="adminTitleMain">
                    <h1>Level {{$level->leveltitle}} Semantic Menu</h1>
                </div>
                <div class="adminTitleButton">
                    <!-- Create New Vocabulary -->
                    <button class="formButton">
                    <a href={{route('admin.semantic.create', ['level' => $level->id])}}>
                        <p>Create New Semantic</p>
                    </a>
                    </button>
                </div>
            </div>
        </div>

        <div class="adminContentRow">
            @unless(count($semantics) == 0)
            <div class="adminBoxContentRow">
                <table class="table">
                    <thead>
                        <tr>
                        <th scope="col">No.</th>
                        <th scope="col">L2 - Studied Language</th>
                        <th scope="col" class="adminTableRowDisplayNone">L1 - Native Language</th>
                        <th scope="col" class="adminTableRowDisplayNone">Word ID</th>
                        <th scope="col">Options</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($semantics as $semantic)
                        <tr>
                        <th scope="row">{{$loop->iteration}}</th>
                        <td>{{$semantic->semanticlanguage1}}</td>
                        <td class="adminTableRowDisplayNone">{{$semantic->semanticlanguage2}}</td>
                        <td class="adminTableRowDisplayNone">{{$semantic->semanticdata_id}}</td>
                        <td>
                            <button class="formButton adminBoxContentRowSegmentMarginButton">
                                <a href={{route('admin.semantic.edit', ['level' => $level->id, 'semantic' => $semantic->id])}}>
                                    Edit
                                </a>
                            </button>
                            <form method="POST" action={{route('admin.semantic.delete', ['level' => $level->id, 'semantic' => $semantic->id])}}>
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
                    <p>No Semantic Found</p>
                </div>
            </div>
            @endunless
        </div>
    </div>
</div>

@endsection