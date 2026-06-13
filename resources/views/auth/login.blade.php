<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; height: 100vh; display: flex; align-items: center; }
        .auth-card { width: 100%; max-width: 400px; padding: 15px; margin: auto; }
        .card { border: none; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); }
    </style>
</head>
<body>
    <div class="auth-card">
        <div class="text-center mb-4">
            <h1 class="fw-bold text-primary"><i class="fas fa-wallet me-2"></i>DompetKu</h1>
            <p class="text-muted">Manajemen Keuangan Pribadi</p>
        </div>
        <div class="card">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-4">Login</h5>
                
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0 small">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Username</label>
                        <input type="text" name="username" class="form-control" value="{{ old('username') }}" required autofocus>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Password</label>
                        <input type="password" name="password" class="form-control" required autocomplete="current-password">
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" name="remember" class="form-check-input" id="remember_me">
                        <label class="form-check-label small" for="remember_me">Ingat saya</label>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">Masuk</button>
                </form>
            </div>
        </div>
        <div class="text-center mt-4 small text-muted">
            Belum punya akun? <a href="{{ route('register') }}" class="text-decoration-none">Daftar sekarang</a>
        </div>
    </div>
</body>
</html>
