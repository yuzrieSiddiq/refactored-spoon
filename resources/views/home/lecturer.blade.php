@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        {{-- MAIN PANEL --}}
        <div class="col-md-12">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>Welcome, {{ Auth::user()->firstname }} {{ Auth::user()->lastname }}</h4>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th class="col-md-1">Unit Code</th>
                                    <th class="col-md-4">Unit Name</th>
                                    <th class="text-center">Student Count</th>
                                    <th class="text-center">Quizzes Count</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (!empty($units))
                                    @foreach ($units as $unit)
                                        <tr>
                                            <td>{{ $unit->code }}</td>
                                            <td>{{ $unit->name }}</td>
                                            <td class="text-center">{{ $unit->students->count() }}</td>
                                            <td class="text-center">{{ $unit->quizzes->count() }}</td>
                                            <td>
                                                <div class="pull-right">
                                                    <a class="btn btn-success" href="{{ route('units.show', $unit->id) }}">VIEW UNIT</a>
                                                    {{-- <a class="btn btn-primary" href="{{ route('units.report', $unit->id) }}">REPORT</a> --}}
                                                    {{-- <a href="{{ route('units.students.index', $unit->id) }}" class="btn btn-info">Student List</a>
                                                    <a href="{{ route('units.quizzes.index', $unit->id) }}" class="btn btn-primary">Quizzes</a> --}}
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5" class="text-center">No units assigned to this lecturer</td>
                                    </tr>
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
