@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-offset-1 col-md-10">
            <div class="panel panel-default">
                <div class="panel-heading">
                    QUIZ CREATE
                </div>
                <div class="panel-body">
                    <div class="form-horizontal">
                        {{-- Unit --}}
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Unit</label>
                            <div class="col-sm-9">
                                @if (!isset($unit))
                                    <select class="form-control unit-code">
                                        @foreach ($units as $unit)
                                            <option value="{{ $unit->code }}">{{ $unit->code }} {{ $unit->name }}</option>
                                        @endforeach
                                    </select>
                                @else
                                    <input class="form-control" disabled value="{{ $unit->code }} {{ $unit->name }}">
                                    <input class="unit-code" hidden value="{{ $unit->code }}">
                                @endif
                            </div>
                        </div>

                        {{-- Semester --}}
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Semester</label>
                            <div class="col-sm-9">
                                <input class="form-control semester" placeholder="i.e: S1">
                            </div>
                        </div>

                        {{-- Year --}}
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Year</label>
                            <div class="col-sm-9">
                                <input class="form-control year" placeholder="i.e: 2017">
                            </div>
                        </div>

                        {{-- Title --}}
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Title</label>
                            <div class="col-sm-9">
                                <input class="form-control title" placeholder="Quiz Title">
                            </div>
                        </div>

                        {{-- Update Button --}}
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-9">
                                <a class="btn btn-info" href="{{ route('units.show', $unit->id) }}">BACK TO PREVIOUS PAGE</a>
                                <button class="btn btn-success submit pull-right" data-url="{{ route('quizzes.store') }}">SUBMIT</button>
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
<script type="text/javascript">
(function() {
    let getToken = function() {
        return $('meta[name=csrf-token]').attr('content')
    }

    $('.submit').click(function () {
        let data = {
            '_token': getToken(),
            'unit_code': $('.unit-code').val(),
            'semester': $('.semester').val(),
            'year': $('.year').val(),
            'title': $('.title').val(),
        }
        $.ajax({
            'url': $(this).data('url'),
            'method': 'POST',
            'data': data
        }).done(function() {
            window.location.href = '{{ route('units.show', $unit->id) }}'
        })
    })
})()
</script>
@endsection
