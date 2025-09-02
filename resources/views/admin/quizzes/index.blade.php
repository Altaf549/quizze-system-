@extends('layouts.admin')

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
                        <th>Time Limit (minutes)</th>
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
            {
                data: 'actions',
                name: 'actions',
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
                    return `
                        <a href="/admin/quizzes/${row.id}" 
                           class="btn btn-action btn-view" 
                           data-bs-toggle="tooltip" 
                           title="View">
                            <i class="fas fa-eye"></i>
                        </a>
                        <button class="btn btn-action btn-edit edit-quiz" 
                                data-id="${row.id}" 
                                data-title="${row.title}"
                                data-category="${row.category_id}"
                                data-time="${row.time_limit}"
                                data-active="${row.is_active}"
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
        $('#edit_quiz_id').val(id);
        $('#edit_title').val(title);
        $('#edit_category_id').val(category);
        $('#edit_time_limit').val(time);
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
});
</script>
@endpush
@endsection
