@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-3">
            @include('layouts.menu_side')
        </div>
        <div class="col-md-9">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>UNIT INFO</h4>
                </div>
                <div class="panel-body">

                    <div class="form-horizontal">
                        {{-- Code --}}
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Code</label>
                            <div class="col-sm-9">
                                <p class="form-control-static">{{ $unit->code }}</p>
                            </div>
                        </div>

                        {{-- Name --}}
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Name</label>
                            <div class="col-sm-9">
                                <p class="form-control-static">{{ $unit->name }}</p>
                            </div>
                        </div>

                        {{-- Students --}}
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Number of Students</label>
                            <div class="col-sm-9">
                                <p class="form-control-static">{{ $unit->students->count() }}</p>
                            </div>
                        </div>

                        {{-- Quizzes --}}
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Number of Quizzes</label>
                            <div class="col-sm-9">
                                <p class="form-control-static">{{ $unit->quizzes->count() }}</p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>QUIZZES</h4>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Quiz ID</th>
                                <th class="col-md-3">Quiz Title</th>
                                <th>Quiz Type</th>
                                <th>Quiz Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (isset($quizzes))
                                @foreach ($quizzes as $quiz)
                                    <tr>
                                        <td class="text-center">{{ $quiz->id }}</td>
                                        <td>{{ $quiz->title }}</td>
                                        <td>{{ $quiz->type }}</td>
                                        <td>{{ $quiz->status }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('quizzes.edit', $quiz->id) }}" class="btn btn-primary">Edit Quiz</a>
                                            <a class="btn btn-info manage" href="{{ route('quizzes.questions.index', $quiz->id)}}">Manage Questions</a>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
                <div class="panel-footer">
                    <a class="btn btn-info" href="{{ route('units.lecturer') }}">BACK TO PREVIOUS PAGE</a>

                    <div class="pull-right">
                        <a class="btn btn-primary" href="{{ route('units.quizzes.create', $unit->id) }}">
                            UPLOAD NEW QUIZ
                        </a>
                        <a class="btn btn-success" href="{{ route('units.quizzes.create', $unit->id) }}">
                            CREATE NEW QUIZ
                        </a>
                    </div>
                </div> {{-- end .panel-footer --}}
            </div>
        </div>
    </div>
</div>
@endsection
