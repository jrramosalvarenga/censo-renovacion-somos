<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión — Renovación Somos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body {
            background: #f9f5f5;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            width: 100%;
            max-width: 420px;
            border: none;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(183,28,28,0.12);
        }
        .login-header {
            background: #b71c1c;
            border-radius: 16px 16px 0 0;
            padding: 2rem;
            text-align: center;
            color: #fff;
        }
        .login-header h1 {
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0.5rem 0 0.2rem;
        }
        .login-header p {
            font-size: 0.85rem;
            opacity: 0.85;
            margin: 0;
        }
        .login-body { padding: 2rem; }
        .btn-login {
            background: #b71c1c;
            border: none;
            color: #fff;
            width: 100%;
            padding: 0.65rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1rem;
            transition: background 0.2s;
        }
        .btn-login:hover { background: #7f0000; color: #fff; }
        .form-control:focus {
            border-color: #b71c1c;
            box-shadow: 0 0 0 0.2rem rgba(183,28,28,0.15);
        }
        .escudo {
            width: 64px;
            height: 64px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 0.75rem;
            font-size: 2rem;
        }
    </style>
</head>
<body>
    <div class="login-card card">
        <div class="login-header">
            <div class="escudo"><i class="bi bi-people-fill"></i></div>
            <h1>Renovación Somos</h1>
            <p>Sistema de Censo de Militancia — Honduras</p>
        </div>
        <div class="login-body">
            @if($errors->any())
                <div class="alert alert-danger alert-sm py-2 mb-3" role="alert">
                    <i class="bi bi-exclamation-circle me-1"></i>
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold">Correo electrónico</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-envelope text-muted"></i></span>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email') }}" placeholder="correo@ejemplo.com"
                               autofocus autocomplete="email" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Contraseña</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-lock text-muted"></i></span>
                        <input type="password" name="password" id="pwd" class="form-control"
                               placeholder="••••••••" autocomplete="current-password" required>
                        <button type="button" class="input-group-text bg-white border-start-0"
                                onclick="togglePwd()" tabindex="-1">
                            <i class="bi bi-eye" id="eye-icon" style="cursor:pointer"></i>
                        </button>
                    </div>
                </div>
                <div class="mb-4 d-flex align-items-center justify-content-between">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                        <label class="form-check-label text-muted small" for="remember">Recordarme</label>
                    </div>
                </div>
                <button type="submit" class="btn btn-login">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Iniciar sesión
                </button>
            </form>
        </div>
        <div class="text-center pb-3">
            <small class="text-muted">Movimiento Renovación Somos &copy; {{ date('Y') }}</small>
        </div>
    </div>

    <script>
        function togglePwd() {
            const pwd = document.getElementById('pwd');
            const icon = document.getElementById('eye-icon');
            if (pwd.type === 'password') {
                pwd.type = 'text';
                icon.className = 'bi bi-eye-slash';
            } else {
                pwd.type = 'password';
                icon.className = 'bi bi-eye';
            }
        }
    </script>
</body>
</html>
