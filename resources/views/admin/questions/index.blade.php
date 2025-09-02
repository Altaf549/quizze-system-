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
        ajax: {
            url: "{{ route('questions.index') }}",
            type: 'GET',
            data: function (d) {
                // Add any additional parameters you need to send with the request
            },
            error: function(xhr, error, thrown) {
                console.error('DataTables error:', error, thrown);
            }
        },
        columns: [
            {data: 'id', name: 'id'},
            {data: 'quiz.title', name: 'quiz.title'},
            {data: 'question_text', name: 'question_text'},
            {
                data: 'options_display', 
                name: 'options_display', 
                orderable: false, 
                searchable: false
            },
            {
                data: 'actions',
                name: 'actions',
                orderable: false,
                searchable: false
            }
        ],
        order: [[0, 'desc']],
        responsive: true,
        language: {
            processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '
        },
        drawCallback: function(settings) {
            // Re-initialize tooltips after table draw
            $('[data-bs-toggle="tooltip"]').tooltip();
        }
    });

    // Add Question
    $('#addQuestionForm').on('submit', function(e) {
        e.preventDefault();
        
        // Clear previous error states
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').text('');
        
        // Get form data
        let formData = {
            _token: '{{ csrf_token() }}',
            quiz_id: $('#quiz_id').val(),
            question_text: $('#question_text').val(),
            options: [
                $('input[name="options[]"]').eq(0).val(),
                $('input[name="options[]"]').eq(1).val(),
                $('input[name="options[]"]').eq(2).val(),
                $('input[name="options[]"]').eq(3).val()
            ],
            correct_answer: $('#correct_answer').val()
        };
        
        $.ajax({
            url: "{{ route('questions.store') }}",
            method: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                $('#addQuestionModal').modal('hide');
                $('#addQuestionForm')[0].reset();
                table.ajax.reload();
                alert('Question added successfully!');
            },
            error: function(xhr) {
                if(xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    Object.keys(errors).forEach(function(key) {
                        if (key.includes('options.')) {
                            let index = key.split('.')[1];
                            $(`input[name="options[]"]`).eq(index).addClass('is-invalid')
                                .siblings('.invalid-feedback').text(errors[key][0]);
                        } else {
                            $(`#${key}`).addClass('is-invalid')
                                .siblings('.invalid-feedback').text(errors[key][0]);
                        }
                    });
                } else {
                    alert('An error occurred. Please try again.');
                }
            }
        });
    });

    // Edit Question
    $(document).on('click', '.edit-question', function() {
        const $button = $(this);
        const id = $button.data('id');
        const questionText = $button.data('question-text');
        let options = $button.data('options');
        const correctAnswer = $button.data('correct-answer');
        
        // Ensure options is an object
        if (typeof options === 'string') {
            try {
                options = JSON.parse(options);
            } catch (e) {
                console.error('Error parsing options:', e, 'Raw options:', options);
                options = { A: '', B: '', C: '', D: '' };
            }
        }
        
        // Populate the edit form
        $('#edit_question_id').val(id);
        $('#edit_question_text').val(questionText);
        
        // Set option values, defaulting to empty string if not found
        // Make sure to handle both string keys (from JSON) and numeric indices
        const optionValues = {
            A: options.A || options[0] || '',
            B: options.B || options[1] || '',
            C: options.C || options[2] || '',
            D: options.D || options[3] || ''
        };
        
        $('#edit_option_a').val(optionValues.A);
        $('#edit_option_b').val(optionValues.B);
        $('#edit_option_c').val(optionValues.C);
        $('#edit_option_d').val(optionValues.D);
        
        // Set correct answer if it exists in options
        if (correctAnswer && (optionValues[correctAnswer] !== undefined)) {
            $('#edit_correct_answer').val(correctAnswer);
        } else {
            $('#edit_correct_answer').val('');
        }
        
        // Clear any previous validation errors
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').text('');
        
        // Show the modal
        $('#editQuestionModal').modal('show');
    });

    // Update Question
    $('#editQuestionForm').on('submit', function(e) {
        e.preventDefault();
        
        let formData = {
            _token: $('meta[name="csrf-token"]').attr('content'),
            _method: 'PUT',
            question_text: $('#edit_question_text').val(),
            options: [
                $('#edit_option_a').val(),
                $('#edit_option_b').val(),
                $('#edit_option_c').val(),
                $('#edit_option_d').val()
            ],
            correct_answer: $('#edit_correct_answer').val()
        };
        
        let id = $('#edit_question_id').val();
        
        $.ajax({
            url: `/admin/questions/${id}`,
            method: 'POST', // Laravel's way of handling PUT requests with _method
            data: formData,
            success: function(response) {
                $('#editQuestionModal').modal('hide');
                table.ajax.reload();
                showToast('success', response.message);
            },
            error: function(xhr) {
                if(xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    // Clear previous errors
                    $('.is-invalid').removeClass('is-invalid');
                    $('.invalid-feedback').text('');
                    
                    // Show new errors
                    Object.keys(errors).forEach(function(key) {
                        let field = key.replace('options.', 'option_').toLowerCase();
                        $(`#edit_${field}`).addClass('is-invalid');
                        $(`#edit_${field}`).siblings('.invalid-feedback').text(errors[key][0]);
                    });
                } else {
                    showToast('error', 'An error occurred. Please try again.');
                }
            }
        });
    });

    // Delete Question
    $(document).on('click', '.delete-question', function(e) {
        e.preventDefault();
        
        if(confirm('Are you sure you want to delete this question?')) {
            let id = $(this).data('id');
            let button = $(this);
            
            $.ajax({
                url: `/admin/questions/${id}`,
                method: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    _method: 'DELETE'
                },
                success: function(response) {
                    table.ajax.reload();
                    showToast('success', response.message);
                },
                error: function(xhr) {
                    showToast('error', 'An error occurred while deleting the question.');
                }
            });
        }
    });
    });
    
    // Helper function to show toast notifications
    function showToast(type, message) {
        const toast = `
            <div class="toast align-items-center text-white bg-${type} border-0 position-fixed bottom-0 end-0 m-3" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        `;
        
        // Add toast to body and show it
        $(toast).appendTo('body').toast({ autohide: true, delay: 3000 }).toast('show');
        
        // Remove toast after it's hidden
        setTimeout(() => {
            $('.toast').toast('dispose').remove();
        }, 3500);
    }
</script>
@endpush
@endsection
