@extends('layouts.app_login')

@section('content')
<div class="container" style="margin-top: 15%;">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-body">
                    <h3 class="text-center">Welcome to Team Based Learning</h3>
                    <br>
                    <div class="row">
                        <div class="col-md-4 col-md-offset-1">
                            <p class="text-center qr-tag">
                                <p class="text-center">
                                    {!! QrCode::size(150)->generate(asset('android/TBL.apk')) !!}
                                </p>
                                <p class="text-center">Download the student app by scanning the QR code above</p>
                                <p class="text-center"><small>Cannot scan? <a href="{{ asset('android/TBL.apk') }}">Download here</a></small></p>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <br><br>
                            <form class="form-horizontal" role="form" method="POST" action="{{ route('login') }}">
                                {{ csrf_field() }}

                                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                    <label for="email" class="col-md-3 control-label">E-Mail</label>

                                    <div class="col-md-9">
                                        <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>

                                        @if ($errors->has('email'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('email') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                    <label for="password" class="col-md-3 control-label">Password</label>

                                    <div class="col-md-9">
                                        <input id="password" type="password" class="form-control" name="password" required>

                                        @if ($errors->has('password'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('password') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-md-8 col-md-offset-4">
                                        <button type="submit" class="btn btn-primary col-md-6 col-md-offset-2">
                                            Login
                                        </button>
                                    </div>
                                    <div class="col-md-7 col-md-offset-4">
                                        <a class="btn btn-link" href="{{ route('password.request') }}">
                                            Forgot Your Password?
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{--
    <div class="modal fade" id="download-apk-modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">

                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-horizontal" style="margin-top: 50px; margin-bottom: 50px;">
                                <div class="form-group">
                                    <h4 class="col-md-12 text-center">Please enter your student credentials</h4>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">E-Mail</label>

                                    <div class="col-md-9">
                                        <input id="student-email" type="email" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-3 control-label">Password</label>

                                    <div class="col-md-9">
                                        <input id="student-password" type="password" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-md-7 col-md-offset-4">
                                        <button class="btn btn-primary col-md-6 col-md-offset-2" id="student-login">
                                            Login
                                        </button>
                                        <div class="loader pull-right vcenter hidden" style="margin-top: 6px;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-qr" hidden>
                            <p class="text-center qr-tag">
                                <p class="text-center">
                                    {!! QrCode::size(150)->generate(asset('android/TBL.apk')) !!}
                                </p>
                                <p class="text-center"><small>Please scan the QR Code</small></p>

                                <hr>
                                <a class="btn btn-primary form-control" href="{{ asset('android/test.apk') }}">Download apk here</a>
                                <p class="text-center"><small>Cannot scan? Click the button above</small></p>
                            </p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
     --}}
</div>
@endsection

@section('extra_js')
<script type="text/javascript">
(function() {
    let getToken = function() {
        return $('meta[name=csrf-token]').attr('content')
    }

    $('#student-login').click(function() {
        let url = "{{ route('login') }}"
        let method = "POST"
        let data = {
            '_token': getToken(),
            'email': $('#student-email').val(),
            'password': $('#student-password').val(),
        }

        $.ajax({
            'url': url,
            'method': method,
            'data': data
        }).done(function(response) {
            if (response == 'logged in') {
                $('.col-qr').show(450).delay(5000)

                // logout
                let next_url = "{{ route('logout') }}"
                let next_method = "POST"
                let next_data = { '_token': getToken() }

                $.ajax({
                    'url': next_url,
                    'method': next_method,
                    'data': next_data
                }).done(function() {
                    if (response == 'logged out') {
                        console.log('good')
                    }
                })
            }
        })
    })
})()
</script>
@endsection
