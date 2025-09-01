@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h2>Quiz Results</h2>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered" id="resultsTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Quiz</th>
                        <th>Score</th>
                        <th>Total Questions</th>
                        <th>Percentage</th>
                        <th>Completed At</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $('#resultsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('results.index') }}",
        columns: [
            {data: 'id', name: 'id'},
            {data: 'user_name', name: 'user.name'},
            {data: 'quiz_title', name: 'quiz.title'},
            {data: 'score', name: 'score'},
            {data: 'total_questions', name: 'total_questions'},
            {data: 'percentage', name: 'percentage'},
            {data: 'completed_at', name: 'completed_at'}
        ],
        order: [[6, 'desc']] // Sort by completed_at by default
    });
});
</script>
@endpush
@endsection
