@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="text-center">QUIZ {{ $quiz->id }}: {{ $quiz->title }}</h4>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="questions-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th class="col-md-4">Question</th>
                                <th class="col-md-2">Answer Type</th>
                                <th class="col-md-3">Correct Answer</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (isset($questions))
                                @foreach ($questions as $question)
                                    <tr>
                                        <td>{{ $question->id }}</td>
                                        <td>{{ $question->question }}</td>
                                        <td class="text-uppercase">{{ $question->answer_type }}</td>
                                        <td>{{ $question->correct_answer }}</td>
                                        <td>
                                            <a class="btn btn-primary" href="{{ route('quizzes.questions.show', ['quiz' => $quiz->id, 'question' => $question->id]) }}">
                                                VIEW QUESTION
                                            </a>
                                            <button class="btn btn-danger modal-remove"><span class="glyphicon glyphicon-remove"></span></button>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div> {{-- end .table-responsive --}}
                <div class="panel-footer">
                    <a class="btn btn-info" href="{{ route('units.quizzes.index', $quiz->unit_id) }}">BACK TO PREVIOUS PAGE</a>
                    <div class="pull-right">
                        <label class="btn btn-primary btn-file">
                            UPLOAD STUDENT LIST (.CSV) <input class="file-upload" type="file" style="display: none;">
                        </label>
                        <a class="btn btn-success" href="{{ route('quizzes.questions.create', $quiz->id) }}">
                            CREATE NEW QUESTION
                        </a>
                    </div>
                </div> {{-- end .panel-footer --}}
            </div>
        </div>
    </div>

    <div class="modal fade modal-template" id="delete-modal-id" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            @if (isset($question))
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Remove question: {{ $question->id }}</h4>
                </div>
                <div class="modal-body">
                        <button type="button" class="btn btn-default cancel-modal" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-danger delete" data-url="{{ route('quizzes.questions.destroy', ['quiz' => $quiz->id, 'question' => $question->id]) }}">
                            DELETE
                        </button>
                </div>
            </div><!-- /.modal-content -->
            @endif
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>
@endsection

@section('extra_js')
<script src="{{ asset('js/papaparse.min.js') }}"></script>
<script type="text/javascript">
(function() {
    // Get CSRF token
    let getToken = function() {
        return $('meta[name=csrf-token]').attr('content')
    }

    $('.modal-remove').click(function() {
        // clone the template to make new modal
        let modal = $('.modal-template').clone().removeClass('modal-template');

        // register the modal to the body and toggle the modal
        $('.container').append(modal)
        modal.modal('toggle')

        // on click delete
        modal.find('.delete').click(function() {
            let url = $(this).data('url')

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
                    'url': '{{ route('csv.questions', $quiz->id) }}',
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
