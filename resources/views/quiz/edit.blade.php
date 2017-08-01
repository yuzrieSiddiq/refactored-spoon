@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-offset-1 col-md-10">
            <div class="panel panel-default">
                <div class="panel-body">
                    <h4 class="text-uppercase">MANAGE QUIZ</h4>
                    <hr>

                    <div class="form-horizontal">
                        {{-- Title --}}
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Title</label>
                            <div class="col-sm-9">
                                <input class="form-control" id="title" placeholder="iRAT1" value="{{ $quiz->title }}">
                            </div>
                        </div>

                        {{-- Status --}}
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Status</label>
                            <div class="col-sm-9">
                                <select class="form-control" id="status">
                                    @if ($quiz->status == 'open')
                                        <option value="open">Open</option>
                                        <option value="close">Close</option>
                                    @else
                                        <option value="open">Open</option>
                                        <option value="close">Close</option>
                                    @endif
                                </select>
                            </div>
                        </div>

                        {{-- Allowed/Show Questions --}}
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Allowed Questions</label>
                            <div class="col-sm-9">
                                <select class="form-control" id="show-questions">
                                    <option value="{{ $quiz->show_questions }}">{{ $quiz->show_questions }}</option>
                                    <option value="" disabled>___</option>
                                    @for ($i=1; $i <= 100; $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                        {{-- Update Button --}}
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-9">
                                <a class="btn btn-info" href="{{ route('units.show', $quiz->unit_id) }}">BACK TO PREVIOUS PAGE</a>
                                <div class="pull-right">
                                    <button class="btn btn-success submit">UPDATE</button>
                                    <button class="btn btn-danger remove">DELETE</button>
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
            'title' : $('#title').val(),
            'type'  : $('#type').val(),
            'status': $('#status').val(),
            'show_questions': $('#show-questions').val(),
        }

        $.ajax({
            'url': '{{ route('quizzes.update', $quiz->id) }}',
            'method': 'PUT',
            'data': data
        }).done(function(response) {
            if (response == '1') {
                let errormsg = 'A quiz with that title already exist'
                showErrorMessage(errormsg)
            } else {
                window.location.href = "{{ route('units.show', $quiz->unit_id) }}"
            }
        })
    })

    $('.remove').click(function() {
        let data = { '_token': getToken() }

        $.ajax({
            'url': '{{ route('quizzes.destroy', $quiz->id) }}',
            'method': 'DELETE',
            'data': data
        }).done(function(data) {
            window.location.href = "{{ route('units.show', $quiz->unit_id) }}"
        })
    })
}) ()
</script>
@endsection
