@extends('layouts.admin')

@section('title', 'Categories Management')

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
                        <th>Description</th>
                        <th>Order</th>
                        <th>Status</th>
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
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="icon" class="form-label">Icon (optional)</label>
                        <input type="text" class="form-control" id="icon" name="icon" placeholder="fas fa-folder">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="order" class="form-label">Order</label>
                        <input type="number" class="form-control" id="order" name="order" min="0" value="0">
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
                    <div class="mb-3">
                        <label for="edit_description" class="form-label">Description</label>
                        <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_icon" class="form-label">Icon (optional)</label>
                        <input type="text" class="form-control" id="edit_icon" name="icon" placeholder="fas fa-folder">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_order" class="form-label">Order</label>
                        <input type="number" class="form-control" id="edit_order" name="order" min="0">
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
            {data: 'description', name: 'description'},
            {data: 'order', name: 'order'},
            {
                data: 'is_active', 
                name: 'is_active',
                render: function(data, type, row) {
                    let isChecked = data ? 'checked' : '';
                    return `
                        <div class="form-check form-switch">
                            <input class="form-check-input toggle-category-status" type="checkbox" role="switch" data-id="${row.id}" ${isChecked}>
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
                        <button class="btn btn-action btn-edit edit-category" 
                                data-id="${row.id}" 
                                data-name="${row.name}" 
                                data-description="${row.description || ''}" 
                                data-icon="${row.icon || ''}" 
                                data-order="${row.order || 0}" 
                                data-is_active="${row.is_active ? 'true' : 'false'}" 
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
        let description = $(this).data('description');
        let icon = $(this).data('icon');
        let order = $(this).data('order');
        let isActive = $(this).data('is_active') === 'true';
        
        $('#edit_category_id').val(id);
        $('#edit_name').val(name);
        $('#edit_description').val(description);
        $('#edit_icon').val(icon);
        $('#edit_order').val(order);
        $('#edit_is_active').prop('checked', isActive);
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

    // Toggle Category Status
    $(document).on('click', '.toggle-category', function() {
        let id = $(this).data('id');
        $.ajax({
            url: `/admin/categories/${id}/toggle-status`,
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
    $(document).on('change', '.toggle-category-status', function() {
        let id = $(this).data('id');
        let isChecked = $(this).is(':checked');
        
        $.ajax({
            url: `/admin/categories/${id}/toggle-status`,
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
                if (response.cascaded_quizzes > 0 || response.cascaded_questions > 0) {
                    alert(response.message + `\n\nCascaded to:\n- ${response.cascaded_quizzes} quizzes\n- ${response.cascaded_questions} questions`);
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
