<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Praktikum Hukum Ohm</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            min-height: 100vh;
            margin: 0;
            background:
                linear-gradient(rgba(0, 0, 0, 0.30), rgba(0, 0, 0, 0.50)),
                url("{{ asset('images/login_bg.png') }}");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        .login-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .login-card {
            max-width: 380px;
            width: 100%;
            border: 0;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(10px);
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.35);
        }

        .login-title {
            font-weight: 700;
            color: #111827;
        }

        .login-subtitle {
            color: #6b7280;
        }

        .form-control {
            border-radius: 12px;
            padding: 12px 14px;
        }

        .btn-login {
            border-radius: 12px;
            padding: 12px;
            font-weight: 600;
        }

        .demo-text {
            color: #6b7280;
        }
    </style>
</head>

<body>
    <div class="login-wrapper">
        <div class="card login-card">
            <div class="card-body p-4 p-md-5">
                <h3 class="mb-1 login-title">Login</h3>
                <p class="login-subtitle mb-4">Sistem praktikum digital hukum Ohm</p>

                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('login.perform') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input
                            type="text"
                            name="username"
                            class="form-control"
                            value="{{ old('username') }}"
                            placeholder="Masukkan username"
                            required
                            autofocus
                        >
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input
                            type="password"
                            name="password"
                            class="form-control"
                            placeholder="Masukkan password"
                            required
                        >
                    </div>

                    <button type="submit" class="btn btn-primary btn-login w-100">
                        Masuk
                    </button>
                </form>

                <hr>

                <div class="small demo-text">
                    Demo: guru1/guru1, siswa1/siswa1, siswa2/siswa2
                </div>
            </div>
        </div>
    </div>
</body>
</html>