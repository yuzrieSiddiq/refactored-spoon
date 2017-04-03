@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-offset-1 col-md-10">
            <div class="panel panel-default">
                <div class="panel-body">
                    {{-- Role name --}}
                    <h4 class="text-uppercase">MANAGE USER DETAILS</h4>
                    <hr>

                    <div class="form-horizontal">
                        {{-- Name --}}
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Name</label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control" id="first_name" placeholder="First Name" value="{{ $user->first_name }}">
                            </div>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" id="last_name" placeholder="Last Name" value="{{ $user->last_name }}">
                            </div>
                        </div>

                        {{-- Email --}}
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Email</label>
                            <div class="col-sm-9">
                                <input type="email" class="form-control" id="email" placeholder="example@email.com" value="{{ $user->email }}">
                            </div>
                        </div>

                        {{-- Roles --}}

                        <div class="form-group">
                            <div class="col-sm-9 col-sm-offset-2">
                                <div class="alert alert-danger" role="alert"></div>
                            </div>
                        </div>

                        {{-- Update Button --}}
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-9">
                                <button class="btn btn-success submit">Update</button>
                                <a href="{{ route('users.index') }}" class="btn btn-primary pull-right">Back to Previous Page</a>
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

    let selectRole = $('#role')
    selectRole.data('prev', selectRole.val())
    $('#' + selectRole.data('prev')).removeClass('hidden')

    selectRole.change(function() {
        let previousOption = $(this)
        $('#' + previousOption.data('prev')).addClass('hidden')
        previousOption.data('prev', previousOption.val())

        let option = $(this).find('option:selected').val()
        $('#' + option).removeClass('hidden')
    })

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
            'role': role,
            'username'  : $('#username').val(),
            'first_name': $('#first_name').val(),
            'last_name' : $('#last_name').val(),
            'password'  : $('#password').val(),
            'email': $('#email').val()
        }

        $.ajax({
            'url': '{{ route('users.update', $user->id) }}',
            'method': 'PUT',
            'data': data
        }).done(function(error) {
            if (error == '1') {
                let errormsg = 'A user with that username already exist'
                showErrorMessage(errormsg)
            } else if (error == '2') {
                let errormsg = 'A user with that email already exist'
                showErrorMessage(errormsg)
            } else {
                window.location.href = "{{ route('users.index') }}"
            }
        })
    })

    $('.remove').click(function() {
        let data = { '_token': getToken() }

        $.ajax({
            'url': '{{ route('users.destroy', $user->id) }}',
            'method': 'DELETE',
            'data': data
        }).done(function(data) {
            window.location.href = "{{ route('users.index') }}"
        })
    })
}) ()
</script>
@endsection
