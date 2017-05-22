@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">

            <div class="panel">
                <div class="panel-heading">
                    <a class="btn btn-info" href="{{ route('units.show', $quiz->unit_id) }}">BACK TO PREVIOUS PAGE</a>
                </div>

                <div class="panel-body">
                    <h4 class="text-center">Student Status Report <small>Overall Questions</small></h4>
                    <div class="table-responsive">
                        <table class="table table-bordered" id="student-report-chart">
                            <thead>
                                <tr>
                                    <th class="col-sm-4">Student</th>
                                    <th>
                                        <div class="input-group col-sm-4 pull-right">
                                            <input class="form-control" id="search-student" placeholder="SEARCH">
                                            <span class="input-group-btn">
                                                <button class="btn btn-default" id="student">
                                                    <span class="glyphicon glyphicon-search"></span>
                                                </button>
                                            </span>
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="list-group">
                                            @foreach ($attempts as $attempt)
                                                @if ($attempt['attempted'])
                                                    <a class="list-group-item clickable student" data-student-id="{{ $attempt['student_id'] }}" data-quiz-id="{{ $quiz->id }}">
                                                        {{ $attempt['student_std_id'] }} {{ $attempt['student_name'] }}
                                                    </a>
                                                @endif
                                            @endforeach
                                        </div>
                                    </td>
                                    <td class="chart-canvas">
                                        <canvas class="hidden" id="studentChart" width="fill" height="150"></canvas>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="panel">
                <div class="panel-body">
                    <h4 class="text-center">Student Attempts Quiz Report <small>Overall Students</small></h4>
                    <canvas id="attemptChart" width="fill" height="150"></canvas>
                </div>
            </div>

            <div class="panel">
                <div class="panel-body">
                    <h4 class="text-center">Ranking</h4>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th class="col-sm-2">STUDENT ID</th>
                                    <th class="col-sm-1">GROUP</th>
                                    <th class="col-sm-7">NAME</th>
                                    <th class="col-sm-1">RANK</th>
                                    <th class="col-sm-1">SCORE</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($rankings as $ranking)
                                    <tr>
                                        <td>{{ $ranking->student->user->student_info->student_id }}</td>
                                        <td>{{ $ranking->student->team_number }}</td>
                                        <td>{{ $ranking->student->user->firstname }} {{ $ranking->student->user->lastname }}</td>
                                        <td>{{ $ranking->rank_no }}/{{ $rankings->last()->rank_no }}</td>
                                        <td>{{ $ranking->score }}%</td>
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

@section('extra_js')
<script src="{{ asset('js/moment.min.js') }}"></script>
<script src="{{ asset('js/Chart.min.js') }}"></script>
<script>
(function() {
    // Get CSRF token
    let getToken = function() {
        return $('meta[name=csrf-token]').attr('content')
    }

    // Search
    $("#search-student").keyup(function() {
       // when something is typed in the box, it will hide all
       let searchvalue = $(this).val().toLowerCase()
       $('.student').hide()
       // if the text from the tr matches any part of the search value (indexOf), show
       $('.student').each(function() {
           let text = $(this).text().toLowerCase()
           if (text.indexOf(searchvalue) != -1)
               $(this).show()
       })
    })

    let attemptChart = new Chart($('#attemptChart'), {
        type: 'bar',
        data: {
            labels: ["Attempted", "Unattempted", "Pass", "Fail"],
            datasets: [{
                data: [{{ $attempted_count }}, {{ $unattempted_count }}, {{ $pass_count }}, {{ $fail_count }}],
                backgroundColor: [
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(99, 107, 111, 0.2)',
                    'rgba(42, 178, 123, 0.2)',
                    'rgba(255, 99, 132, 0.2)',
                ],
                borderColor: [
                    'rgba(54, 162, 235, 1)',
                    'rgba(99, 107, 111, 1)',
                    'rgba(42, 178, 123, 1)',
                    'rgba(255,99,132,1)',
                ],
                borderWidth: 1
            }]
        },
        options: {
            legend: {
                display: false
            },
            scales: {
                xAxes: [{
                    gridLines: {
                        color: "rgba(0, 0, 0, 0)",
                    }
                }],
                yAxes: [{
                    gridLines: {
                        color: "rgba(0, 0, 0, 0)",
                    },
                    ticks: {
                        beginAtZero:true
                    }
                }]
            }
        }
    })

    $('.student').click(function() {
        let student_id = $(this).data('student-id')
        let quiz_id = $(this).data('quiz-id')
        let url = '{{ route('units.students.report', ['student' => 'student_id', 'quiz' => 'quiz_id']) }}'
        url = url.replace('student_id', student_id)
        url = url.replace('quiz_id', quiz_id)

        // console.log(url)
        $.ajax({
            'url': url,
            'method': 'POST',
            'data': { '_token': getToken()}
        }).done(function(response) {
            $('.chart-canvas').find('#studentChart').remove()
            $('.chart-canvas').find('iframe, h4').remove()
            $('.chart-canvas').append('<h4 class="text-center">'+ response['student_std_id'] + " " + response['student_name'] +'</h4>')
            $('.chart-canvas').append('<canvas class="hidden" id="studentChart" width="fill" height="175"></canvas>')

            let studentChart = new Chart($('#studentChart'), {
                type: 'bar',
                data: {
                    labels: ["Correct", "Wrong"],
                    datasets: [{
                        data: [response['correct'], response['wrong']],
                        backgroundColor: [
                            'rgba(42, 178, 123, 0.2)',
                            'rgba(255, 99, 132, 0.2)',
                        ],
                        borderColor: [
                            'rgba(42, 178, 123, 1)',
                            'rgba(255,99,132,1)',
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    legend: {
                        display: false
                    },
                    scales: {
                        xAxes: [{
                            gridLines: {
                                color: "rgba(0, 0, 0, 0)",
                            }
                        }],
                        yAxes: [{
                            gridLines: {
                                color: "rgba(0, 0, 0, 0)",
                            },
                            ticks: {
                                max: response['total_questions'],
                                beginAtZero:true
                            }
                        }]
                    }
                }
            })
            $('#studentChart').removeClass('hidden')
        })
    })
}) ()
</script>
@endsection
