@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-offset-1 col-md-10">
            <div class="panel panel-default">
                <div class="panel-heading">
                    REPORT
                </div>
                <div class="panel-body">
                    <ul>
                        <li>STUDENT ID: {{ $this_student_info->student_id }}</li>
                        <li>STUDENT NAME: {{ $this_student->user->firstname }} {{ $this_student->user->lastname }}</li>
                    </ul>
                </div>
            </div>

            <div class="row">
                @foreach ($quizzes as $quiz)
                    @foreach ($ranking_asc as $ranker)
                        @if ($this_student->id == $ranker['student_id'] && $ranker['quiz_id'] == $quiz->id)
                            <div class="col-md-4">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        {{ $quiz->title }} <span class="text-capitalize">{{ $quiz->type }}</span>
                                    </div>
                                    <div class="panel-body">
                                        <ul>
                                            <li>RANK: {{ $ranker['rank_no'] }} / {{ $all_students->count() }}</li>
                                            <li>SCORE: {{ $ranker['score'] }}</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
