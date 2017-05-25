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
                                    <th colspan="2">
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
                                    <td class="chart-canvas chart-individual">
                                        <div class="row">
                                            <div class="col-md-7 chart-individual-questions">
                                                <h4 class="text-center">
                                                    INDIVIDUAL <br>
                                                    <small id="student-id">STUDENT ID</small></small>
                                                </h4>
                                                <canvas class="hidden" id="studentChart"></canvas>
                                            </div>
                                            <div class="col-md-5 chart-individual-score">
                                                <h4 class="text-success hidden passed-status">PASS</h4>

                                                <h4>RANK</h4>
                                                <h3 id="studentRank"></h3>

                                                <h4>SCORE</h4>
                                                <canvas class="hidden" id="studentScore"></canvas>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="chart-canvas chart-group">
                                        <div class="row">
                                            <div class="col-md-7 chart-group-questions">
                                                <h4 class="text-center">GROUP <span class="group-no"></span></h4>
                                                <canvas class="hidden" id="groupChart"></canvas>
                                            </div>
                                            <div class="col-md-5 chart-group-score">
                                                <h4 class="text-success hidden passed-status">PASS</h4>

                                                <h4>RANK</h4>
                                                <h3 id="groupRank"></h3>

                                                <h4>SCORE</h4>
                                                <canvas class="hidden" id="groupScore"></canvas>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <h4 class="text-center">Student Attempts Quiz Report <small>Overall Students</small></h4>
                            <canvas id="attemptChart" width="fill" height="150"></canvas>
                        </div>

                        <div class="col-md-6">
                            <h4 class="text-center">Quiz Passing Rate</h4>
                            <canvas id="passingRateChart" width="fill" height="150"></canvas>
                        </div>
                    </div>
                    <div class="row">
                        <hr>
                        <div class="col-md-6">
                            <h4 class="text-center">INDIVIDUAL RANKS</h4>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th class="col-sm-2">STD ID</th>
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
                        <div class="col-md-6">
                            <h4 class="text-center">GROUP RANKS</h4>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th class="col-sm-1">GROUP</th>
                                            <th class="col-sm-7">NAME</th>
                                            <th class="col-sm-1">RANK</th>
                                            <th class="col-sm-1">SCORE</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($rankings as $ranking)
                                            <tr>
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
                </div>
            </div>

        </div> {{-- end .col --}}
    </div> {{-- end .row --}}
</div> {{-- end .container --}}
@endsection

@section('extra_js')
<script src="{{ asset('js/moment.min.js') }}"></script>
<script src="{{ asset('js/Chart.min.js') }}"></script>
<script src="{{ asset('js/chart.piecelabel.min.js') }}"></script>
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

    Chart.pluginService.register({
        afterUpdate: function (chart) {
            if (chart.config.options.elements.center) {
                var helpers = Chart.helpers;
                var centerConfig = chart.config.options.elements.center;
                var globalConfig = Chart.defaults.global;
                var ctx = chart.chart.ctx;

                var fontStyle = helpers.getValueOrDefault(centerConfig.fontStyle, globalConfig.defaultFontStyle);
                var fontFamily = helpers.getValueOrDefault(centerConfig.fontFamily, globalConfig.defaultFontFamily);

                if (centerConfig.fontSize)
                    var fontSize = centerConfig.fontSize;
                // figure out the best font size, if one is not specified
                else {
                    ctx.save();
                    var fontSize = helpers.getValueOrDefault(centerConfig.minFontSize, 1);
                    var maxFontSize = helpers.getValueOrDefault(centerConfig.maxFontSize, 256);
                    var maxText = helpers.getValueOrDefault(centerConfig.maxText, centerConfig.text);

                    do {
                        ctx.font = helpers.fontString(fontSize, fontStyle, fontFamily);
                        var textWidth = ctx.measureText(maxText).width;

                        // check if it fits, is within configured limits and that we are not simply toggling back and forth
                        if (textWidth < chart.innerRadius * 2 && fontSize < maxFontSize)
                            fontSize += 1;
                        else {
                            // reverse last step
                            fontSize -= 1;
                            break;
                        }
                    } while (true)
                    ctx.restore();
                }

                // save properties
                chart.center = {
                    font: helpers.fontString(fontSize, fontStyle, fontFamily),
                    fillStyle: helpers.getValueOrDefault(centerConfig.fontColor, globalConfig.defaultFontColor)
                };
            }
        },
        afterDraw: function (chart) {
            if (chart.center) {
                var centerConfig = chart.config.options.elements.center;
                var ctx = chart.chart.ctx;

                ctx.save();
                ctx.font = chart.center.font;
                ctx.fillStyle = chart.center.fillStyle;
                ctx.textAlign = 'center';
                ctx.textBaseline = 'middle';
                var centerX = (chart.chartArea.left + chart.chartArea.right) / 2;
                var centerY = (chart.chartArea.top + chart.chartArea.bottom) / 2;
                ctx.fillText(centerConfig.text, centerX, centerY);
                ctx.restore();
            }
        },
    })

    let attemptChart = new Chart($('#attemptChart'), {
        type: 'pie',
        data: {
            labels: ["Unattempted", "Pass", "Fail"],
            datasets: [{
                data: [{{ $unattempted_count }}, {{ $pass_count }}, {{ $fail_count }}],
                backgroundColor: [
                    'rgba(99, 107, 111, 0.5)',
                    'rgba(42, 178, 123, 0.5)',
                    'rgba(255, 99, 132, 0.5)',
                ],
                borderColor: [
                    'rgba(99, 107, 111, 1)',
                    'rgba(42, 178, 123, 1)',
                    'rgba(255,99,132,1)',
                ],
                borderWidth: 1
            }]
        },
        options: {
            legend: {
                display: true
            },
            pieceLabel: {
                mode: 'percentage',
                fontSize: 14,
            }
        },
    })

    let passingRate = {{ $pass_count * 100 / ($pass_count + $fail_count) }}
    let failingRate = 100 - passingRate;
    let passingRateChart = new Chart($('#passingRateChart'), {
        type: 'doughnut',
        data: {
            labels: ["Pass", "Fail"],
            datasets: [{
                data: [passingRate, failingRate,],
                backgroundColor: [
                    'rgba(66, 139, 202, 0.8)',
                    'rgba(66, 139, 202, 0.2)',
                ],
                borderColor: [
                    'rgba(66, 139, 202, 1)',
                    'rgba(66, 139, 202, 1)',
                ],
                borderWidth: 1
            }]
        },
        options: {
            legend: {
                display: false
            },
            rotation: 1 * Math.PI,
            elements: {
                center: {
                    maxText: '100%',
                    text: Math.round(passingRate) + "%",
                    fontColor: '#36A2EB',
                    fontFamily: "'Helvetica Neue', 'Helvetica', 'Arial', sans-serif",
                    fontStyle: 'normal',
                    minFontSize: 1,
                    maxFontSize: 32,
                }
            }
        },
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

            //* INDIVIDUAL *//
            $('.chart-individual-questions').find('#studentChart').remove()
            $('.chart-individual-score').find('#studentScore').remove()
            $('.chart-canvas').find('iframe').remove()
            $('.chart-canvas').prop('width', 200)

            $('.chart-individual-questions').append('<canvas class="hidden" id="studentChart" width="200" height="200"></canvas>')
            $('.chart-individual-score').append('<canvas class="hidden" id="studentScore" width="100" height="100"></canvas>')

            let studentChart = new Chart($('#studentChart'), {
                type: 'pie',
                data: {
                    labels: ["Correct", "Wrong"],
                    datasets: [{
                        data: [response['individual']['correct'], response['individual']['wrong']],
                        backgroundColor: [
                            'rgba(42, 178, 123, 0.4)',
                            'rgba(255, 99, 132, 0.4)',
                        ],
                        borderColor: [
                            'rgba(42, 178, 123, 1)',
                            'rgba(255,99,132,1)',
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: false,
                    maintainAspectRatio: true,
                    legend: {
                        display: true,
                    },
                }
            })
            $('#studentChart').removeClass('hidden')

            let studentScore = new Chart($('#studentScore'), {
                type: 'doughnut',
                data: {
                    labels: ["Score", "100%"],
                    datasets: [{
                        data: [response['individual']['score'], response['individual']['remaining_score']],
                        backgroundColor: [
                            'rgba(66, 139, 202, 0.8)',
                            'rgba(66, 139, 202, 0.2)',
                        ],
                        borderColor: [
                            'rgba(66, 139, 202, 1)',
                            'rgba(66, 139, 202, 1)',
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: false,
                    maintainAspectRatio: true,
                    legend: {
                        display: false
                    },
                    rotation: 1 * Math.PI,
                    elements: {
                        center: {
                            maxText: '100%',
                            text: Math.round(response['individual']['score']) + "%",
                            fontColor: '#36A2EB',
                            fontFamily: "'Helvetica Neue', 'Helvetica', 'Arial', sans-serif",
                            fontStyle: 'normal',
                            minFontSize: 1,
                            maxFontSize: 256,
                        }
                    }
                }
            })
            $('#studentScore').removeClass('hidden')
            $('#studentRank').text(response['individual']['rank'] + '/' + response['individual']['last_rank'])
            $('#student-id').text(response['individual']['student_std_id'])

            if (response['individual']['pass'] == false) {
                $('.chart-individual-score').find('.passed-status').text('FAIL')
                $('.chart-individual-score').find('.passed-status').removeClass('text-success')
                $('.chart-individual-score').find('.passed-status').addClass('text-danger')
            } else {
                $('.chart-individual-score').find('.passed-status').text('PASS')
                $('.chart-individual-score').find('.passed-status').removeClass('text-danger')
                $('.chart-individual-score').find('.passed-status').addClass('text-success')
            }
            $('.chart-individual-score').find('.passed-status').removeClass('hidden')

            //* END INDIVIDUAL *//

            //* GROUP *//
            $('.chart-group-questions').find('#groupChart').remove()
            $('.chart-group-score').find('#groupScore').remove()
            $('.chart-canvas').find('iframe').remove()
            $('.chart-canvas').prop('width', 200)

            $('.chart-group-questions').append('<canvas class="hidden" id="groupChart" width="200" height="200"></canvas>')
            $('.chart-group-score').append('<canvas class="hidden" id="groupScore" width="100" height="100"></canvas>')

            let groupChart = new Chart($('#groupChart'), {
                type: 'pie',
                data: {
                    labels: ["Correct", "Wrong"],
                    datasets: [{
                        data: [response['group']['correct'], response['group']['wrong']],
                        backgroundColor: [
                            'rgba(42, 178, 123, 0.4)',
                            'rgba(255, 99, 132, 0.4)',
                        ],
                        borderColor: [
                            'rgba(42, 178, 123, 1)',
                            'rgba(255,99,132,1)',
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: false,
                    maintainAspectRatio: true,
                    legend: {
                        display: true
                    },
                }
            })

            $('#groupChart').removeClass('hidden')
            let groupScore = new Chart($('#groupScore'), {
                type: 'doughnut',
                data: {
                    labels: ["Score", "100%"],
                    datasets: [{
                        data: [response['group']['score'], response['group']['remaining_score']],
                        backgroundColor: [
                            'rgba(66, 139, 202, 0.8)',
                            'rgba(66, 139, 202, 0.2)',
                        ],
                        borderColor: [
                            'rgba(66, 139, 202, 1)',
                            'rgba(66, 139, 202, 1)',
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: false,
                    maintainAspectRatio: true,
                    legend: {
                        display: false
                    },
                    rotation: 1 * Math.PI,
                    elements: {
                        center: {
                            maxText: '100%',
                            text: Math.round(response['group']['score']) + "%",
                            fontColor: '#36A2EB',
                            fontFamily: "'Helvetica Neue', 'Helvetica', 'Arial', sans-serif",
                            fontStyle: 'normal',
                            minFontSize: 1,
                            maxFontSize: 256,
                        }
                    }
                }
            })
            $('#groupScore').removeClass('hidden')
            $('#groupRank').text(response['group']['rank'] + '/' + response['group']['last_rank'])

            if (response['group']['pass'] == false) {
                $('.chart-group-score').find('.passed-status').text('FAIL')
                $('.chart-group-score').find('.passed-status').removeClass('text-success')
                $('.chart-group-score').find('.passed-status').addClass('text-danger')
            } else {
                $('.chart-group-score').find('.passed-status').text('PASS')
                $('.chart-group-score').find('.passed-status').removeClass('text-danger')
                $('.chart-group-score').find('.passed-status').addClass('text-success')
            }
            $('.chart-group-score').find('.passed-status').removeClass('hidden')
            $('.chart-group-questions').find('.group-no').text(response['group']['group_no'])

            //* END GROUP *//
        })
    })
}) ()
</script>
@endsection
