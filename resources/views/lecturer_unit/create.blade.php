@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-offset-1 col-md-10">
            <div class="panel panel-default">
                <div class="panel-heading">
                    ASSIGN LECTURER
                </div>
                <div class="panel-body">
                    <div class="form-horizontal">
                        {{-- Lecturer --}}
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Lecturer</label>
                            <div class="col-sm-9">
                                <select class="lecturer form-control">
                                    @foreach ($lecturers as $lecturer)
                                        <option value="{{ $lecturer->id }}">{{ $lecturer->firstname }} {{ $lecturer->lastname }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Template --}}
                        <div class="form-group unit-template hidden">
                            <label class="col-sm-2 control-label">Unit</label>
                            <div class="col-sm-9">
                                <select class="form-control">
                                    @foreach ($available_units as $unit)
                                        <option value="{{ $unit->id }}">{{ $unit->code }} {{ $unit->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="additional-units">

                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Add More Unit</label>
                            <div class="col-sm-9">
                                <button class="btn btn-info add-unit"><span class="glyphicon glyphicon-plus"></span> ADD UNIT</button>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="panel-footer">
                    <button class="btn btn-success submit">SUBMIT</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('extra_js')
<script>
(function() {
    let getToken = function() {
        return $('meta[name=csrf-token]').attr('content')
    }

    $('.add-unit').click(function() {
        let additionalunit = $('.unit-template').clone().removeClass('unit-template hidden')
        additionalunit.find('.col-sm-9').find('select').addClass('unit')
        $('.additional-units').append(additionalunit)
    })

    $('.submit').click(function() {
        let units = []
        $('.unit').each(function() {
            let unit = {}
            unit['unit_id'] = $(this).val()
            units.push(unit)
        })

        let data = {
            '_token': getToken(),
            'user_id': $('.lecturer').val(),
            'units': JSON.stringify(units)
        }

        $.ajax({
            'url': '{{ route('l_units.store') }}',
            'method': 'POST',
            'data': data
        }).done(function(response) {
            // window.location.href = '{{ route('home') }}'
        })
    })
})()
</script>
@endsection
