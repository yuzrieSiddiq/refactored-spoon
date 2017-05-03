@extends('layouts.app')

@section('extra_head')
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css">
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div> {{-- tabs --}}
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active"><a href="#student-list" role="tab" data-toggle="tab">Student List</a></li>
                            <li role="presentation"><a href="#quiz-list" role="tab" data-toggle="tab">Quizzes</a></li>
                            <li role="presentation"><a href="#unit-info" role="tab" data-toggle="tab">Unit Info</a></li>
                         </ul>
                    </div>

                    <div class="tab-content">
                        {{-- Student List --}}
                        <div role="tabpanel" class="tab-pane fade in active" id="student-list">
                            <div class="table-responsive">
                                <table class="table table-striped" id="students-table">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>First Name</th>
                                            <th>Last Name</th>
                                            <th>Semester</th>
                                            <th>Year</th>
                                            <th>Team</th>
                                            <th>Leader</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                </table>
                            </div> {{-- end .table-responsive --}}

                            <button class="btn btn-success" data-target="#modal-add-student" data-toggle="modal">
                                ADD NEW STUDENT
                            </button>
                            <label class="btn btn-primary btn-file">
                                UPLOAD STUDENT LIST (.CSV) <input class="file-upload" type="file" style="display: none;">
                            </label>

                            {{-- New Student Modal --}}
                            <div class="modal fade" id="modal-add-student" tabindex="-1" role="dialog">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">ADD NEW STUDENT</h4>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-horizontal">
                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label">Student</label>
                                                    <div class="col-sm-10">
                                                        <select class="form-control select-student">
                                                            @foreach ($students as $user)
                                                                <option value="{{ $user->id}}">
                                                                    {{ $user->student_info->student_id }} {{ $user->firstname }} {{ $user->lastname }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label">Semester</label>
                                                    <div class="col-sm-10">
                                                        <input class="form-control semester">
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label">Year</label>
                                                    <div class="col-sm-10">
                                                        <input class="form-control year">
                                                    </div>
                                                </div>
                                            </div>
                                        </div> {{-- modal-body --}}
                                        <div class="modal-footer">
                                            <button class="btn btn-success add-student">ADD STUDENT</button>
                                        </div> {{-- modal-footer --}}
                                    </div><!-- /.modal-content -->
                                </div><!-- /.modal-dialog -->
                            </div><!-- /.modal -->

                            {{-- Delete Student Modal --}}
                            <div class="modal fade modal-template" id="delete-modal-id" tabindex="-1" role="dialog">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title"></h4>
                                        </div>
                                        <div class="modal-body">
                                            <button class="btn btn-default cancel-modal" data-dismiss="modal">Cancel</button>
                                            <button class="btn btn-danger delete" data-url="{{ route('units.students.destroy', ['unit' => 'unit_id', 'student' => 'student_id']) }}">
                                                DELETE
                                            </button>
                                        </div>
                                    </div><!-- /.modal-content -->
                                </div><!-- /.modal-dialog -->
                            </div><!-- /.modal -->

                        </div>
                        {{-- Quizzes --}}
                        <div role="tabpanel" class="tab-pane fade" id="quiz-list">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Quiz No</th>
                                            <th class="col-md-3">Quiz Title</th>
                                            <th>Quiz Type</th>
                                            <th>Quiz Status</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (isset($quizzes))
                                            @foreach ($quizzes as $count => $quiz)
                                                <tr>
                                                    <td class="text-center">{{ $count+1 }}</td>
                                                    <td>{{ $quiz->title }}</td>
                                                    <td>{{ $quiz->type }}</td>
                                                    <td>{{ $quiz->status }}</td>
                                                    <td class="text-center">
                                                        <a href="{{ route('quizzes.edit', $quiz->id) }}" class="btn btn-primary">Edit Quiz</a>
                                                        <a class="btn btn-info manage" href="{{ route('quizzes.questions.index', $quiz->id)}}">Manage Questions</a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                            <a class="btn btn-primary" href="{{ route('units.quizzes.create', $unit->id) }}">
                                UPLOAD NEW QUIZ
                            </a>
                            <a class="btn btn-success" href="{{ route('units.quizzes.create', $unit->id) }}">
                                CREATE NEW QUIZ
                            </a>
                        </div>

                        {{-- Unit Info --}}
                        <div role="tabpanel" class="tab-pane fade" id="unit-info">
                            <div class="form-horizontal">
                                {{-- Code --}}
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Code</label>
                                    <div class="col-sm-9">
                                        <p class="form-control-static">{{ $unit->code }}</p>
                                    </div>
                                </div>

                                {{-- Name --}}
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Name</label>
                                    <div class="col-sm-9">
                                        <p class="form-control-static">{{ $unit->name }}</p>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('extra_js')
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="//cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
<script src="{{ asset('js/papaparse.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script type="text/javascript">
(function() {
    // Bootstrap tooltip
    $(function () {
      $('[data-toggle="tooltip"]').tooltip()
    })

    // Get CSRF token
    let getToken = function() {
        return $('meta[name=csrf-token]').attr('content')
    }

    let table_operations = '\
        <div class="text-center">\
            <button class="btn btn-info make-teamleader" data-toggle="tooltip" data-placement="top" title="Make Team Leader"><span class="glyphicon glyphicon-user"></span></button>\
            <button class="btn btn-success student-report" data-toggle="tooltip" data-placement="top" title="Student Report"><span class="glyphicon glyphicon-book"></span></button>\
            <button class="btn btn-danger modal-remove" data-toggle="tooltip" data-placement="top" title="Remove Student"><span class="glyphicon glyphicon-remove" data-toggle="modal"></span></button>\
        </div>'

    let table = $('#students-table').DataTable( {
        "ajax": "{{ route('get.students.datatable', $unit->id) }}",
        "columnDefs": [
            {
                // hide student id
                "targets": 0,
                "visible": false,
            },
            {
                // yes and no for is_a_group_leader
                "targets": 6,
                "render": function(data, type, full, meta) {
                    if (data == 1) {
                        return "YES"
                    } else {
                        return "NO"
                    }
                }
            },
            {
                // add extra column
                "targets": -1,
                "data": null,
                "defaultContent": table_operations
            }
        ]
    } );


    $('#students-table tbody').on( 'click', '.modal-remove', function () {
        let data = table.row( $(this).parents('tr') ).data()
        let id = data[0];

        // clone the template to make new modal
        let modal = $('.modal-template').clone().removeClass('modal-template');
        modal.prop('id', 'delete-modal-' + data[0])

        // replacing values inside the specified classes
        let modal_title = modal.find('.modal-title').text('Remove ' + data[1] + ' ' + data[2]);

        // register the modal to the body and toggle the modal
        $('.container').append(modal)
        modal.modal('toggle')

        modal.find('.delete').click(function() {
            let url = $(this).data('url')
            url = url.replace('unit_id', {{ $unit->id }})
            url = url.replace('student_id', data[0])

            $.ajax({
                'url': url,
                'method': 'DELETE',
                'data': { '_token': getToken() }
            }).done(function() {
                window.location.reload()
            })
        })

        // destroy the modal if dismissed
        modal.on('hidden.bs.modal', function (e) {
            $(this).remove()
        })
    });

    $('#students-table tbody').on( 'click', '.student-report', function () {
        let data = table.row( $(this).parents('tr') ).data()
        let student_id = data[0];

        let url = '{{ route('units.students.show', ['unit' => 'unit_id', 'student' => 'student_id']) }}'
        url = url.replace('unit_id', {{ $unit->id }})
        url = url.replace('student_id', student_id)

        window.location.href = url
    });

    $('.add-student').click(function() {
        data = {
            '_token': getToken(),
            'student_user_id': $('.select-student').val(),
            'semester': $('.semester').val(),
            'year': $('.year').val(),
        }

        $.ajax({
            'url': '{{ route('units.students.store', $unit->id) }}',
            'method': 'POST',
            'data': data
        }).done(function(response) {
            window.location.reload()
        })
    })

    $('#students-table tbody').on( 'click', '.make-teamleader', function () {
        let data = table.row( $(this).parents('tr') ).data()

        let url = '{{ route('units.students.destroy', ['unit' => 'unit_id', 'student' => 'student_id']) }}'
        url = url.replace('unit_id', {{ $unit->id }})
        url = url.replace('student_id', data[0])
        console.log(url)

        $.ajax({
            'url': url,
            'method': 'PUT',
            'data': { '_token': getToken() }
        }).done(function(response) {
            window.location.reload()
        })
    })

    $('.file-upload').change(function() {
        // do the csv file parsing
        Papa.parse($(this).prop('files')[0], {
            complete: function(results) {
                let jsonstring = JSON.stringify(results.data)
                // after complete parsing, send to controller via ajax
                let data = {
                    '_token': getToken(),
                    'file': jsonstring
                }

                $.ajax({
                    'url': '{{ route('csv.students') }}',
                    'method': 'POST',
                    'data': data,
                    'enctype': 'multipart/form-data'
                }).done(function(response) {
                    window.location.reload()
                })
            }
        })
    })
})()
</script>
@endsection
