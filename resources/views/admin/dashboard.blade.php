@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <h4 class="mb-0">@yield('title', 'Dashboard')</h4>
            <div class="user-info">
                <i class="fas fa-user-circle me-2"></i>
                {{ auth()->user()->name ?? 'Guest' }}
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-3">
        <div class="card">
            <div class="card-body text-center">
                <i class="fas fa-list fa-3x mb-3 text-primary"></i>
                <h5 class="card-title">Categories</h5>
                <p class="card-text h3">{{ \App\Models\Category::count() }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body text-center">
                <i class="fas fa-question-circle fa-3x mb-3 text-success"></i>
                <h5 class="card-title">Quizzes</h5>
                <p class="card-text h3">{{ \App\Models\Quiz::count() }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body text-center">
                <i class="fas fa-clipboard-question fa-3x mb-3 text-warning"></i>
                <h5 class="card-title">Questions</h5>
                <p class="card-text h3">{{ \App\Models\Question::count() }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body text-center">
                <i class="fas fa-users fa-3x mb-3 text-info"></i>
                <h5 class="card-title">Users</h5>
                <p class="card-text h3">{{ \App\Models\User::count() }}</p>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Recent Quizzes</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Questions</th>
                                <th>Created</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(\App\Models\Quiz::with('category')->latest()->take(5)->get() as $quiz)
                            <tr>
                                <td>{{ $quiz->title }}</td>
                                <td>{{ $quiz->category->name }}</td>
                                <td>{{ $quiz->questions_count }}</td>
                                <td>{{ $quiz->created_at->diffForHumans() }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Recent Categories</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Quizzes</th>
                                <th>Created</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(\App\Models\Category::withCount('quizzes')->latest()->take(5)->get() as $category)
                            <tr>
                                <td>{{ $category->name }}</td>
                                <td>{{ $category->quizzes_count }}</td>
                                <td>{{ $category->created_at->diffForHumans() }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
