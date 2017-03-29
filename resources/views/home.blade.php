@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    You are logged in!
                </div>
                <div class="panel-footer">
                    <div class="row">
                        <div class="col-md-8">
                            <input type="file" id="excel">
                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-success process">PROCESS</button>
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

    let papaparseafile = function(file) {
        let jsonstring = ''

    }

    $('.process').click(function() {
        // do the ajax after parsing complete
        Papa.parse($('#excel')[0].files[0], {
            complete: function(results) {
                jsonstring = JSON.stringify(results.data)

                // after complete parsing, send to controller via ajax
                let data = {
                    '_token': getToken(),
                    'file': jsonstring
                }

                $.ajax({
                    'url': '{{ route('excel.import') }}',
                    'method': 'POST',
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
