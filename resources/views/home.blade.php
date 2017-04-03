@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-offset-1 col-md-10">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    You are logged in!
                </div>
            </div>
        </div>

        <div class="col-md-offset-1 col-md-10">
            <div class="row">
                <div class="col-md-4">
                    <div class="panel panel-primary">
                        <div class="panel-heading">LECTURERS</div>
                        <div class="panel-body">
                            <input type="file" id="lecturers">
                        </div>
                        <div class="panel-footer">
                            <button class="btn btn-primary process form-control" data-upload="lecturers">PROCESS</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="panel panel-info">
                        <div class="panel-heading">STUDENTS</div>
                        <div class="panel-body">
                            <input type="file" id="students">
                        </div>
                        <div class="panel-footer">
                            <button class="btn btn-info process form-control" data-upload="students">PROCESS</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="panel panel-success">
                        <div class="panel-heading">QUESTIONS</div>
                        <div class="panel-body">
                            <input type="file" id="questions">
                        </div>
                        <div class="panel-footer">
                            <button class="btn btn-success process form-control" data-upload="questions">PROCESS</button>
                        </div>
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
