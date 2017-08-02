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
                                <input class="form-control" id="question" placeholder="MCQ / RANKING" value="{{ $question->question }}">
                            </div>
                        </div>

                        {{-- ANSWER 1 --}}
                        <div class="form-group">
                            <label class="col-sm-3 control-label">ANSWER 1</label>
                            <div class="col-sm-9">
                                <input class="form-control" id="answer1" placeholder="-" value="{{ $question->answer1 }}">
                            </div>
                        </div>

                        {{-- ANSWER 2 --}}
                        <div class="form-group">
                            <label class="col-sm-3 control-label">ANSWER 2</label>
                            <div class="col-sm-9">
                                <input class="form-control" id="answer2" placeholder="-" value="{{ $question->answer2 }}">
                            </div>
                        </div>

                        {{-- ANSWER 3 --}}
                        <div class="form-group">
                            <label class="col-sm-3 control-label">ANSWER 3</label>
                            <div class="col-sm-9">
                                <input class="form-control" id="answer3" placeholder="-" value="{{ $question->answer3 }}">
                            </div>
                        </div>

                        {{-- ANSWER 4 --}}
                        <div class="form-group">
                            <label class="col-sm-3 control-label">ANSWER 4</label>
                            <div class="col-sm-9">
                                <input class="form-control" id="answer4" placeholder="-" value="{{ $question->answer4 }}">
                            </div>
                        </div>

                        {{-- ANSWER 5 --}}
                        <div class="form-group">
                            <label class="col-sm-3 control-label">ANSWER 5</label>
                            <div class="col-sm-9">
                                <input class="form-control" id="answer5" placeholder="-" value="{{ $question->answer5 }}">
                            </div>
                        </div>

                        {{-- CORRECT ANSWER --}}
                        <div class="form-group">
                            <label class="col-sm-3 control-label">CORRECT ANSWER</label>
                            <div class="col-sm-9">
                                <input class="form-control" id="correct-answer" placeholder="-" value="{{ $question->correct_answer }}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-footer">
                    <button class="btn btn-success submit" data-url="{{ route('quizzes.questions.update', ['quiz' => $quiz->id, 'question' => $question->id]) }}">
                        UPDATE QUESTION DETAILS
                    </button>
                    <a class="btn btn-info pull-right" href="{{ route('quizzes.questions.show', ['quiz' => $quiz->id, 'question' => $question->id]) }}">CANCEL</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('extra_js')
<script>
(function(){
    // Get CSRF token
    let getToken = function() {
        return $('meta[name=csrf-token]').attr('content')
    }

    $('.alert').hide()
    let showErrorMessage = function(errormsg)  {
        $('.alert').text(errormsg)
        $('.alert').show(450).delay(8000).slideUp(450)
    }

    $('.submit').click(function() {
        let url = $(this).data('url')
        let data = {
            '_token': getToken(),
            'quiz_title' : $('#quiz-title').val(),
            'question'  : $('#question').val(),
            'answer1': $('#answer1').val(),
            'answer2': $('#answer2').val(),
            'answer3': $('#answer3').val(),
            'answer4': $('#answer4').val(),
            'answer5': $('#answer5').val(),
            'correct_answer': $('#correct-answer').val(),
        }

        $.ajax({
            'url': url,
            'method': 'PUT',
            'data': data
        }).done(function(response) {
            if (response == '1') {
                let errormsg = 'Something is wrong with this question please review and make sure the answers are all correct (no typos)'
                showErrorMessage(errormsg)
            } else {
                window.location.href = '{{ route('quizzes.questions.show', ['quiz' => $quiz->id, 'question' => $question->id]) }}'
            }
        })
    })
}) ()
</script>
@endsection
