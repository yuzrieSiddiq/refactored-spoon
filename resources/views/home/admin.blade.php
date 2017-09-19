@extends('layouts.app')

@section('extra_head')
    <link rel="stylesheet" href="{{ asset('css/datatables.bootstrap.css') }}">
@endsection

@section('content')
<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading">
            DASHBOARD
        </div>
        <div class="panel-body">
            <div> {{-- tabs --}}
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"><a href="#unit-list" role="tab" data-toggle="tab">Unit List</a></li>
                    <li role="presentation"><a href="#user-list" role="tab" data-toggle="tab">User List</a></li>
                    <li role="presentation"><a href="#assign-lecturer-list" role="tab" data-toggle="tab">Assign Lecturer</a></li>
                    <li role="presentation"><a href="#settings" role="tab" data-toggle="tab">Settings</a></li>
                 </ul>
            </div>

            <div class="tab-content">
                {{-- Unit List --}}
                <div role="tabpanel" class="tab-pane fade in active" id="unit-list">
                    <div class="table-responsive">
                        <table class="table table-striped" id="units-table" width="100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Code</th>
                                    <th>Name</th>
                                    <th></th>
                                </tr>
                            </thead>
                        </table>
                    </div> {{-- end .table-responsive --}}

                    <a class="btn btn-success" href="{{ route('units.create') }}">
                        CREATE NEW UNIT
                    </a>
                </div>

                {{-- User List --}}
                <div role="tabpanel" class="tab-pane fade" id="user-list">
                    <div class="table-responsive">
                        <table class="table table-striped" id="users-table" width="100%">
                            <thead>
                                <tr>
                                    <th class="hidden">ID</th>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th></th>
                                </tr>
                            </thead>
                        </table>
                    </div> {{-- end .table-responsive --}}

                    <div class="form-horizontal">
                        <div class="form-group">
                            <div class="col-sm-12">
                                <div class="alert alert-danger alert-no-bottom-margin" role="alert" hidden></div>
                            </div>
                        </div>
                        <label class="btn btn-primary btn-file">
                            UPLOAD LECTURERS LIST (.CSV) <input class="file-upload" type="file" style="display: none;">
                        </label>
                        <a class="btn btn-success" href="{{ route('users.create') }}">
                            CREATE NEW USER
                        </a>
                    </div>

                </div>

                {{-- Unit Info --}}
                <div role="tabpanel" class="tab-pane fade" id="assign-lecturer-list">
                    <div class="table-responsive">
                        <table class="table table-striped" id="l_units-table" width="100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Code</th>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th></th>
                                </tr>
                            </thead>
                        </table>
                    </div> {{-- end .table-responsive --}}

                    <a class="btn btn-success" href="{{ route('l_units.create') }}">
                        ADD NEW LECTURER/UNIT
                    </a>
                </div>

                {{-- Settings --}}
                <div role="tabpanel" class="tab-pane fade" id="settings">
                    <div class="form-horizontal">
                        <h3 class="col-sm-offset-1">System Settings</h3><br>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Teaching Period</label>
                            <div class="col-sm-8">
                                @if (isset($semester))
                                    <input type="text" class="form-control" id="settings-semester" value="{{ $semester->value }}" placeholder="S1">
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Year</label>
                            <div class="col-sm-8">
                                @if (isset($year))
                                    <input type="text" class="form-control" id="settings-year" value="{{ $year->value }}" placeholder="2017">
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-8">
                              <button class="btn btn-success settings-update-btn">Update</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div> {{-- end .tab-content --}}
        </div> {{-- end .panel-body --}}
    </div> {{-- end .panel --}}

    {{-- Delete Modal --}}
    <div class="modal fade modal-template" id="delete-modal-id" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">
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
</div>
@endsection

@section('extra_js')
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/datatables.net.js') }}"></script>
<script src="{{ asset('js/datatables.bootstrap.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/papaparse.min.js') }}"></script>
<script>
(function() {
    $(function () {
      $('[data-toggle="tooltip"]').tooltip()
    })

    let getToken = function() {
        return $('meta[name=csrf-token]').attr('content')
    }

    // Tab 1: Unit DataTable
    let table_operations1 = '\
        <div class="pull-right">\
        <button class="btn btn-info studentlist" data-url="{{ route('units.students.index', "id")}}"data-toggle="tooltip" data-placement="top" title="View Student List"><span class="glyphicon glyphicon-list-alt"></span></button>\
        <button class="btn btn-success edit" data-url="{{ route('units.edit', "id")}}"data-toggle="tooltip" data-placement="top" title="Edit Unit"><span class="glyphicon glyphicon-pencil"></span></button>\
        <button class="btn btn-danger modal-remove" data-toggle="modal"data-toggle="tooltip" data-placement="top" title="Delete Unit"><span class="glyphicon glyphicon-remove"></span></button>\
        </div>'

    let table1 = $('#units-table').DataTable( {
        "ajax": "{{ route('get.units.datatable') }}",
        "columnDefs": [
            {
                // hide id
                "targets": 0,
                "visible": false,
            },
            {
            "targets": -1,
            "data": null,
            "defaultContent": table_operations1
        } ]
    })

    $('#units-table tbody').on( 'click', '.studentlist', function () {
        // get the row data
        let data = table1.row( $(this).parents('tr') ).data()

        // get the url
        let url = $(this).data('url').replace('id', data[0])

        // redirect using href
        window.location.href = url;
    })

    $('#units-table tbody').on( 'click', '.edit', function () {
        // get the row data
        let data = table1.row( $(this).parents('tr') ).data()

        // get the url
        let url = $(this).data('url').replace('id', data[0])

        // redirect using href
        window.location.href = url;
    })

    $('#units-table tbody').on( 'click', '.modal-remove', function () {
        let data = table1.row( $(this).parents('tr') ).data()
        let id = data[0];

        // clone the template to make new modal
        let modal = $('.modal-template').clone().removeClass('modal-template');
        modal.prop('id', 'delete-modal-' + data[0])

        // replacing values inside the specified classes
        let modal_title = modal.find('.modal-title').text('Remove ' + data[1]);

        // register the modal to the body and toggle the modal
        $('.container').append(modal)
        modal.modal('toggle')

        // set the destroy url
        let url_destroy = '{{ route('units.destroy', 'id') }}'
        url_destroy = url_destroy.replace('id', id)


        modal.find('.delete').click(function() {
            $.ajax({
                'url': url_destroy,
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
    })

    // Tab 2: User List
    let table_operations2 = '\
        <div class="pull-right">\
        <button class="btn btn-info change-password" data-url="{{ route('users.edit.password', "id")}}" data-toggle="tooltip" data-placement="top" title="Change Password"><span class="glyphicon glyphicon-lock"></span></button>\
        <button class="btn btn-success manage" data-url="{{ route('users.edit', "id")}}" data-toggle="tooltip" data-placement="top" title="Manage User"><span class="glyphicon glyphicon-user"></span></button>\
        <button class="btn btn-danger modal-remove" data-toggle="tooltip" data-placement="top" title="Remove"><span class="glyphicon glyphicon-remove" data-toggle="modal"></span></button>\
        </div>'

    let table2 = $('#users-table').DataTable( {
        "ajax": "{{ route('get.users.datatable') }}",
        "columnDefs": [
            {
                // hide id
                "targets": 0,
                "visible": false,
            },
            {
                "targets": -1,
                "data": null,
                "defaultContent": table_operations2
            }
        ],
        "columns": [
            null,
            { "width": "20%" },
            { "width": "20%" },
            { "width": "25%" },
            { "width": "15%" },
            { "width": "20%" },
        ]
    })

    $('#users-table tbody').on( 'click', '.manage', function () {
        // get the row data
        let data = table2.row( $(this).parents('tr') ).data()

        // get the url
        let url = $(this).data('url').replace('id', data[0])

        // redirect using href
        window.location.href = url;
    } );

    $('#users-table tbody').on( 'click', '.change-password', function () {
        // get the row data
        let data = table2.row( $(this).parents('tr') ).data()

        // get the url
        let url = $(this).data('url').replace('id', data[0])

        // redirect using href
        window.location.href = url;
    } );

    $('#users-table tbody').on( 'click', '.modal-remove', function () {
        let data = table2.row( $(this).parents('tr') ).data()
        let id = data[0];

        // clone the template to make new modal
        let modal = $('.modal-template').clone().removeClass('modal-template');
        modal.prop('id', 'delete-modal-' + data[0])

        // replacing values inside the specified classes
        let modal_title = modal.find('.modal-title').text('Remove ' + data[1]);

        // register the modal to the body and toggle the modal
        $('.container').append(modal)
        modal.modal('toggle')

        // set the destroy url
        let url_destroy = '{{ route('users.destroy', 'id') }}'
        url_destroy = url_destroy.replace('id', id)


        modal.find('.delete').click(function() {
            $.ajax({
                'url': url_destroy,
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
    })

    // show alert if error
    let showErrorMessage = function(errormsg)  {
        $('.alert').text(errormsg)
        $('.alert').show(450).delay(8000).slideUp(450)
    }

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

                $('#loading').modal('toggle')
                $.ajax({
                    'url': '{{ route('csv.lecturers') }}',
                    'method': 'POST',
                    'data': data,
                    'enctype': 'multipart/form-data'
                }).done(function(response) {
                    if (response == "Error_H01") {
                        let errormsg = 'The file has wrong format/headers please ensure .csv file has the correct format'
                        showErrorMessage(errormsg)
                    } else {
                        $('#loading').modal('toggle')
                        window.location.reload()
                    }
                })
            }
        })
    })

    // Tab 3: Assign Lecturer-Unit
    let table_operations3 = '<div class="pull-right">\
        <button class="btn btn-danger modal-remove" data-toggle="tooltip" data-placement="top" title="Unassign This Unit">\
            <span class="glyphicon glyphicon-remove" data-toggle="modal"></span>\
        </button></div>';

    let table3 = $('#l_units-table').DataTable( {
        "ajax": "{{ route('get.l_units.datatable') }}",
        "columnDefs": [
            {
                // hide id
                "targets": 0,
                "visible": false,
            },
            {
            "targets": -1,
            "data": null,
            "defaultContent": table_operations3
        } ]
    })

    $('#l_units-table tbody').on( 'click', '.modal-remove', function () {
        let data = table3.row( $(this).parents('tr') ).data()
        let id = data[0];

        // clone the template to make new modal
        let modal = $('.modal-template').clone().removeClass('modal-template');
        modal.prop('id', 'delete-modal-' + data[0])

        // replacing values inside the specified classes
        let modal_title = modal.find('.modal-title').text('Remove ' + data [2] + ' ' + data[3] + ' from teaching ' + data[1]);

        // register the modal to the body and toggle the modal
        $('.container').append(modal)
        modal.modal('toggle')

        // set the destroy url
        let url_destroy = '{{ route('l_units.destroy', 'id') }}'
        url_destroy = url_destroy.replace('id', id)


        modal.find('.delete').click(function() {
            $.ajax({
                'url': url_destroy,
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
    })

    $('.settings-update-btn').click(function () {
        let data = {
            '_token': getToken(),
            'settings-semester': $('#settings-semester').val(),
            'settings-year': $('#settings-year').val()
        }

        $('#loading').modal('toggle')
        $.ajax({
            'url': '{{ route('settings.update') }}',
            'method': 'PUT',
            'data': data
        }).done(function() {
            // show the loading screen
            setTimeout(function () {
                $('#loading').modal('toggle')
            }, 3000)
            window.location.reload()
        })
    })

})()
</script>
@endsection
