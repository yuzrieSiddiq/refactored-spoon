@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        {{-- MAIN PANEL --}}
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="panel-title">REPORT</div>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th class="col-md-2">Unit Code</th>
                                    <th class="col-md-6">Unit Name</th>
                                    <th class="col-md-2">Student Count</th>
                                    <th class="col-md-2">Quizzes Count</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (isset($units))
                                    @foreach ($units as $unit)
                                        <tr>
                                            <td>{{ $unit->code }}</td>
                                            <td>{{ $unit->name }}</td>
                                            <td>{{ $unit->students->count() }}</td>
                                            <td>{{ $unit->quizzes->count() }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('extra_js')
<script src="{{ asset('js/papaparse.min.js') }}"></script>
<script>
(function() {
    let getToken = function() {
        return $('meta[name=csrf-token]').attr('content')
    }

    $('.process').click(function() {
        let file = {}
        let data = {}
        let url = ""
        let method = "POST"

        if ($(this).data('upload') == 'lecturers') {
            inputfile = $('#lecturers')
            url = '{{ route('csv.lecturers') }}'
        } else if ($(this).data('upload') == 'students') {
            inputfile = $('#students')
            url = '{{ route('csv.students') }}'
        } else if ($(this).data('upload') == 'questions') {
            inputfile = $('#questions')
            url = '{{ route('csv.questions') }}'
        } else {
            console.log('something is wrong with the data-attribute for this button')
        }

        // do the csv file parsing
        Papa.parse(inputfile[0].files[0], {
            complete: function(results) {
                let jsonstring = JSON.stringify(results.data)

                // after complete parsing, send to controller via ajax
                data = {
                    '_token': getToken(),
                    'file': jsonstring
                }

                $.ajax({
                    'url': url,
                    'method': method,
                    'data': data,
                    'enctype': 'multipart/form-data'
                }).done(function(response) {
                    console.log(response)
                })
            }
        })
    })
})()
</script>
@endsection
