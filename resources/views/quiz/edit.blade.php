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

                        {{-- Type --}}
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Type</label>
                            <div class="col-sm-9">
                                <input class="form-control" id="type" placeholder="INDIVIDUAL / GROUP" value="{{ $quiz->type }}">
                            </div>
                        </div>

                        {{-- Status --}}
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Status</label>
                            <div class="col-sm-9">
                                <input class="form-control" id="status" placeholder="OPEN / CLOSE" value="{{ $quiz->status }}">
                            </div>
                        </div>

                        {{-- Update Button --}}
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-9">
                                <button class="btn btn-success submit">Update</button>
                                <a href="{{ route('quizzes.index') }}" class="btn btn-primary pull-right">Back to Previous Page</a>
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
        let role = selectRole.val()

        let data = {
            '_token': getToken(),
            'title' : $('#title').val(),
            'type'  : $('#status').val(),
            'status': $('#type').text(),
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
                window.location.href = "{{ route('quizzes.show', $quiz->id) }}"
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
            window.location.href = "{{ route('quizzes.index') }}"
        })
    })
}) ()
</script>
@endsection
