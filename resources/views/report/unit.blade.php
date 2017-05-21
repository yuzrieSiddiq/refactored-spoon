@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-offset-1 col-md-10">

            <div class="panel panel-heading">
                <div class="panel-heading">
                    unit report
                </div>
                <div class="panel-body">
                    empty body
                </div>
            </div>

        </div> {{-- end .col --}}
    </div> {{-- end .row --}}
</div> {{-- end .container --}}
@endsection

@section('extra_js')
<script src="{{ asset('js/moment.min.js') }}"></script>
<script src="{{ asset('js/Chart.min.js') }}"></script>
<script>

</script>
@endsection
