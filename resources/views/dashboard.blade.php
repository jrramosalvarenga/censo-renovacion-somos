@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0 fw-bold text-dark">Dashboard</h4>
        <small class="text-muted">Movimiento Renovación Somos — Honduras</small>
    </div>
    <a href="{{ route('miembros.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-person-plus me-1"></i>Nuevo miembro
    </a>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card stat-card shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle p-3 me-3" style="background:#e8f0fe">
                        <i class="bi bi-people-fill fs-4 text-primary"></i>
                    </div>
                    <div>
                        <div class="fs-2 fw-bold text-primary">{{ number_format($totalMiembros) }}</div>
                        <small class="text-muted">Total miembros</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card stat-card shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle p-3 me-3" style="background:#e3f2fd">
                        <i class="bi bi-person-badge-fill fs-4" style="color:#b71c1c"></i>
                    </div>
                    <div>
                        <div class="fs-2 fw-bold" style="color:#b71c1c">{{ number_format($totalMilitantes) }}</div>
                        <small class="text-muted">Militantes</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card stat-card shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle p-3 me-3" style="background:#f3e5f5">
                        <i class="bi bi-person-check fs-4 text-secondary"></i>
                    </div>
                    <div>
                        <div class="fs-2 fw-bold text-secondary">{{ number_format($totalSimpatizantes) }}</div>
                        <small class="text-muted">Simpatizantes</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card stat-card shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle p-3 me-3" style="background:#e8f5e9">
                        <i class="bi bi-geo-alt-fill fs-4 text-success"></i>
                    </div>
                    <div>
                        <div class="fs-2 fw-bold text-success">{{ number_format($totalLocalidades) }}</div>
                        <small class="text-muted">Localidades</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-7">
        <div class="card shadow-sm">
            <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                <span class="fw-semibold"><i class="bi bi-clock-history me-2 text-muted"></i>Últimos registros</span>
                <a href="{{ route('miembros.index') }}" class="btn btn-outline-secondary btn-sm">Ver todos</a>
            </div>
            <div class="card-body p-0">
                @if($miembrosRecientes->isEmpty())
                    <div class="text-center py-4 text-muted"><i class="bi bi-inbox fs-3 d-block mb-2"></i>Sin registros aún</div>
                @else
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Nombre</th>
                                <th>Tipo</th>
                                <th>Localidad</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($miembrosRecientes as $m)
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $m->nombre_completo }}</div>
                                    @if($m->cargo)<small class="text-muted">{{ $m->cargo->nombre }}</small>@endif
                                </td>
                                <td>
                                    <span class="badge {{ $m->tipo === 'militante' ? 'badge-militante' : 'badge-simpatizante' }} text-white">
                                        {{ ucfirst($m->tipo) }}
                                    </span>
                                </td>
                                <td>
                                    <small>{{ $m->localidad->nombre }}</small><br>
                                    <small class="text-muted">{{ $m->localidad->municipio->departamento->nombre }}</small>
                                </td>
                                <td>
                                    <a href="{{ route('miembros.show', $m) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-5">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                <span class="fw-semibold"><i class="bi bi-map me-2 text-muted"></i>Por departamento</span>
                <a href="{{ route('reportes.general') }}" class="btn btn-outline-secondary btn-sm">Reporte</a>
            </div>
            <div class="card-body p-0">
                @forelse($porDepartamento as $dep)
                @if($dep->total_miembros > 0)
                <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom">
                    <span>{{ $dep->nombre }}</span>
                    <span class="badge bg-primary rounded-pill">{{ $dep->total_miembros }}</span>
                </div>
                @endif
                @empty
                <div class="text-center py-4 text-muted"><i class="bi bi-inbox fs-3 d-block mb-2"></i>Sin datos</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
