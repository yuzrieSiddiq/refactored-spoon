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
                    <table class="table table-striped" id="questions-table">
                        <thead>
                            <tr>
                                <th class="col-md-1 text-center">No</th>
                                <th class="col-md-5">Question</th>
                                <th class="col-md-3">Correct Answer</th>
                                <th class="col-md-2"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (isset($questions))
                                @foreach ($questions as $count => $question)
                                    <tr>
                                        <td class="text-center">{{ $count+1 }}</td>
                                        <td>{{ $question->question }}</td>
                                        <td>{{ $question->correct_answer }}</td>
                                        <td class="text-right">
                                            <a class="btn btn-info" href="{{ route('quizzes.questions.show', ['quiz' => $quiz->id, 'question' => $question->id]) }}">
                                                MORE
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
                    @if ($has_empty_answer_type)
                        <button class="btn btn-success update">UPDATE</button>
                    @else
                        <a class="btn btn-info" href="{{ route('units.show', $quiz->unit_id) }}">BACK TO PREVIOUS PAGE</a>
                    @endif

                    <div class="pull-right">
                        <label class="btn btn-primary btn-file">
                            UPLOAD QUESTIONS (.CSV) <input class="file-upload" type="file" style="display: none;">
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

                $('#loading').modal('toggle')
                $.ajax({
                    'url': '{{ route('csv.questions', $quiz->id) }}',
                    'method': 'POST',
                    'data': data,
                    'enctype': 'multipart/form-data'
                }).done(function(response) {
                    $('#loading').modal('toggle')
                    window.location.reload()
                })
            }
        })
    })

    $('.update').click(function() {
        let answer_types = []

        $('input[name*=answer-type]:checked').each(function() {
            let answer_type = {}
            answer_type['question_id'] = $(this).data('id')
            answer_type['question'] = $(this).data('question')
            answer_type['answer_type'] = $(this).siblings('.radio-value').text()
            answer_types.push(answer_type)
        })

        let data = {
            '_token': getToken(),
            'answer_types': answer_types
        }

        $.ajax({
            'url': '{{ route('quizzes.questions.update.answer_types', $quiz->id) }}',
            'method': 'PUT',
            'data': data
        }).done(function(response) {
            window.location.reload()
        })
    })

}) ()
</script>
@endsection
