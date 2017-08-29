@extends('layouts.app')

@section('extra_head')
    <link rel="stylesheet" href="{{ asset('css/bootstrap-datepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/datatables.bootstrap.css') }}">
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-offset-1 col-md-10">
            <div class="panel panel-default">
                <div class="panel-body">
                    <h4 class="text-uppercase">{{ $quiz->title }} [GROUP: {{ $group->group_number }}]</h4>
                    <hr>

                    <div class="form-horizontal">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Status</label>

                            {{-- Is Open --}}
                            <div class="col-sm-4">
                                <div class="input-group">
                                    <input type="text" class="form-control" value="Is Open" disabled>
                                    <span class="input-group-addon">
                                        @if ($group->is_open == 1) {{-- if true --}}
                                            <input type="checkbox" id="is_open_check" checked>
                                        @else
                                            <input type="checkbox" id="is_open_check">
                                        @endif
                                    </span>
                                </div>
                            </div>

                            {{-- Is Randomized --}}
                            <div class="col-sm-offset-1 col-sm-4">
                                <div class="input-group">
                                    <input type="text" class="form-control" value="Is Randomized" disabled>
                                    <span class="input-group-addon">
                                        @if ($group->is_randomized == 1) {{-- if true --}}
                                            <input type="checkbox" id="is_randomized_check" checked>
                                        @else
                                            <input type="checkbox" id="is_randomized_check">
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Date/Duration</label>
                            {{-- Test Date --}}
                            <div class="col-sm-4">
                                @if (isset($group->test_date))
                                    <input id="test-date" class="form-control" value="{{ Carbon\Carbon::parse($group->test_date)->format('d/m/Y') }}" data-provide="datepicker">
                                @else
                                    <input id="test-date" class="form-control" data-provide="datepicker" placeholder="Date" value="">
                                @endif
                            </div>

                            {{-- Duration --}}
                            <div class="col-sm-offset-1 col-sm-4">
                                <select class="form-control" id="duration">
                                    @if ($quiz->duration !== 0)
                                        <option value="{{ $group->duration }}">{{ $group->duration }} minutes</option>
                                    @else
                                        <option value="0">Test</option>
                                    @endif
                                    <option value="" disabled>___</option>
                                    {{-- options are separated every 30 minutes --}}
                                    @for ($i=1; $i < 7; $i++)
                                        <option value="{{ $i*30 }}">{{ $i*30 }} minutes</option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                        {{-- Update Button --}}
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-9">
                                <a class="btn btn-info" href="{{ route('quizzes.edit', $quiz->id) }}">BACK TO PREVIOUS PAGE</a>
                                <div class="pull-right">
                                    <button class="btn btn-success submit" data-method="PUT" data-method-direction="update_tutorial_group"
                                        data-url="{{ route('quizzes.questions.update.group', ['quiz' => $quiz->id, 'group' => $group->group_number]) }}">
                                        UPDATE
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div> {{-- end .form-horizontal --}}

                </div> {{-- end .panel-body --}}
            </div> {{-- end .panel --}}

            @if ($group->is_randomized == 0)
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped" id="questions-table">
                                <thead>
                                    <tr>
                                        <th></th> {{-- id [hidden in JS] --}}
                                        <th class="text-center" width="10%">No</th>
                                        <th class="text-center" width="30%">Question</th>
                                        <th class="text-center" width="30%">Correct Answer</th>
                                        <th></th> {{-- options [hidden in JS] --}}
                                    </tr>
                                </thead>
                            </table>
                        </div> {{-- end .table-responsive --}}
                    </div>
                    <div class="panel-footer">
                        <button type="button" class="btn btn-success submit" data-method="PUT" data-method-direction="choose_questions"
                        data-url="{{ route('quizzes.questions.choose', ['quiz' => $quiz->id, 'group' => $group->group_number]) }}">
                        UPDATE
                    </button>
                </div> {{-- end .panel-footer --}}
            </div> {{-- end .panel --}}
            @endif

        </div> {{-- end .col-10 --}}
    </div> {{-- end .row --}}
</div> {{-- end .container --}}
@endsection

@section('extra_js')
<script src="{{ asset('js/datatables.net.js') }}"></script>
<script src="{{ asset('js/datatables.bootstrap.js') }}"></script>
<script src="{{ asset('js/bootstrap-datepicker.min.js') }}"></script>
<script>
(function(){
    // Get CSRF token
    let getToken = function() {
        return $('meta[name=csrf-token]').attr('content')
    }

    // formatting the datepicker
    $('#test-date').datepicker({
        format: 'dd/mm/yyyy',
        startDate: '-3d'
    });

    $('.submit').click(function() {
        let url = $(this).data('url');
        let method = $(this).data('method');
        let method_direction = $(this).data('method-direction');
        let data = {};

        if (method_direction == "update_tutorial_group") {

            data = {
                '_token': getToken(),
                'is_open': $('#is_open_check').prop('checked'),
                'is_randomized': $('#is_randomized_check').prop('checked'),
                'date': $('#test-date').val(),
                'duration': $('#duration').val()
            }
        } else if (method_direction == "choose_questions") {
            let chosen_questions = ""
            $( ".chosen_questions" ).each(function() {
                if ($(this).prop('checked')) {
                    chosen_questions += ($(this).data('question-id') + " ")
                }
            });

            data = {
                '_token': getToken(),
                'chosen_questions': chosen_questions
            }
        }


        $.ajax({
            'url': url,
            'method': method,
            'data': data
        }).done(function(response) {
            window.location.reload()
        })
    })

    let ellipsis = function(string) {
        if (string.length > 100)
            return string.substring(0, 100) + '...'

        return string
    }

    /** datatable **/
    let choose_questions_operation = function(question_id, chosen_questions) {
        // if the the question id matches one of the id in chosen_questions array, return with a checked
        for (let i = 0; i < chosen_questions.length; i++) {
            if (chosen_questions[i] == question_id) {
                return '<div class="input-group update-group-div">\
                            <input type="checkbox" class="chosen_questions" data-question-id="' + question_id +  '" checked="true">\
                        </div>'
            }
        }

        // if id is not listed in chosen questions array, return default
        return '<div class="input-group update-group-div">\
                    <input type="checkbox" class="chosen_questions" data-question-id="' + question_id +  '" value="false">\
                </div>'
    }

    let table = $('#questions-table').DataTable( {
        "ajax": "{{ route('get.questions.group.datatable', ['quiz_id' => $quiz->id, 'group_no' => $group->group_number]) }}",
        "columnDefs": [
            {   // hide question id
                "targets": 0,
                "searchable": false,
                "visible": false
            },
            {   // show question number instead of chosen_questions
                "targets": 1,
                "className": "text-center",
                "render": function(data, type, full, meta) {
                    return meta['row']+1
                }
            },
            {   // ellipsis text
                "targets": 2,
                "render": function(data, type, full, meta) {
                    return ellipsis(data)
                }
            },
            {   // add extra column and process the question "checked" status
                "targets": -1,
                "data": null,
                "className": "text-center",
                "render": function(data, type, full, meta) {
                    let question_id = full[0]
                    let chosen_questions = full[1].split(" ")

                    return choose_questions_operation(question_id, chosen_questions)
                }
            }
        ]
    });



}) ()
</script>
@endsection
