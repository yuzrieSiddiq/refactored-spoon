@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-offset-1 col-md-10">
            <div class="panel panel-default">
                <div class="panel-heading">
                    CREATE NEW UNIT
                </div>
                <div class="panel-body">
                    <div class="form-horizontal">
                        {{-- Code --}}
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Code</label>
                            <div class="col-sm-9">
                                <input class="form-control" id="code" placeholder="Unit Code">
                            </div>
                        </div>

                        {{-- Name --}}
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Name</label>
                            <div class="col-sm-9">
                                <input class="form-control" id="name" placeholder="Unit Name">
                            </div>
                        </div>

                        {{-- Description --}}
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Description</label>
                            <div class="col-sm-9">
                                <textarea class="form-control" id="description" rows="3"></textarea>
                            </div>
                        </div>

                        {{-- Update Button --}}
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-9">
                                <button class="btn btn-success submit">Create</button>
                                <a href="{{ route('units.index') }}" class="btn btn-primary pull-right">Back to Previous Page</a>
                            </div>
                        </div>
                    </div> {{-- end .form-horizontal --}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('extra_js')
<script>
(function(){
    // Get CSRF token
    let getToken = function() {
        return $('meta[name=csrf-token]').attr('content')
    }

    $('.submit').click(function() {
        let data = {
            '_token': getToken(),
            'code'  : $('#code').val(),
            'name'  : $('#name').val(),
            'description' : $('#description').text(),
        }

        $.ajax({
            'url': '{{ route('units.store') }}',
            'method': 'POST',
            'data': data
        }).done(function(response) {
            if (response == '1') {
                let errormsg = 'A unit with that code already exist'
                showErrorMessage(errormsg)
            } else if (response == '2') {
                let errormsg = 'A unit with that name already exist'
                showErrorMessage(errormsg)
            } else {
                window.location.href = "{{ route('units.index') }}"
            }
        })
    })
}) ()
</script>
@endsection
