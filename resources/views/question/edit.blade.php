@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-offset-1 col-md-10">
            <div class="panel panel-default">
                <div class="panel-body">
                    <h4 class="text-uppercase">MANAGE QUESTION</h4>
                    <hr>

                    <div class="form-horizontal">
                        {{-- Quiz --}}
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Quiz</label>
                            <div class="col-sm-9">
                                <input class="form-control" id="quiz-title" placeholder="iRAT1" value="{{ $quiz->title }}">
                            </div>
                        </div>

                        {{-- Question --}}
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Quesion</label>
                            <div class="col-sm-9">
                                <input class="form-control" id="question" placeholder="MCQ / RANKING" value="{{ $question->question }}">
                            </div>
                        </div>

                        {{-- Answer Type --}}
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Answer Type</label>
                            <div class="col-sm-9">
                                <input class="form-control" id="answer-type" placeholder="MCQ / RANKING" value="{{ $question->answer_type }}">
                            </div>
                        </div>

                        {{-- Answer 1 --}}
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Answer 1</label>
                            <div class="col-sm-9">
                                <input class="form-control" id="answer1" placeholder="-" value="{{ $question->answer1 }}">
                            </div>
                        </div>

                        {{-- Answer 2 --}}
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Answer 2</label>
                            <div class="col-sm-9">
                                <input class="form-control" id="answer2" placeholder="-" value="{{ $question->answer2 }}">
                            </div>
                        </div>

                        {{-- Answer 3 --}}
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Answer 3</label>
                            <div class="col-sm-9">
                                <input class="form-control" id="answer3" placeholder="-" value="{{ $question->answer3 }}">
                            </div>
                        </div>

                        {{-- Answer 4 --}}
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Answer 4</label>
                            <div class="col-sm-9">
                                <input class="form-control" id="answer4" placeholder="-" value="{{ $question->answer4 }}">
                            </div>
                        </div>

                        {{-- Answer 5 --}}
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Answer 5</label>
                            <div class="col-sm-9">
                                <input class="form-control" id="answer5" placeholder="-" value="{{ $question->answer5 }}">
                            </div>
                        </div>

                        {{-- Correct Answer --}}
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Correct Answer</label>
                            <div class="col-sm-9">
                                <input class="form-control" id="correct-answer" placeholder="-" value="{{ $question->correct_answer }}">
                            </div>
                        </div>

                        {{-- Update Button --}}
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-9">
                                <button class="btn btn-success submit">Update</button>
                                <a href="{{ route('questions.index') }}" class="btn btn-primary pull-right">Back to Previous Page</a>
                            </div>
                        </div>
                    </div> {{-- end .form-horizontal --}}

                </div> {{-- end .panel-body --}}
            </div> {{-- end .panel --}}
        </div> {{-- end .col-10 --}}
    </div> {{-- end .row --}}
</div> {{-- end .container --}}
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
            'answer_type': $('#answer-type').val(),
            'answer1': $('#answer1').val(),
            'answer2': $('#answer2').val(),
            'answer3': $('#answer3').val(),
            'answer4': $('#answer4').val(),
            'answer5': $('#answer5').val(),
            'correct_answer': $('#correct-answer').val(),
        }

        $.ajax({
            'url': '{{ route('questions.update', $quiz->id) }}',
            'method': 'PUT',
            'data': data
        }).done(function(response) {
            if (response == '1') {
                let errormsg = 'Something is wrong with this question please review and make sure the answers are all correct (no typos)'
                showErrorMessage(errormsg)
            } else {
                window.location.href = "{{ route('questions.show', $quiz->id) }}"
            }
        })
    })

    $('.remove').click(function() {
        let data = { '_token': getToken() }

        $.ajax({
            'url': '{{ route('questions.destroy', $quiz->id) }}',
            'method': 'DELETE',
            'data': data
        }).done(function(data) {
            window.location.href = "{{ route('questions.index') }}"
        })
    })
}) ()
</script>
@endsection
