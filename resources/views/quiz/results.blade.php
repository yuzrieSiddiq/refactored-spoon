@extends('layouts.app')
@section('extra_head')
    <link rel="stylesheet" href="{{ asset('css/datatables.bootstrap.css') }}">
@endsection
@section('content')
<div class="container">

    {{-- 1st part - overall quiz results --}}
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4>
                <a class="btn btn-info" href="{{ route('quizzes.edit', $quiz_individual->id) }}">BACK TO PREVIOUS PAGE</a>
                <div class="pull-right">
                    <div class="form-inline">
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-btn">
                                    <button class="btn btn-default dropdown-toggle" data-toggle="dropdown">Filter Type <span class="caret"></span></button>
                                    <ul class="dropdown-menu">
                                        <li><a href="#" class="select-type" data-type="Team">Team</a></li>
                                        <li><a href="#" class="select-type" data-type="Question">Question</a></li>
                                        <li><a href="#" class="select-type" data-type="Tutorial Group">Tutorial Group</a></li>
                                    </ul>
                                </div>
                                <input type="text" class="form-control selected-type" disabled placeholder="View Results by:">
                            </div>
                        </div>
                    </div>
                </div>
            </h4>
        </div>

        <div class="panel-body">
            <div class="table-responsive">
                <table id="ranking-table" class="table table-striped">
                    <thead>
                        <tr>
                            <th width="5%" class="text-center">I-Rank</th>
                            <th width="5%" class="text-center">T-Rank</th>
                            <th width="5%" class="text-center">Group</th>
                            <th width="5%" class="text-center">Team</th>
                            <th width="10%" class="text-center">Student ID</th>
                            <th width="10%" class="text-center">Student Name</th>
                            <th width="5%" class="text-center">I Score</th>
                            <th width="5%" class="text-center">T Score</th>
                            <th width="10%"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($rankings as $count => $ranking)
                            <tr class="text-center">
                                <td>{{ $ranking->rank_no }}</td>
                                <td>{{ $ranking->t_rank_no }}</td>
                                <td>{{ $ranking->student['group_number'] }}</td>
                                <td>{{ $ranking->student['team_number'] }}</td>
                                <td>{{ $ranking->student['user']['student_info']['student_id'] }}</td>
                                <td>{{ $ranking->student['user']['firstname'] }} {{ $ranking->student['user']['lastname'] }}</td>
                                <td>{{ $ranking->score }}</td>
                                <td>{{ $ranking->t_score }}</td>
                                <td>
                                    <button class="btn btn-primary questions-modal" data-id="{{ $ranking->id }}" data-student-id="{{ $ranking->student['user']['student_info']['student_id'] }}"
                                        data-group-number="{{ $ranking->student['group_number'] }}" data-team-number="{{ $ranking->student['team_number'] }}"
                                        data-std-id="{{ $ranking->student['id'] }}"
                                        data-name="{{ $ranking->student['user']['firstname'] }} {{ $ranking->student['user']['lastname'] }}"
                                        data-route="{{ route('results.get.answers', ['quiz' => $quiz->id, 'student' => $ranking->student['id']]) }}">
                                        Details
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            {{-- 2nd part - compare results between teams (group_leaders) --}}
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>Teams Comparison</h4>
                </div>
                <div class="panel-body">
                    <canvas id="teams-chart" width="fill" height="250"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            {{-- 4th part - compare results between students in different groups --}}
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>Tutorial Groups Comparisons</h4>
                </div>
                <div class="panel-body">
                    <canvas id="groups-chart" width="fill" height="250"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- 3rd part - compare results by questions --}}
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4>Questions Comparison</h4>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="table-responsive">
                        <table id="questions-table" class="table table-striped">
                            <thead>
                                <tr>
                                    <th width="2%"></th>
                                    <th width="15%" class="text-center">Questions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($questions as $count => $question)
                                    <tr>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-primary question">{{ $count+1 }}</button>
                                        </td>
                                        <td>{{ $question->question }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div> {{-- end col-6 --}}

                <div class="col-md-6">
                    <h4>Individual</h4>
                    <canvas id="individual-results-chart" width="fill" height="150"></canvas>
                    <h4>Group</h4>
                    <canvas id="group-results-chart" width="fill" height="150"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Details Modal --}}
<div class="modal fade modal-template" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Modal Template</h4>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-striped" width="100%">
                        <thead>
                            <tr>
                                <th class="text-center" width="5%">No.</th>
                                <th width="75%">Question</th>
                                <th class="text-center" width="20%">Individual Answer</th>
                                <th class="text-center" width="20%">Team Answer</th>
                            </tr>
                        </thead>
                        <tbody class="table-body">
                            <tr class="tbody-template hidden">
                                <td class="question-number text-center"></td>
                                <td class="question"></td>
                                <td class="individual-answer text-center"></td>
                                <td class="team-answer text-center"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <button type="button" class="btn btn-default cancel-modal" data-dismiss="modal">Cancel</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

{{-- loading modal --}}
<div class="modal fade" id="loading" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-body">
              <div class="form-horizontal">
                  {{-- Code --}}
                  <div class="form-group">
                      <label class="col-xs-2 control-label text-align-right"><div class="loader"></div></label>
                      <div class="col-xs-9">
                          <h4 class="form-control-static">Loading Please Wait</h4>
                      </div>
                  </div>
              </div>
          </div>
        </div>
    </div>
</div>
{{-- end loading modal --}}
@endsection

@section('extra_js')
<script src="{{ asset('js/datatables.net.js') }}"></script>
<script src="{{ asset('js/datatables.bootstrap.js') }}"></script>
<script src="{{ asset('js/Chart.min.js') }}"></script>
<script src="{{ asset('js/chart.piecelabel.min.js') }}"></script>
<script type="text/javascript">
(function() {
    let getToken = function () {
        return $('meta[name=csrf-token]').attr('content')
    }

    $('.select-type').click(function() {
        $('.selected-type').val($(this).data('type'))
    })

    $('#ranking-table').DataTable({
        'columnDefs': [{
            'targets': -1,
            'orderable': false
        }]
    })
    $('#questions-table').DataTable({
        'sort' : false,
        'lengthMenu': [ [5, 10, 25, 50], [5, 10, 25, 50] ],
        'pageLength': 5
    })

    $('.questions-modal').click(function() {
        let id  = $(this).data('id')
        let name  = $(this).data('name')
        let route = $(this).data('route')
        let modal = $('.modal-template').clone().removeClass('modal-template');

        modal.prop('id', 'delete-modal-' + id)
        modal.find('.modal-title').text(name)

        let data = {
            '_token': getToken(),
            'student_id': $(this).data('std-id'),
            'team_number': $(this).data('team-number'),
            'group_number': $(this).data('group-number'),
        }
        $.ajax({
            'url': route,
            'method': 'POST',
            'data': data,
        }).done(function(response) {
            console.log(response)
            for (let i = 0; i < response.length; i++) {
                let text_color = ""
                let t_text_color = ""

                if (response[i]['answer'] == "4 POINTS")        text_color = 'text-success'
                else if (response[i]['answer'] == "2 POINTS")   text_color = 'text-primary'
                else if (response[i]['answer'] == "1 POINTS")   text_color = 'text-warning'
                else                                            text_color = 'text-danger'

                if (response[i]['team_answer'] == "4 POINTS")       t_text_color = 'text-success'
                else if (response[i]['team_answer'] == "2 POINTS")  t_text_color = 'text-primary'
                else if (response[i]['team_answer'] == "1 POINTS")  t_text_color = 'text-warning'
                else                                                t_text_color = 'text-danger'

                let tbody_template = modal.find('.tbody-template').clone()
                tbody_template.removeClass('tbody-template hidden')
                tbody_template.find('.question-number').text(i+1)
                tbody_template.find('.question').text(response[i]['question']['question'])
                tbody_template.find('.individual-answer').text(response[i]['answer'])
                tbody_template.find('.individual-answer').addClass(text_color)
                tbody_template.find('.team-answer').text(response[i]['team_answer'])
                tbody_template.find('.team-answer').addClass(t_text_color)
                modal.find('.table-body').append(tbody_template)
            }

            // transform to datatable
            let table = modal.find('table')
            table.DataTable({
                'pageLength': 10,
                'paging': true,
                'searching': false,
                'sort': false,
            })
        })

        $('.container').append(modal)

        modal.modal('toggle')
        modal.on('hidden.bs.modal', function (e) {
            $(this).remove()
        })
    })

    // 2nd part
    let teams_ctx = document.getElementById("teams-chart").getContext('2d');
    let teams_chart = new Chart(teams_ctx, {
        type: 'bar',
        data: {
            labels: ['Team 1', 'Team 2', 'Team 3', 'Team 4', 'Team 5', 'Team 6'],
            datasets: [{
                label: '# of Votes',
                data: [12, 19, 3, 5, 2, 3],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.8)',
                    'rgba(54, 162, 235, 0.8)',
                    'rgba(255, 206, 86, 0.8)',
                    'rgba(75, 192, 192, 0.8)',
                    'rgba(153, 102, 255, 0.8)',
                    'rgba(255, 159, 64, 0.8)'
                ],
                borderColor: [
                    'rgba(255,99,132,1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            maintainAspectRatio: false,
            legend: { display: false },
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero:true
                    }
                }]
            }
        }
    })

    // 3rd part
    let groups_ctx = document.getElementById("groups-chart").getContext('2d');
    let groups_chart = new Chart(groups_ctx, {
        type: 'bar',
        data: {
            labels: ['Group 1', 'Group 2', 'Group 3', 'Group 4'],
            datasets: [
                {
                    label: 'Pass',
                    backgroundColor: 'rgba(54, 162, 235, 0.8)',
                    data: [4,3,5,4] // passed students
                },
                {
                    label: 'Fail',
                    backgroundColor: 'rgba(255, 99, 132, 0.8)',
                    data: [3,7,4,8] // failed students
                },
            ]
        },
        options: {
            maintainAspectRatio: false,
            legend: { display: false },
            scales: {
                xAxes: [{
                    barPercentage: 1.0,
                }],
                yAxes: [{
                    ticks: {
                        beginAtZero:true
                    }
                }]
            }
        }
    });

    // 4th part - compare results between students in different groups (delegated function for dynamic content)
    $('#questions-table').on('click', '.question',function () {
        let individual_results_ctx = document.getElementById("individual-results-chart").getContext('2d');
        let individual_results_chart = new Chart(individual_results_ctx, {
            type: 'pie',
            data: {
                labels: ['4 Points', '2 Points', '1 Point', '0 Point'],
                datasets: [{
                    data: [10, 20, 15, 25],
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.8)',
                        'rgba(54, 162, 235, 0.8)',
                        'rgba(255, 206, 86, 0.8)',
                        'rgba(255, 99, 132, 0.8)',
                    ],
                }],
            }
        });

        let groups_results_ctx = document.getElementById("group-results-chart").getContext('2d');
        let groups_results_chart = new Chart(groups_results_ctx, {
            type: 'pie',
            data: {
                labels: ['4 Points', '2 Points', '1 Point', '0 Point'],
                datasets: [{
                    data: [10, 20, 15, 25],
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.8)',
                        'rgba(54, 162, 235, 0.8)',
                        'rgba(255, 206, 86, 0.8)',
                        'rgba(255, 99, 132, 0.8)',
                    ],
                }],
            }
        });
    })
})()
</script>
@endsection
