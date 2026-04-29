<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Censo') — Renovación Somos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root {
            --rs-primary: #b71c1c;
            --rs-primary-dark: #7f0000;
            --rs-primary-light: #fdecea;
            --rs-accent: #ff6f00;
        }
        body { background: #f9f5f5; font-size: 0.92rem; }
        .navbar { background: var(--rs-primary) !important; }
        .navbar-brand { font-weight: 700; letter-spacing: 0.5px; color: #fff !important; }
        .navbar-brand span { color: #ffcc80; }
        .nav-link { color: rgba(255,255,255,0.85) !important; }
        .nav-link:hover, .nav-link.active { color: #ffcc80 !important; }
        .sidebar { background: #fff; min-height: calc(100vh - 56px); border-right: 1px solid #f5c6c6; padding: 1.5rem 0; }
        .sidebar .nav-link { color: #444; padding: 0.5rem 1.5rem; border-radius: 0; }
        .sidebar .nav-link:hover { background: #fff5f5; color: var(--rs-primary); }
        .sidebar .nav-link.active { background: var(--rs-primary-light); color: var(--rs-primary); font-weight: 600; border-left: 3px solid var(--rs-primary); }
        .sidebar .nav-link i { width: 20px; }
        .stat-card { border: none; border-radius: 12px; transition: transform 0.15s; }
        .stat-card:hover { transform: translateY(-2px); }
        .badge-militante { background: var(--rs-primary); }
        .badge-simpatizante { background: #6c757d; }
        .btn-primary { background-color: var(--rs-primary) !important; border-color: var(--rs-primary-dark) !important; }
        .btn-primary:hover { background-color: var(--rs-primary-dark) !important; border-color: var(--rs-primary-dark) !important; }
        .btn-outline-primary { color: var(--rs-primary) !important; border-color: var(--rs-primary) !important; }
        .btn-outline-primary:hover { background-color: var(--rs-primary) !important; color: #fff !important; }
        .text-primary { color: var(--rs-primary) !important; }
        .bg-primary { background-color: var(--rs-primary) !important; }
        .border-primary { border-color: var(--rs-primary) !important; }
        .badge.bg-primary { background-color: var(--rs-primary) !important; }
        .progress-bar { background-color: var(--rs-primary) !important; }
        a { color: var(--rs-primary); }
        a:hover { color: var(--rs-primary-dark); }
        @media (max-width: 767px) {
            .sidebar { min-height: auto; border-right: none; border-bottom: 1px solid #e3e6ea; padding: 0.5rem 0; }
        }
    </style>
    @yield('styles')
</head>
<body>
<nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('dashboard') }}">
            <i class="bi bi-people-fill me-2"></i>Renovación <span>Somos</span>
        </a>
        <button class="navbar-toggler border-light" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        <i class="bi bi-house me-1"></i>Inicio
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('miembros.*') ? 'active' : '' }}" href="{{ route('miembros.index') }}">
                        <i class="bi bi-people me-1"></i>Militancia
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('localidades.*') ? 'active' : '' }}" href="{{ route('localidades.index') }}">
                        <i class="bi bi-geo-alt me-1"></i>Localidades
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('reportes.*') ? 'active' : '' }}" href="{{ route('reportes.index') }}">
                        <i class="bi bi-bar-chart me-1"></i>Reportes
                    </a>
                </li>
                <li class="nav-item dropdown ms-2">
                    <a class="nav-link dropdown-toggle d-flex align-items-center gap-1" href="#" data-bs-toggle="dropdown">
                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold"
                             style="width:30px;height:30px;background:rgba(255,255,255,0.25);font-size:0.75rem">
                            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                        </div>
                        <span class="d-none d-lg-inline">{{ Auth::user()->name }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><h6 class="dropdown-header">{{ Auth::user()->email }}</h6></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="bi bi-box-arrow-right me-2"></i>Cerrar sesión
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container-fluid">
    <div class="row">
        <nav class="col-md-2 d-none d-md-block sidebar">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item mt-2">
                    <small class="px-3 text-muted text-uppercase fw-bold" style="font-size:0.7rem">Militancia</small>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('miembros.index') ? 'active' : '' }}" href="{{ route('miembros.index') }}">
                        <i class="bi bi-people"></i> Todos los miembros
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('miembros.create') ? 'active' : '' }}" href="{{ route('miembros.create') }}">
                        <i class="bi bi-person-plus"></i> Nuevo miembro
                    </a>
                </li>
                <li class="nav-item mt-2">
                    <small class="px-3 text-muted text-uppercase fw-bold" style="font-size:0.7rem">Territorio</small>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('localidades.*') ? 'active' : '' }}" href="{{ route('localidades.index') }}">
                        <i class="bi bi-geo-alt"></i> Localidades
                    </a>
                </li>
                <li class="nav-item mt-2">
                    <small class="px-3 text-muted text-uppercase fw-bold" style="font-size:0.7rem">Reportes</small>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('reportes.general') ? 'active' : '' }}" href="{{ route('reportes.general') }}">
                        <i class="bi bi-globe-americas"></i> General
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('reportes.departamento') ? 'active' : '' }}" href="{{ route('reportes.departamento') }}">
                        <i class="bi bi-map"></i> Por departamento
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('reportes.municipio') ? 'active' : '' }}" href="{{ route('reportes.municipio') }}">
                        <i class="bi bi-building"></i> Por municipio
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('reportes.localidad') ? 'active' : '' }}" href="{{ route('reportes.localidad') }}">
                        <i class="bi bi-pin-map"></i> Por localidad
                    </a>
                </li>
            </ul>
        </nav>

        <main class="col-md-10 ms-sm-auto px-4 py-4">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @yield('content')
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@yield('scripts')
</body>
</html>
