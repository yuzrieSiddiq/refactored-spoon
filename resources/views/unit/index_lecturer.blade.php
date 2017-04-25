@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-offset-1 col-md-10">

            <div class="panel panel-default">
                <div class="panel-heading">
                    UNIT LISTING
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th class="col-md-2">Unit Code</th>
                                    <th class="col-md-6">Unit Name</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($units as $unit)
                                    <tr>
                                        <td>{{ $unit->unit->code }}</td>
                                        <td>{{ $unit->unit->name }}</td>
                                        <td><a href="{{ route('units.students.index', $unit->unit_id) }}" class="btn btn-info">View Students</a></td>
                                        <td><a href="{{ route('units.quizzes.index', $unit->unit_id) }}" class="btn btn-primary">Manage Quizzes</a></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div> {{-- end .col --}}
    </div> {{-- end .row --}}
</div> {{-- end .container --}}
@endsection
