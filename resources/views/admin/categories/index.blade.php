@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h2>Categories Management</h2>
        </div>
        <div class="col text-end">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                Add New Category
            </button>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered" id="categoriesTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<!-- Add Category Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addCategoryForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Category Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Category</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Category Modal -->
<div class="modal fade" id="editCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editCategoryForm">
                @method('PUT')
                <input type="hidden" id="edit_category_id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Category Name</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update Category</button>
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
    var table = $('#categoriesTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "/admin/categories",
        columns: [
            {data: 'id', name: 'id'},
            {data: 'name', name: 'name'},
            {data: 'created_at', name: 'created_at'},
            {
                data: 'actions',
                name: 'actions',
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
                    return `
                        <button class="btn btn-action btn-edit edit-category" 
                                data-id="${row.id}" 
                                data-name="${row.name}" 
                                data-bs-toggle="tooltip" 
                                title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-action btn-delete delete-category" 
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

    // Add Category
    $('#addCategoryForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: "{{ route('categories.store') }}",
            method: 'POST',
            data: $(this).serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('#addCategoryModal').modal('hide');
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

    // Edit Category
    $(document).on('click', '.edit-category', function() {
        let id = $(this).data('id');
        let name = $(this).data('name');
        $('#edit_category_id').val(id);
        $('#edit_name').val(name);
        $('#editCategoryModal').modal('show');
    });

    // Update Category
    $('#editCategoryForm').on('submit', function(e) {
        e.preventDefault();
        let id = $('#edit_category_id').val();
        $.ajax({
            url: `/admin/categories/${id}`,
            method: 'POST',
            data: $(this).serialize() + '&_method=PUT',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('#editCategoryModal').modal('hide');
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

    // Delete Category
    $(document).on('click', '.delete-category', function() {
        if(confirm('Are you sure you want to delete this category?')) {
            let id = $(this).data('id');
            $.ajax({
                url: `/admin/categories/${id}`,
                method: 'POST',
                data: {
                    _method: 'DELETE',
                    _token: $('meta[name="csrf-token"]').attr('content')
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
