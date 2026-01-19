@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Create Content</h1>
    <a href="{{ route('contents.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back to Contents
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('contents.store') }}" method="POST">
            @csrf
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="page_key" class="form-label">Page Key</label>
                    <select class="form-select @error('page_key') is-invalid @enderror" 
                            name="page_key" 
                            id="page_key" 
                            required>
                        <option value="">Select a page type</option>
                        <option value="about-us">About Us</option>
                        <option value="privacy-policy">Privacy Policy</option>
                        <option value="terms-conditions">Terms & Conditions</option>
                    </select>
                    @error('page_key')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">Choose the type of content you're creating</small>
                </div>
                <div class="col-md-6">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" 
                           class="form-control @error('title') is-invalid @enderror" 
                           name="title" 
                           id="title" 
                           value="{{ old('title') }}" 
                           required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="content" class="form-label">Content</label>
                <textarea class="form-control @error('content') is-invalid @enderror" 
                          name="content" 
                          id="content" 
                          rows="10" 
                          required>{{ old('content') }}</textarea>
                @error('content')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-end">
                <a href="{{ route('contents.index') }}" class="btn btn-secondary me-2">Cancel</a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Create Content
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<!-- CKEditor CDN -->
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        ClassicEditor
            .create(document.querySelector('#content'), {
                toolbar: [
                    'heading', '|',
                    'bold', 'italic', 'underline', 'strikethrough', '|',
                    'bulletedList', 'numberedList', '|',
                    'outdent', 'indent', '|',
                    'link', 'blockQuote', '|',
                    'insertTable', '|',
                    'undo', 'redo'
                ],
                heading: {
                    options: [
                        { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                        { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                        { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                        { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' }
                    ]
                }
            })
            .catch(error => {
                console.error(error);
            });
    });
</script>
@endpush
