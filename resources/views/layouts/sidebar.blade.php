@extends('layouts.app')

@section('sidebar')
<div class="sidebar">
    <h4 class="mb-4 text-center">Quiz System</h4>
    <nav class="nav flex-column">
        <a class="nav-link {{ request()->is('admin/dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </a>
        <a class="nav-link {{ request()->is('admin/categories*') ? 'active' : '' }}" href="{{ route('categories.index') }}">
            <i class="fas fa-list"></i> Categories
        </a>
        <a class="nav-link {{ request()->is('admin/quizzes*') ? 'active' : '' }}" href="{{ route('quizzes.index') }}">
            <i class="fas fa-question-circle"></i> Quizzes
        </a>
        <a class="nav-link {{ request()->is('admin/questions*') ? 'active' : '' }}" href="{{ route('questions.index') }}">
            <i class="fas fa-clipboard-question"></i> Questions
        </a>
        <a class="nav-link {{ request()->is('admin/results*') ? 'active' : '' }}" href="{{ route('results.index') }}">
            <i class="fas fa-chart-bar"></i> Results
        </a>
    </nav>
    <div class="mt-4">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-outline-light w-100">
                <i class="fas fa-sign-out-alt"></i> Logout
            </button>
        </form>
    </div>
</div>
@endsection
