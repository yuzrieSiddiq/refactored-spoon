@extends('layouts.app')

@section('extra_head')
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css">
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    USERS INDEX
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered" id="users-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th></th>
                                </tr>
                            </thead>
                        </table>
                    </div> {{-- end .table-responsive --}}
                </div> {{-- end .panel-body --}}
                <div class="panel-footer">
                    <label class="btn btn-primary btn-file">
                        UPLOAD LECTURERS LIST (.CSV) <input class="file-upload" type="file" style="display: none;">
                    </label>
                    <a class="btn btn-success" href="{{ route('users.create') }}">
                        CREATE NEW USER
                    </a>
                </div> {{-- end .panel-footer --}}
            </div>
        </div>
    </div>

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
</div>
@endsection

@section('extra_js')
<script src="{{ asset('js/papaparse.min.js') }}"></script>
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="//cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script>
(function() {
    // Get CSRF token
    let getToken = function() {
        return $('meta[name=csrf-token]').attr('content')
    }

    let table_operations = '\
        <button class="btn btn-success manage" data-url="{{ route('users.edit', "id")}}">Manage</button>\
        <button class="btn btn-danger modal-remove" data-toggle="modal">Remove</button>';

    let table = $('#users-table').DataTable( {
        "ajax": "{{ route('get.users.datatable') }}",
        "columnDefs": [ {
            "targets": -1,
            "data": null,
            "defaultContent": table_operations
        } ]
    } );

    $('#users-table tbody').on( 'click', '.manage', function () {
        // get the row data
        let data = table.row( $(this).parents('tr') ).data()

        // get the url
        let url = $(this).data('url').replace('id', data[0])

        // redirect using href
        window.location.href = url;
    } );

    $('#users-table tbody').on( 'click', '.modal-remove', function () {
        let data = table.row( $(this).parents('tr') ).data()
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
                    'url': '{{ route('csv.lecturers') }}',
                    'method': 'POST',
                    'data': data,
                    'enctype': 'multipart/form-data'
                }).done(function(response) {
                    window.location.reload()
                })
            }
        })
    })

}) ()
</script>
@endsection
