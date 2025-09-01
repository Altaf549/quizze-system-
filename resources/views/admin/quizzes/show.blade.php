@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h2>Quiz Details: {{ $quiz->title }}</h2>
        </div>
        <div class="col text-end">
            <a href="{{ route('quizzes.index') }}" class="btn btn-secondary">Back to Quizzes</a>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addQuestionModal">
                Add Question
            </button>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Quiz Information</h5>
                    <p><strong>Category:</strong> {{ $quiz->category->name }}</p>
                    <p><strong>Time Limit:</strong> {{ $quiz->time_limit }} minutes</p>
                    <p><strong>Status:</strong> 
                        @if($quiz->is_active)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-danger">Inactive</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Questions</h5>
            <table class="table" id="questionsTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Question</th>
                        <th>Options</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($quiz->questions as $index => $question)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $question->question_text }}</td>
                        <td>
                            <ol type="A">
                                @foreach(json_decode($question->options) as $option)
                                    <li @if($option === $question->correct_answer) class="text-success fw-bold" @endif>
                                        {{ $option }}
                                    </li>
                                @endforeach
                            </ol>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-primary edit-question" 
                                data-id="{{ $question->id }}"
                                data-question="{{ $question->question_text }}"
                                data-options="{{ json_encode($question->options) }}"
                                data-correct="{{ $question->correct_answer }}">
                                Edit
                            </button>
                            <button class="btn btn-sm btn-danger delete-question" data-id="{{ $question->id }}">
                                Delete
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Question Modal -->
<div class="modal fade" id="addQuestionModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Question</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addQuestionForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="question_text" class="form-label">Question</label>
                        <textarea class="form-control" id="question_text" name="question_text" rows="3" required></textarea>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="option_a" class="form-label">Option A</label>
                            <input type="text" class="form-control" id="option_a" name="options[]" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="option_b" class="form-label">Option B</label>
                            <input type="text" class="form-control" id="option_b" name="options[]" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="option_c" class="form-label">Option C</label>
                            <input type="text" class="form-control" id="option_c" name="options[]" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="option_d" class="form-label">Option D</label>
                            <input type="text" class="form-control" id="option_d" name="options[]" required>
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

@push('scripts')
<script>
$(document).ready(function() {
    $('#questionsTable').DataTable();

    // Add Question
    $('#addQuestionForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: "{{ route('questions.store') }}",
            method: 'POST',
            data: $(this).serialize() + '&quiz_id={{ $quiz->id }}',
            success: function(response) {
                $('#addQuestionModal').modal('hide');
                location.reload();
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

    // Delete Question
    $('.delete-question').on('click', function() {
        if(confirm('Are you sure you want to delete this question?')) {
            let id = $(this).data('id');
            $.ajax({
                url: `/admin/questions/${id}`,
                method: 'DELETE',
                success: function(response) {
                    location.reload();
                }
            });
        }
    });
});
</script>
@endpush
@endsection
