@extends('layouts.app')

@section('extra_head')
    <link rel="stylesheet" href="{{ asset('css/datatables.bootstrap.css') }}">
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    UNITS INDEX
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered" id="units-table">
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
                </div> {{-- end .panel-body --}}
                <div class="panel-footer">
                    <a class="btn btn-success" href="{{ route('units.create') }}">
                        CREATE NEW UNIT
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
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/datatables.net.js') }}"></script>
<script src="{{ asset('js/datatables.bootstrap.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script>
(function() {
    // Get CSRF token
    let getToken = function() {
        return $('meta[name=csrf-token]').attr('content')
    }

    let table_operations = '\
        <button class="btn btn-info studentlist" data-url="{{ route('units.students.index', "id")}}">Student List</button>\
        <button class="btn btn-success edit" data-url="{{ route('units.edit', "id")}}">Edit</button>\
        <button class="btn btn-danger modal-remove" data-toggle="modal">Remove</button>';

    let table = $('#units-table').DataTable( {
        "ajax": "{{ route('get.units.datatable') }}",
        "columnDefs": [ {
            "targets": -1,
            "data": null,
            "defaultContent": table_operations
        } ]
    } );

    $('#units-table tbody').on( 'click', '.studentlist', function () {
        // get the row data
        let data = table.row( $(this).parents('tr') ).data()

        // get the url
        let url = $(this).data('url').replace('id', data[0])

        // redirect using href
        window.location.href = url;
    } );

    $('#units-table tbody').on( 'click', '.edit', function () {
        // get the row data
        let data = table.row( $(this).parents('tr') ).data()

        // get the url
        let url = $(this).data('url').replace('id', data[0])

        // redirect using href
        window.location.href = url;
    } );

    $('#units-table tbody').on( 'click', '.modal-remove', function () {
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
    } );

}) ()
</script>
@endsection
