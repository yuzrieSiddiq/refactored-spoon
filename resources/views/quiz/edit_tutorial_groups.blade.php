@extends('layouts.app')

@section('extra_head')
    <link rel="stylesheet" href="{{ asset('css/bootstrap-datepicker.min.css') }}">
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
                                    <input id="test-date" class="form-control" data-provide="datepicker" value="{{ Carbon\Carbon::parse($group->test_date)->format('j/m/Y') }}">
                                @else
                                    <input id="test-date" class="form-control" data-provide="datepicker" placeholder="Date" value="">
                                @endif
                            </div>

                            {{-- Duration --}}
                            <div class="col-sm-offset-1 col-sm-4">
                                <select class="form-control" id="duration">
                                    @if (isset($quiz->show_questions))
                                        <option value="{{ $group->duration }}">{{ $group->duration }} minutes</option>
                                        <option value="" disabled>___</option>
                                    @endif
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
                                    <button class="btn btn-success submit" data-method="PUT"
                                        data-url="{{ route('quizzes.questions.update.group', ['quiz' => $quiz->id, 'group' => $group->group_number]) }}">
                                        UPDATE
                                    </button>
                                </div>
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
<script src="{{ asset('js/bootstrap-datepicker.min.js') }}"></script>
<script>
(function(){
    // Get CSRF token
    let getToken = function() {
        return $('meta[name=csrf-token]').attr('content')
    }

    $('.submit').click(function() {
        let url = $(this).data('url')
        let method = $(this).data('method')
        let data = {
            '_token': getToken(),
            'is_open': $('#is_open_check').prop('checked'),
            'is_randomized': $('#is_randomized_check').prop('checked'),
            'date': $('#test-date').val(),
            'duration': $('#duration').val()
        }

        $.ajax({
            'url': url,
            'method': method,
            'data': data
        }).done(function(response) {
            window.location.reload()
        })
    })
}) ()
</script>
@endsection
