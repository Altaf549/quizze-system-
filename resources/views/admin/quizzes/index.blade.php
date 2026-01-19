@extends('layouts.admin')

@section('title', 'Quiz Management')

@push('styles')
<style>
.form-switch {
    position: relative;
    display: inline-block;
    width: 80px;
    height: 36px;
}

.form-switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.form-switch label {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #e0e0e0;
    transition: .5s;
    border-radius: 36px;
    border: 2px solid #ccc;
}

.form-switch label:before {
    position: absolute;
    content: "";
    height: 28px;
    width: 28px;
    left: 4px;
    bottom: 4px;
    background-color: white;
    transition: .5s;
    border-radius: 50%;
    box-shadow: 0 2px 5px rgba(0,0,0,0.3);
}

.form-switch label:after {
    position: absolute;
    content: "";
    height: 28px;
    width: 28px;
    right: 4px;
    bottom: 4px;
    background-color: white;
    transition: .5s;
    border-radius: 50%;
    box-shadow: 0 2px 5px rgba(0,0,0,0.3);
}

.form-switch input:checked + label {
    background-color: #4CAF50;
    border-color: #4CAF50;
}

.form-switch input:checked + label:before {
    transform: translateX(32px);
}

.form-switch input:checked + label:after {
    background-color: #4CAF50;
}
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h2>Quiz Management</h2>
        </div>
        <div class="col text-end">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addQuizModal">
                Add New Quiz
            </button>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered" id="quizzesTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Time Limit</th>
                        <th>Difficulty</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<!-- Add Quiz Modal -->
<div class="modal fade" id="addQuizModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Quiz</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addQuizForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="title" class="form-label">Quiz Title</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="category_id" class="form-label">Category</label>
                        <select class="form-control" id="category_id" name="category_id" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="time_limit" class="form-label">Time Limit (minutes)</label>
                        <input type="number" class="form-control" id="time_limit" name="time_limit" min="1" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="difficulty" class="form-label">Difficulty</label>
                        <select class="form-control" id="difficulty" name="difficulty">
                            <option value="easy">Easy</option>
                            <option value="medium" selected>Medium</option>
                            <option value="hard">Hard</option>
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="total_points" class="form-label">Total Points</label>
                        <input type="number" class="form-control" id="total_points" name="total_points" min="0" value="0">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="image" class="form-label">Image URL (optional)</label>
                        <input type="text" class="form-control" id="image" name="image" placeholder="https://example.com/image.jpg">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" checked>
                            <label class="form-check-label" for="is_active">Active</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Quiz</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Quiz Modal -->
<div class="modal fade" id="editQuizModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Quiz</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editQuizForm">
                @method('PUT')
                @csrf
                <input type="hidden" id="edit_quiz_id" name="id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_title" class="form-label">Quiz Title</label>
                        <input type="text" class="form-control" id="edit_title" name="title" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_description" class="form-label">Description</label>
                        <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_category_id" class="form-label">Category</label>
                        <select class="form-control" id="edit_category_id" name="category_id" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_time_limit" class="form-label">Time Limit (minutes)</label>
                        <input type="number" class="form-control" id="edit_time_limit" name="time_limit" min="1" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_difficulty" class="form-label">Difficulty</label>
                        <select class="form-control" id="edit_difficulty" name="difficulty">
                            <option value="easy">Easy</option>
                            <option value="medium">Medium</option>
                            <option value="hard">Hard</option>
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_total_points" class="form-label">Total Points</label>
                        <input type="number" class="form-control" id="edit_total_points" name="total_points" min="0">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_image" class="form-label">Image URL (optional)</label>
                        <input type="text" class="form-control" id="edit_image" name="image" placeholder="https://example.com/image.jpg">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="edit_is_active" name="is_active">
                            <label class="form-check-label" for="edit_is_active">Active</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update Quiz</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<meta name="csrf-token" content="{{ csrf_token() }}">
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
$(document).ready(function() {
    var table = $('#quizzesTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('quizzes.index') }}",
        columns: [
            {data: 'id', name: 'id'},
            {data: 'title', name: 'title'},
            {data: 'category.name', name: 'category.name'},
            {data: 'time_limit', name: 'time_limit'},
            {data: 'difficulty', name: 'difficulty'},
            {
                data: 'is_active', 
                name: 'is_active',
                render: function(data, type, row) {
                    let isChecked = data ? 'checked' : '';
                    return `
                        <div class="form-check form-switch">
                            <input class="form-check-input toggle-quiz-status" type="checkbox" role="switch" data-id="${row.id}" ${isChecked}>
                            <label class="form-check-label"></label>
                        </div>
                    `;
                }
            },
            {data: 'created_at', name: 'created_at'},
            {
                data: 'actions',
                name: 'actions',
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
                    return `
                        <button class="btn btn-action btn-edit edit-quiz" 
                                data-id="${row.id}" 
                                data-title="${row.title}"
                                data-category="${row.category_id}"
                                data-time="${row.time_limit}"
                                data-difficulty="${row.difficulty}"
                                data-description="${row.description || ''}"
                                data-total_points="${row.total_points || 0}"
                                data-image="${row.image || ''}"
                                data-is_active="${row.is_active ? 'true' : 'false'}"
                                data-bs-toggle="tooltip"
                                title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-action btn-delete delete-quiz" 
                                data-id="${row.id}" 
                                data-bs-toggle="tooltip"
                                title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    `;
                }
            }
        ]
    });

    // Add Quiz
    $('#addQuizForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: "{{ route('quizzes.store') }}",
            method: 'POST',
            data: $(this).serialize() + '&_token=' + $('meta[name="csrf-token"]').attr('content'),
            success: function(response) {
                $('#addQuizModal').modal('hide');
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

    // Edit Quiz
    $(document).on('click', '.edit-quiz', function() {
        let id = $(this).data('id');
        let title = $(this).data('title');
        let category = $(this).data('category');
        let time = $(this).data('time');
        let difficulty = $(this).data('difficulty');
        let description = $(this).data('description');
        let totalPoints = $(this).data('total_points');
        let image = $(this).data('image');
        let isActive = $(this).data('is_active') === 'true';
        
        $('#edit_quiz_id').val(id);
        $('#edit_title').val(title);
        $('#edit_category_id').val(category);
        $('#edit_time_limit').val(time);
        $('#edit_difficulty').val(difficulty);
        $('#edit_description').val(description);
        $('#edit_total_points').val(totalPoints);
        $('#edit_image').val(image);
        $('#edit_is_active').prop('checked', isActive);
        $('#editQuizModal').modal('show');
    });

    // Update Quiz
    $('#editQuizForm').on('submit', function(e) {
        e.preventDefault();
        let id = $('#edit_quiz_id').val();
        $.ajax({
            url: `/admin/quizzes/${id}`,
            method: 'PUT',
            data: $(this).serialize() + '&_token=' + $('meta[name="csrf-token"]').attr('content'),
            success: function(response) {
                $('#editQuizModal').modal('hide');
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

    // Delete Quiz
    $(document).on('click', '.delete-quiz', function() {
        if(confirm('Are you sure you want to delete this quiz?')) {
            let id = $(this).data('id');
            $.ajax({
                url: `/admin/quizzes/${id}`,
                method: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    _method: 'DELETE'
                },
                success: function(response) {
                    table.ajax.reload();
                    alert(response.message);
                }
            });
        }
    });

    // Toggle Quiz Status
    $(document).on('click', '.toggle-quiz', function() {
        let id = $(this).data('id');
        $.ajax({
            url: `/admin/quizzes/${id}/toggle-status`,
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                table.ajax.reload();
                alert(response.message);
            }
        });
    });

    // Handle Toggle Switch Changes
    $(document).on('change', '.toggle-quiz-status', function() {
        let id = $(this).data('id');
        let isChecked = $(this).is(':checked');
        
        $.ajax({
            url: `/admin/quizzes/${id}/toggle-status`,
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                // Update the switch state based on response
                if (response.is_active !== isChecked) {
                    $(this).prop('checked', response.is_active);
                }
                table.ajax.reload();
                
                // Show cascading information if applicable
                if (response.cascaded_questions > 0) {
                    alert(response.message + `\n\nCascaded to:\n- ${response.cascaded_questions} questions`);
                } else {
                    alert(response.message);
                }
            },
            error: function(xhr) {
                // Handle validation errors
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON;
                    alert(errors.message || 'An error occurred');
                    // Revert switch state
                    $(this).prop('checked', isChecked);
                } else {
                    alert('An error occurred. Please try again.');
                    // Revert switch state
                    $(this).prop('checked', isChecked);
                }
            }
        });
    });
});
</script>
@endpush
@endsection
