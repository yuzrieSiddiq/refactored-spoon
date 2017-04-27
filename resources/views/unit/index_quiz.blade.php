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
                                    <th class="col-md-5">Quiz Title</th>
                                    <th>Quiz Type</th>
                                    <th>Quiz Status</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (isset($quizzes))
                                    @foreach ($quizzes as $quiz)
                                        <tr>
                                            <td>{{ $quiz->id }}</td>
                                            <td>{{ $quiz->title }}</td>
                                            <td>{{ $quiz->type }}</td>
                                            <td>{{ $quiz->status }}</td>
                                            <td>
                                                <a href="{{ route('quizzes.edit', $quiz->id) }}" class="btn btn-primary">Edit Quiz</a>
                                                <a class="btn btn-info manage" href="{{ route('quizzes.questions.index', $quiz->id)}}">Manage Questions</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div> {{-- end .panel-body --}}
                <div class="panel-footer">
                    <a class="btn btn-info" href="{{ route('units.lecturer') }}">BACK TO PREVIOUS PAGE</a>
                    <a class="btn btn-success pull-right" href="{{ route('units.quizzes.create', $unit->id) }}">
                        CREATE NEW QUIZ
                    </a>
                </div> {{-- end .panel-footer --}}
            </div>

        </div> {{-- end .col --}}
    </div> {{-- end .row --}}
</div> {{-- end .container --}}
@endsection
