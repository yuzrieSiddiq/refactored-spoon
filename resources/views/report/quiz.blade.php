@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-offset-1 col-md-10">

            <div class="panel">
                <div class="panel-body">
                    <h4 class="text-center">Student Attempts Quiz Report</h4>
                    <canvas id="attemptChart" width="fill" height="150"></canvas>
                </div>
            </div>

            <div class="panel">
                <div class="panel-body">
                    <h4 class="text-center">Student Status Report</h4>
                    <div class="table-responsive">
                        <table class="table table-bordered" id="student-report-chart">
                            <thead>
                                <tr>
                                    <th class="col-sm-2">Student ID</th>
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
                                            <a href="#" class="list-group-item active student">4300000</a>
                                            <a href="#" class="list-group-item student">4300001</a>
                                            <a href="#" class="list-group-item student">4300002</a>
                                            <a href="#" class="list-group-item student">4300003</a>
                                        </div>
                                    </td>
                                    <td>
                                        <canvas id="studentChart" width="fill" height="150"></canvas>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
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
                                <tr>
                                    <td>4300000</td>
                                    <td>1</td>
                                    <td>Uevuvuveuve Ossas</td>
                                    <td>100/100</td>
                                    <td>100%</td>
                                </tr>
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
                data: [12, 19, 3, 5],
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

    let studentChart = new Chart($('#studentChart'), {
        type: 'bar',
        data: {
            labels: ["Correct", "Wrong"],
            datasets: [{
                data: [12, 19],
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
                        beginAtZero:true
                    }
                }]
            }
        }
    })
}) ()
</script>
@endsection
