@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h2>Quiz Details: {{ $quiz->title }}</h2>
        </div>
        <div class="col text-end">
            <a href="{{ route('quizzes.index') }}" class="btn btn-secondary">Back to Quizzes</a>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Quiz Information</h5>
                    <p><strong>Category:</strong> {{ $quiz->category->name }}</p>
                    <p><strong>Time Limit:</strong> {{ $quiz->time_limit }} minutes</p>
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

@push('scripts')
<script>
$(document).ready(function() {
    $('#questionsTable').DataTable();

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
