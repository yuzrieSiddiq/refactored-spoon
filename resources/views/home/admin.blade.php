@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-offset-1 col-md-10">
            <div class="panel panel-primary">
                <div class="panel-heading">LECTURERS</div>
                <div class="panel-body">
                    -
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
})()
</script>
@endsection
