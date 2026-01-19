@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Content Management</h1>
    <a href="{{ route('contents.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Add New Content
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover" id="contentsTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Page Key</th>
                        <th>Title</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($contents as $index => $content)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <span class="badge bg-secondary">{{ $content->page_key }}</span>
                        </td>
                        <td>{{ $content->title }}</td>
                        <td>{{ $content->created_at->format('M d, Y') }}</td>
                        <td>{{ $content->updated_at->format('M d, Y') }}</td>
                        <td>
                            <a href="{{ route('contents.edit', $content->id) }}" 
                               class="btn btn-action btn-edit" 
                               data-bs-toggle="tooltip" 
                               data-bs-placement="top" 
                               title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('contents.destroy', $content->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="btn btn-action btn-delete" 
                                        data-bs-toggle="tooltip" 
                                        data-bs-placement="top" 
                                        title="Delete"
                                        onclick="return confirm('Are you sure you want to delete this content?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#contentsTable').DataTable({
            responsive: true,
            order: [[0, 'asc']]
        });
    });
</script>
@endpush
