@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-offset-1 col-md-10">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h4>REPORT <small>
                        {{ $this_student_info->student_id }}
                        {{ $this_student->user->firstname }} {{ $this_student->user->lastname }}
                    </small></h4>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th class="col-md-5">QUIZ TITLE</th>
                                <th class="col-md-1">TYPE</th>
                                <th class="col-md-1">RANK</th>
                                <th class="col-md-1">SCORE</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($quizzes as $quiz)
                                @foreach ($ranking_asc as $ranker)
                                    @if ($this_student->id == $ranker['student_id'] && $ranker['quiz_id'] == $quiz->id)
                                        <tr>
                                            <td>{{ $quiz->title }}</td>
                                            <td><span class="text-capitalize">{{ $quiz->type }}</span></td>
                                            <td>{{ $ranker['rank_no'] }} / {{ $all_students->count() }}</td>
                                            <td>{{ $ranker['score'] }} %</td>
                                        </tr>
                                    @endif
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="panel-footer">
                    <a class="btn btn-info" href="{{ route('units.show', $quiz->unit->id) }}">BACK TO PREVIOUS PAGE</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
