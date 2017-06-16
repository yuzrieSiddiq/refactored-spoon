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
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Student ID</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="student-std-id" placeholder="Student ID" value="{{ $user->student->student_id }}">
                            </div>
                        </div>

                        {{-- Locality --}}
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Locality</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="password" placeholder="LOCAL/INTERNATIONAL"  value="{{ $user->student->nationality }}">
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
                                <button class="btn btn-success submit" data-url="{{ route('users.update.studentinfo') }}">UPDATE</button>
                                <a class="btn btn-info pull-right" href="{{ route('home') }}">BACK TO PREVIOUS PAGE</a>
                            </div>
                        </div>

                    </div> {{-- end .form-horizontal --}}

                </div> {{-- end .panel-body --}}
            </div> {{-- end .panel --}}

            @if ($user->hasRole('Lecturer'))
                <div class="panel panel-default">
                    <div class="panel-body">
                        <h4>ASSIGN UNITS TO LECTURER</h4>
                        <div class="form-horizontal">
                            <div class="form-group">
                                <div class="col-md-9">
                                    <select class="form-control new-unit">
                                        @if (isset($availableunits))
                                            @foreach ($availableunits as $unit)
                                                <option value="{{ $unit->id }}">{{ $unit->code }} {{ $unit->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <button class="btn btn-success form-control assign">ASSIGN THIS UNIT</button>
                                </div>
                            </div>
                        </div>
                        <hr>

                        @if (count($lecturerunits) > 0)
                            <h4>CURRENTLY ASSIGNED UNITS</h4>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Unit Code</th>
                                        <th>Unit Name</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($lecturerunits as $unit)
                                        <tr>
                                            <td>{{ $unit->unit->code }}</td>
                                            <td>{{ $unit->unit->name }}</td>
                                            <td>
                                                <div class="text-center">
                                                    <button class="btn btn-danger"><span class="glyphicon glyphicon-remove"></span></button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div> {{-- end .panel --}}
            @endif

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
            'student_std_id'  : $('#student-std-id').val(),
            'locality': $('#locality').val()
        }

        $.ajax({
            'url': '{{ route('users.update', $user->id) }}',
            'method': 'PUT',
            'data': data
        }).done(function(error) {
            window.location.href = "{{ route('users.index') }}"
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

    $('.assign').click(function() {
        let data = {
            '_token': getToken(),
            'user_id': '{{ $user->id }}',
            'unit_id': $('.new-unit').val(),
        }

        $.ajax({
            'url': '{{ route('l_units.store') }}',
            'method': 'POST',
            'data': data,
        }).done(function(response) {
            window.location.reload()
        })
    })
}) ()
</script>
@endsection
