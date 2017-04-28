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
                        {{-- Title --}}
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Title</label>
                            <div class="col-sm-9">
                                <input class="form-control title" placeholder="Quiz Title">
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

                        {{-- Type --}}
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Type</label>
                            <div class="col-sm-9">
                                <select class="form-control type">
                                    <option value="individual">Individual</option>
                                    <option value="group">Group</option>
                                </select>
                            </div>
                        </div>

                        {{-- Status --}}
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Status</label>
                            <div class="col-sm-9">
                                <select class="form-control status">
                                    <option value="open">Open</option>
                                    <option value="close">Close</option>
                                </select>
                            </div>
                        </div>

                        {{-- Upload --}}
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Upload</label>
                            <div class="col-sm-9">
                                <input class="file-upload" type="file">
                            </div>
                        </div>

                        {{-- Update Button --}}
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-9">
                                <button class="btn btn-success submit pull-right" data-url="{{ route('quizzes.store.upload') }}">SUBMIT</button>
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
<script src="{{ asset('js/papaparse.min.js') }}"></script>
<script type="text/javascript">
(function() {
    let getToken = function() {
        return $('meta[name=csrf-token]').attr('content')
    }

    $('.submit').click(function () {
        // do the csv file parsing
        Papa.parse($('.file-upload').prop('files')[0], {
            complete: function(results) {
                let jsonstring = JSON.stringify(results.data)
                // after complete parsing, send to controller via ajax
                let data = {
                    '_token': getToken(),
                    'title': $('.title').val(),
                    'semester': $('.semester').val(),
                    'year': $('.year').val(),
                    'type': $('.type').val(),
                    'status': $('.status').val(),
                    'file': jsonstring
                }
                $.ajax({
                    'url': '{{ route('quizzes.store.upload') }}',
                    'method': 'POST',
                    'data': data,
                    'enctype': 'multipart/form-data'
                }).done(function(response) {
                    window.location.href = '#'
                })
            }
        })
    })
})()
</script>
@endsection
