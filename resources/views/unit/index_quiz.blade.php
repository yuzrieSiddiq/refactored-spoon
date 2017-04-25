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
                                    <th>Quiz No</th>
                                    <th class="col-md-7">Quiz Title</th>
                                    <th>Quiz Type</th>
                                    <th>Quiz Status</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($quizzes as $quiz)
                                    <tr>
                                        <td>{{ $quiz->id }}</td>
                                        <td>{{ $quiz->title }}</td>
                                        <td>{{ $quiz->type }}</td>
                                        <td>{{ $quiz->status }}</td>
                                        <td><a href="{{ route('quizzes.edit', $quiz->id) }}" class="btn btn-primary">Edit Quiz</a></td>
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
