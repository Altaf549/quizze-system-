@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h2>Questions Management</h2>
        </div>
        <div class="col text-end">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addQuestionModal">
                Add New Question
            </button>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered" id="questionsTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Quiz</th>
                        <th>Question</th>
                        <th>Options</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<!-- Add Question Modal -->
<div class="modal fade" id="addQuestionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Question</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addQuestionForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="quiz_id" class="form-label">Quiz</label>
                        <select class="form-control" id="quiz_id" name="quiz_id" required>
                            <option value="">Select Quiz</option>
                            @foreach($quizzes as $quiz)
                                <option value="{{ $quiz->id }}">{{ $quiz->title }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="question_text" class="form-label">Question Text</label>
                        <textarea class="form-control" id="question_text" name="question_text" rows="3" required></textarea>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Options</label>
                        <div class="option-group">
                            <div class="input-group mb-2">
                                <span class="input-group-text">A</span>
                                <input type="text" class="form-control" name="options[]" required>
                            </div>
                            <div class="input-group mb-2">
                                <span class="input-group-text">B</span>
                                <input type="text" class="form-control" name="options[]" required>
                            </div>
                            <div class="input-group mb-2">
                                <span class="input-group-text">C</span>
                                <input type="text" class="form-control" name="options[]" required>
                            </div>
                            <div class="input-group mb-2">
                                <span class="input-group-text">D</span>
                                <input type="text" class="form-control" name="options[]" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="correct_answer" class="form-label">Correct Answer</label>
                        <select class="form-control" id="correct_answer" name="correct_answer" required>
                            <option value="">Select Correct Answer</option>
                            <option value="A">Option A</option>
                            <option value="B">Option B</option>
                            <option value="C">Option C</option>
                            <option value="D">Option D</option>
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Question</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Question Modal -->
<div class="modal fade" id="editQuestionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Question</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editQuestionForm">
                @method('PUT')
                <input type="hidden" id="edit_question_id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_question_text" class="form-label">Question Text</label>
                        <textarea class="form-control" id="edit_question_text" name="question_text" rows="3" required></textarea>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Options</label>
                        <div class="option-group">
                            <div class="input-group mb-2">
                                <span class="input-group-text">A</span>
                                <input type="text" class="form-control" name="options[]" id="edit_option_a" required>
                            </div>
                            <div class="input-group mb-2">
                                <span class="input-group-text">B</span>
                                <input type="text" class="form-control" name="options[]" id="edit_option_b" required>
                            </div>
                            <div class="input-group mb-2">
                                <span class="input-group-text">C</span>
                                <input type="text" class="form-control" name="options[]" id="edit_option_c" required>
                            </div>
                            <div class="input-group mb-2">
                                <span class="input-group-text">D</span>
                                <input type="text" class="form-control" name="options[]" id="edit_option_d" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_correct_answer" class="form-label">Correct Answer</label>
                        <select class="form-control" id="edit_correct_answer" name="correct_answer" required>
                            <option value="">Select Correct Answer</option>
                            <option value="A">Option A</option>
                            <option value="B">Option B</option>
                            <option value="C">Option C</option>
                            <option value="D">Option D</option>
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update Question</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    var table = $('#questionsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('questions.index') }}",
        columns: [
            {data: 'id', name: 'id'},
            {data: 'quiz.title', name: 'quiz.title'},
            {data: 'question_text', name: 'question_text'},
            {data: 'options', name: 'options'},
            {data: 'actions', name: 'actions', orderable: false, searchable: false}
        ]
    });

    // Add Question
    $('#addQuestionForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: "{{ route('questions.store') }}",
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                $('#addQuestionModal').modal('hide');
                table.ajax.reload();
                alert(response.message);
            },
            error: function(xhr) {
                if(xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    Object.keys(errors).forEach(function(key) {
                        $(`#${key}`).addClass('is-invalid');
                        $(`#${key}`).siblings('.invalid-feedback').text(errors[key][0]);
                    });
                }
            }
        });
    });

    // Edit Question
    $(document).on('click', '.edit-question', function() {
        let id = $(this).data('id');
        // Fetch question data
        $.get(`/admin/questions/${id}`, function(question) {
            $('#edit_question_id').val(id);
            $('#edit_question_text').val(question.question_text);
            $('#edit_option_a').val(question.options.A);
            $('#edit_option_b').val(question.options.B);
            $('#edit_option_c').val(question.options.C);
            $('#edit_option_d').val(question.options.D);
            $('#edit_correct_answer').val(question.correct_answer);
            $('#editQuestionModal').modal('show');
        });
    });

    // Update Question
    $('#editQuestionForm').on('submit', function(e) {
        e.preventDefault();
        let id = $('#edit_question_id').val();
        $.ajax({
            url: `/admin/questions/${id}`,
            method: 'PUT',
            data: $(this).serialize(),
            success: function(response) {
                $('#editQuestionModal').modal('hide');
                table.ajax.reload();
                alert(response.message);
            },
            error: function(xhr) {
                if(xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    Object.keys(errors).forEach(function(key) {
                        $(`#edit_${key}`).addClass('is-invalid');
                        $(`#edit_${key}`).siblings('.invalid-feedback').text(errors[key][0]);
                    });
                }
            }
        });
    });

    // Delete Question
    $(document).on('click', '.delete-question', function() {
        if(confirm('Are you sure you want to delete this question?')) {
            let id = $(this).data('id');
            $.ajax({
                url: `/admin/questions/${id}`,
                method: 'DELETE',
                success: function(response) {
                    table.ajax.reload();
                    alert(response.message);
                }
            });
        }
    });
});
</script>
@endpush
@endsection
