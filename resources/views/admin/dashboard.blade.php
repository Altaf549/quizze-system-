@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    <h2>Welcome to Quiz System Administration</h2>
                    <div class="list-group mt-4">
                        <a href="{{ route('categories.index') }}" class="list-group-item list-group-item-action">
                            Manage Categories
                        </a>
                        <a href="{{ route('quizzes.index') }}" class="list-group-item list-group-item-action">
                            Manage Quizzes
                        </a>
                        <a href="{{ route('questions.index') }}" class="list-group-item list-group-item-action">
                            Manage Questions
                        </a>
                        <a href="{{ route('results.index') }}" class="list-group-item list-group-item-action">
                            View Results
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
