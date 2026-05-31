<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Praktikum Hukum Ohm</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container d-flex align-items-center justify-content-center min-vh-100">
    <div class="card shadow-sm border-0" style="max-width: 480px; width:100%;">
        <div class="card-body p-4">
            <h3 class="mb-1">Login</h3>
            <p class="text-muted mb-4">Sistem praktikum digital hukum Ohm</p>
            <form method="POST" action="{{ route('login.perform') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" value="{{ old('username') }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button class="btn btn-primary w-100">Masuk</button>
            </form>
            <hr>
            <div class="small text-muted">
                Demo: guru1/guru1, siswa1/siswa1, siswa2/siswa2
            </div>
        </div>
    </div>
</div>
</body>
</html>
