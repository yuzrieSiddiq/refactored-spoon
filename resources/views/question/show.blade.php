@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-offset-1 col-md-10">
            <div class="panel panel-default">
                <div class="panel-heading">
                    QUIZ {{ $quiz->id }}: {{ $quiz->title }}
                </div>
                <div class="panel-body">
                    <div class="form-horizontal">
                        {{-- QUESTION --}}
                        <div class="form-group">
                            <label class="col-sm-3 control-label">QUESTION</label>
                            <div class="col-sm-9">
                                <p class="form-control-static">{{ $question->question }}</p>
                            </div>
                        </div>

                        {{-- ANSwER TYPE --}}
                        <div class="form-group">
                            <label class="col-sm-3 control-label">ANSWER TYPE</label>
                            <div class="col-sm-9">
                                <p class="form-control-static">{{ $question->answer_type }}</p>
                            </div>
                        </div>

                        {{-- ANSWER 1 --}}
                        <div class="form-group">
                            <label class="col-sm-3 control-label">ANSWER 1</label>
                            <div class="col-sm-9">
                                <p class="form-control-static">{{ $question->answer1 }}</p>
                            </div>
                        </div>

                        {{-- ANSWER 2 --}}
                        <div class="form-group">
                            <label class="col-sm-3 control-label">ANSWER 2</label>
                            <div class="col-sm-9">
                                <p class="form-control-static">{{ $question->answer2 }}</p>
                            </div>
                        </div>

                        {{-- ANSWER 3 --}}
                        <div class="form-group">
                            <label class="col-sm-3 control-label">ANSWER 3</label>
                            <div class="col-sm-9">
                                <p class="form-control-static">{{ $question->answer3 }}</p>
                            </div>
                        </div>

                        {{-- ANSWER 4 --}}
                        <div class="form-group">
                            <label class="col-sm-3 control-label">ANSWER 4</label>
                            <div class="col-sm-9">
                                <p class="form-control-static">{{ $question->answer4 }}</p>
                            </div>
                        </div>

                        {{-- ANSWER 5 --}}
                        <div class="form-group">
                            <label class="col-sm-3 control-label">ANSWER 5</label>
                            <div class="col-sm-9">
                                <p class="form-control-static">{{ $question->answer5 }}</p>
                            </div>
                        </div>

                        {{-- CORRECT ANSWER --}}
                        <div class="form-group">
                            <label class="col-sm-3 control-label">CORRECT ANSWER</label>
                            <div class="col-sm-9">
                                <p class="form-control-static">{{ $question->correct_answer }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-footer">
                    <a class="btn btn-success" href="{{ route('quizzes.questions.edit', ['quiz' => $quiz->id, 'question' => $question->id]) }}">
                        EDIT QUESTION/ANSWER
                    </a>
                    <a class="btn btn-info pull-right" href="{{ route('quizzes.questions.index', $quiz->id) }}">CANCEL</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
