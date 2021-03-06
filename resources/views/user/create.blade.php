@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-offset-1 col-md-10">
            <div class="panel panel-default">
                <div class="panel-body">
                    <h3>CREATE NEW USER</h3>
                    <hr>

                    <div class="form-horizontal">
                        {{-- Name --}}
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Name</label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control" id="first_name" placeholder="First Name">
                            </div>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" id="last_name" placeholder="Last Name">
                            </div>
                        </div>

                        {{-- Password --}}
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Password</label>
                            <div class="col-sm-9">
                                <input type="password" class="form-control" id="password" placeholder="******">
                            </div>
                        </div>

                        {{-- Email --}}
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Email</label>
                            <div class="col-sm-9">
                                <input type="email" class="form-control" id="email" placeholder="example@email.com">
                            </div>
                        </div>

                        {{-- Roles --}}
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Role</label>
                            <div class="col-sm-9">
                                <select class="form-control text-capitalize" id="role">
                                    @foreach ($availableroles as $role)
                                        <option value="{{ $role->name }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-9 col-sm-offset-2">
                                <div class="alert alert-danger" role="alert"></div>
                            </div>
                        </div>

                        {{-- Update Button --}}
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-9">
                                <button class="btn btn-success submit" data-url="{{ route('users.store') }}">CREATE</button>
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

    $('.submit').click(function() {
        let url = $(this).data('url')
        let role = selectRole.val()

        let data = {
            '_token': getToken(),
            'role': role,
            'first_name': $('#first_name').val(),
            'last_name' : $('#last_name').val(),
            'password'  : $('#password').val(),
            'email': $('#email').val()
        }

        // send to controller
        $.ajax({
            'url': url,
            'method': 'POST',
            'data': data
        }).done(function(response) {
            if (response == 'Error 1') {
                let errormsg = 'The user with that username already exist'
                showErrorMessage(errormsg)
            } else if (response == 'Error 2') {
                let errormsg = 'The user with that email already exist'
                showErrorMessage(errormsg)
            } else {
                // check if student, add additional info
                if (role == 'Student') {
                    window.location.href = response
                } else {
                    window.location.href = "{{ route('home') }}"
                }
            }
        })
    })

}) ()
</script>
@endsection
