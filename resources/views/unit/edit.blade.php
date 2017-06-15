@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-offset-1 col-md-10">
            <div class="panel panel-default">
                <div class="panel-body">
                    <h4 class="text-uppercase">MANAGE UNIT</h4>
                    <hr>

                    <div class="form-horizontal">
                        {{-- Code --}}
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Code</label>
                            <div class="col-sm-9">
                                <input class="form-control" id="code" placeholder="ABC123" value="{{ $unit->code }}">
                            </div>
                        </div>

                        {{-- Name --}}
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Name</label>
                            <div class="col-sm-9">
                                <input class="form-control" id="name" placeholder="Unit Name" value="{{ $unit->name }}">
                            </div>
                        </div>

                        {{-- Description --}}
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Description</label>
                            <div class="col-sm-9">
                                <textarea class="form-control" id="description" rows="3">{{ $unit->description }}</textarea>
                            </div>
                        </div>

                        {{-- Content --}}
                        {{-- this require dynamic population. TODO after finish easy tasks --}}

                        {{-- Update Button --}}
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-9">
                                <button class="btn btn-success submit">UPDATE</button>
                                <a href="{{ route('units.index') }}" class="btn btn-info pull-right">BACK TO PREVIOUS PAGE</a>
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
            'code'  : $('#code').val(),
            'name'  : $('#name').val(),
            'description' : $('#description').val(),
        }

        $.ajax({
            'url': '{{ route('units.update', $unit->id) }}',
            'method': 'PUT',
            'data': data
        }).done(function(response) {
            if (response == '1') {
                let errormsg = 'A unit with that code already exist'
                showErrorMessage(errormsg)
            } else if (response == '2') {
                let errormsg = 'A unit with that name already exist'
                showErrorMessage(errormsg)
            } else {
                window.location.href = "{{ route('units.show', $unit->id) }}"
            }
        })
    })

    $('.remove').click(function() {
        let data = { '_token': getToken() }

        $.ajax({
            'url': '{{ route('units.destroy', $unit->id) }}',
            'method': 'DELETE',
            'data': data
        }).done(function(data) {
            window.location.href = "{{ route('units.index') }}"
        })
    })
}) ()
</script>
@endsection
