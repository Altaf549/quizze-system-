<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz System - Admin Panel</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <!-- Custom CSS -->
    <style>
        .sidebar { min-height: 100vh; background: #343a40; }
        .sidebar a { color: white; }
        .sidebar a:hover { background: #454d55; }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 sidebar p-0">
                <div class="d-flex flex-column flex-shrink-0 p-3 text-white">
                    <a href="/admin/dashboard" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
                        <span class="fs-4">Quiz Admin</span>
                    </a>
                    <hr>
                    <ul class="nav nav-pills flex-column mb-auto">
                        <li class="nav-item">
                            <a href="{{ route('admin.dashboard') }}" class="nav-link text-white">Dashboard</a>
                        </li>
                        <li>
                            <a href="{{ route('categories.index') }}" class="nav-link text-white">Categories</a>
                        </li>
                        <li>
                            <a href="{{ route('quizzes.index') }}" class="nav-link text-white">Quizzes</a>
                        </li>
                        <li>
                            <a href="{{ route('questions.index') }}" class="nav-link text-white">Questions</a>
                        </li>
                        <li>
                            <a href="{{ route('results.index') }}" class="nav-link text-white">Results</a>
                        </li>
                    </ul>
                    <hr>
                    <div class="dropdown">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-danger w-100">Logout</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Main content -->
            <div class="col-md-10 p-4">
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    @stack('scripts')
</body>
</html>
