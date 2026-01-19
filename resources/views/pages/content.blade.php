<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $content->title }}</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .page-header {
            background: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
            padding: 20px 0;
            margin-bottom: 30px;
        }
        .content-body {
            line-height: 1.6;
            max-width: 800px;
            margin: 0 auto;
        }
        .content-body h1, .content-body h2, .content-body h3 {
            margin-top: 25px;
            margin-bottom: 15px;
            color: #333;
        }
        .content-body h1 { font-size: 2rem; }
        .content-body h2 { font-size: 1.5rem; }
        .content-body h3 { font-size: 1.25rem; }
        .content-body p {
            margin-bottom: 15px;
        }
        .content-body ul, .content-body ol {
            margin-bottom: 15px;
            padding-left: 25px;
        }
        .content-body li {
            margin-bottom: 8px;
        }
        .content-body blockquote {
            border-left: 4px solid #007bff;
            padding-left: 15px;
            margin: 15px 0;
            font-style: italic;
            color: #666;
            background: #f8f9fa;
            padding: 15px;
        }
        .content-body table {
            margin: 15px 0;
        }
        .container {
            padding: 0 20px;
        }
    </style>
</head>
<body>
    <!-- Simple Page Header -->
    <div class="page-header">
        <div class="container">
            <h1 class="h3 mb-0">{{ $content->title }}</h1>
            <small class="text-muted">Last updated: {{ $content->updated_at->format('F d, Y') }}</small>
        </div>
    </div>

    <!-- Content -->
    <div class="container">
        <div class="content-body">
            {!! $content->content !!}
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
