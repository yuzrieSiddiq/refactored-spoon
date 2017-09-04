@extends('layouts.app')
@section('extra_head')
    <link rel="stylesheet" href="{{ asset('css/datatables.bootstrap.css') }}">
@endsection
@section('content')
<div class="container">
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
                            <th width="10%" class="text-center">Ranking</th>
                            <th width="10%" class="text-center">Group No</th>
                            <th width="10%" class="text-center">Team No</th>
                            <th width="10%" class="text-center">Student ID</th>
                            <th width="10%" class="text-center">Student Name</th>
                            <th width="10%" class="text-center">Score</th>
                            <th width="10%"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($rankings as $count => $ranking)
                            <tr class="text-center">
                                <td>{{ $ranking->rank_no }}</td>
                                <td>{{ $ranking->student['group_number'] }}</td>
                                <td>{{ $ranking->student['team_number'] }}</td>
                                <td>{{ $ranking->student['user']['student_info']['student_id'] }}</td>
                                <td>{{ $ranking->student['user']['firstname'] }} {{ $ranking->student['user']['lastname'] }}</td>
                                <td>{{ $ranking->score }}</td>
                                <td>
                                    <button class="btn btn-primary questions-modal" data-id="{{ $ranking->id }}"
                                        data-name = "{{ $ranking->student['user']['firstname'] }} {{ $ranking->student['user']['lastname'] }}"
                                        data-route="{{ route('results.get.answers', ['quiz' => $quiz_individual->id, 'student' => $ranking->student['id']]) }}">
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
</div>

{{-- Delete Modal --}}
<div class="modal fade modal-template" id="questions-modal" tabindex="-1" role="dialog">
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
                                <th class="text-center" width="20%">Student Answer</th>
                            </tr>
                        </thead>
                        <tbody class="table-body">
                            <tr class="tbody-template hidden">
                                <td class="question-number text-center"></td>
                                <td class="question"></td>
                                <td class="std-answer text-center"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <button type="button" class="btn btn-default cancel-modal" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger delete">DELETE</button>
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

    $('.questions-modal').click(function() {
        let id  = $(this).data('id')
        let name  = $(this).data('name')
        let route = $(this).data('route')
        let modal = $('.modal-template').clone().removeClass('questions-modal');

        modal.prop('id', 'delete-modal-' + id)
        modal.find('.modal-title').text(name)

        $.ajax({
            'url': route,
            'method': 'POST',
            'data': { '_token': getToken() }
        }).done(function(response) {
            for (let i = 0; i < response.length; i++) {
                let text_color = ""

                if (response[i]['answer'] == "4 POINTS")        text_color = 'text-success'
                else if (response[i]['answer'] == "2 POINTS")   text_color = 'text-primary'
                else if (response[i]['answer'] == "1 POINTS")   text_color = 'text-warning'
                else                                            text_color = 'text-danger'

                let tbody_template = modal.find('.tbody-template').clone()
                tbody_template.removeClass('tbody-template hidden')
                tbody_template.find('.question-number').text(i+1)
                tbody_template.find('.question').text(response[i]['question']['question'])
                tbody_template.find('.std-answer').text(response[i]['answer'])
                tbody_template.find('.std-answer').addClass(text_color)
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
})()
</script>
@endsection
