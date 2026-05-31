<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Praktikum Hukum Ohm' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { background: #f7f9fc; }
        .sidebar { min-height: 100vh; background: #1f2937; color: white; }
        .sidebar a { color: #e5e7eb; text-decoration: none; display: block; padding: .75rem 1rem; border-radius: .5rem; }
        .sidebar a:hover { background: rgba(255,255,255,.08); }
        .card-soft { border: 0; box-shadow: 0 8px 24px rgba(0,0,0,.06); }
        .table thead th { background: #eef2ff; }
        .small-label { font-size: .85rem; color: #6b7280; }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 p-0 sidebar">
            <div class="p-3 border-bottom border-secondary">
                <div class="fw-bold">Praktikum Ohm</div>
                <div class="small text-secondary">Role: {{ auth()->user()->role }}</div>
            </div>
            <div class="p-3">
                <a href="{{ route('dashboard') }}">Dashboard</a>
                @if(auth()->user()->role === 'siswa')
                    <a href="{{ route('student.material') }}">Materi</a>
                    <a href="{{ route('student.practice') }}">Soal</a>
                @else
                    <a href="{{ route('teacher.practice') }}">Praktikum</a>
                    <a href="{{ route('teacher.users') }}">Manager</a>
                @endif
                <form method="POST" action="{{ route('logout') }}" class="mt-3">
                    @csrf
                    <button class="btn btn-sm btn-outline-light w-100">Logout</button>
                </form>
            </div>
        </div>
        <div class="col-md-10 p-4">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @yield('content')
        </div>
    </div>
</div>
<script>
window.csrfToken = document.querySelector('meta[name="csrf-token"]').content;
</script>
@stack('scripts')
</body>
</html>
