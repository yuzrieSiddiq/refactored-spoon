@extends('layouts.app')

@section('content')
<div class="container">
    <div class="form-group">
        <div class="col-sm-10 col-sm-offset-1">
            <div class="alert alert-success" role="alert"></div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-offset-1 col-md-10">
            <div class="panel panel-default">
                <div class="panel-body">
                    {{-- Role name --}}
                    <h4 class="text-uppercase">CHANGE PASSWORD</h4>
                    <hr>

                    <div class="form-horizontal">
                        {{-- Password --}}
                        <div class="form-group">
                            <label class="col-sm-2 control-label">New Password</label>
                            <div class="col-sm-9">
                                <input type="password" class="form-control" id="new-password">
                            </div>
                        </div>

                        {{-- Password --}}
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Confirm Password</label>
                            <div class="col-sm-9">
                                <input type="password" class="form-control" id="confirm-password">
                            </div>
                        </div>

                        {{-- Update Button --}}
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-9">
                                <button class="btn btn-success submit" disabled="true">Update</button>
                                <a href="{{ route('home') }}" class="btn btn-primary pull-right">Back to Previous Page</a>
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
    let showErrorMessage = function(message)  {
        $('.alert').text(message)
        $('.alert').show(450).delay(8000).slideUp(450)
    }

    $('#confirm-password').keyup(function () {
        let new_password = $('#new-password').val()
        let confirmed_password = $(this).val()

        if (confirmed_password == new_password) {
            $('.submit').prop('disabled', false)
        } else {
            $('.submit').prop('disabled', true)
        }
    })

    $('.submit').click(function() {
        let url = $(this).data('url')

        let data = {
            '_token': getToken(),
            'password'  : $('#confirm-password').val(),
        }

        $.ajax({
            'url': '{{ route('users.update.password', $user->id) }}',
            'method': 'PUT',
            'data': data
        }).done(function(error) {
            let success_msg = 'Password updated successfully'
            showErrorMessage(success_msg)
        })
    })
}) ()
</script>
@endsection
