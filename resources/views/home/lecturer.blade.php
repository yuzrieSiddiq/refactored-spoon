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
