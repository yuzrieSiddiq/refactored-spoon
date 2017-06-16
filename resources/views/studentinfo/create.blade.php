@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-offset-1 col-md-10">
            <div class="panel panel-default">
                <div class="panel-body">
                    <h3>CREATE NEW STUDENT INFO - {{ $user->firstname }} {{ $user->lastname }}</h3>
                    <hr>

                    <div class="form-horizontal">
                        {{-- Student ID --}}
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Student ID</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="student-std-id" placeholder="Student ID">
                            </div>
                        </div>

                        {{-- Locality --}}
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Locality</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="locality" placeholder="LOCAL/INTERNATIONAL">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-9 col-sm-offset-2">
                                <div class="alert alert-danger" role="alert"></div>
                            </div>
                        </div>

                        {{-- Create Button --}}
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-9">
                                <button class="btn btn-success submit" data-url="{{ route('users.store.studentinfo', $user->id) }}">CREATE</button>
                                <a class="btn btn-info pull-right" href="{{ route('home') }}">BACK TO PREVIOUS PAGE</a>
                            </div>
                        </div>

                    </div> {{-- end form --}}
                </div> {{-- end .panel-body --}}
            </div> {{-- end .panel --}}
        </div> {{-- end .col-10 --}}
    </div> {{-- end .row --}}
</div> {{-- end .container --}}
@stop

@section('extra_js')
<script>
(function() {
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
            'student_std_id': $('#student-std-id').val(),
            'locality' : $('#locality').val(),
        }

        // send to controller
        $.ajax({
            'url': url,
            'method': 'POST',
            'data': data
        }).done(function(error) {
            window.location.href = "{{ route('home') }}"
        })
    })

}) ()
</script>
@endsection
