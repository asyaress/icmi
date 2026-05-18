<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Admin ICMI Kaltim</title>
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <style>
        body {
            min-height: 100vh;
            display: grid;
            place-items: center;
            background: linear-gradient(120deg, #111 0%, #222 50%, #333 100%);
        }
        .login-card {
            width: 100%;
            max-width: 420px;
            background: #fff;
            border-radius: 1rem;
            border: 1px solid #e5e7eb;
            padding: 1.5rem;
        }
        .brand-logo {
            width: 84px;
            height: 84px;
            object-fit: contain;
            border-radius: 14px;
            background: #fff;
            border: 1px solid #e5e7eb;
            padding: 6px;
            margin: 0 auto 10px;
            display: block;
        }
        .brand { color: #111; font-weight: 800; text-align: center; }
        .brand span { color: #d6ab00; }
        .btn-primary {
            background: #f4c400;
            border-color: #d6ab00;
            color: #111;
            font-weight: 600;
        }
        .btn-primary:hover { background: #d6ab00; border-color: #b18f00; color: #111; }
    </style>
</head>
<body>
<div class="login-card">
    <img class="brand-logo" src="{{ asset('logo-icmi.png') }}" alt="Logo ICMI Kaltim">
    <h1 class="h4 brand mb-1">Admin <span>ICMI Kaltim</span></h1>
    <p class="text-muted small mb-4 text-center">Masuk untuk mengelola konten website.</p>

    @include('admin.partials.alerts')

    <form method="POST" action="{{ route('admin.login.store') }}">
        @csrf
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required autofocus>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" value="1" id="remember" name="remember">
            <label class="form-check-label" for="remember">Ingat saya</label>
        </div>
        <button type="submit" class="btn btn-primary w-100">Login</button>
    </form>
</div>
</body>
</html>
