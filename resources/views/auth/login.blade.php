@extends('layouts.app_login')

@section('content')
<div class="container" style="margin-top: 15%;">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-body">
                    <h3 class="text-center">Welcome to Semester Quiz</h3>
                    <br>
                    <div class="row">
                        <div class="col-md-offset-1 col-md-4">
                            <p class="text-center">
                                <img class="hidden-sm hidden-xs" src="http://lorempixel.com/200/200" width="100%" style="margin-bottom:4px;">
                                <a class="btn btn-primary form-control" href="{{ asset('android/test.apk') }}">Download apk here</a>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <br>
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

                    <p class="text-center">
                        <br>
                        <small>Are you a student? <span class="hidden-xs hidden-sm">Scan the QR Code above or</span> click the button to download the apk for android</small>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
