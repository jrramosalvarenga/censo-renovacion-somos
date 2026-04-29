@extends('layouts.app')
@section('title', 'Reporte — '.$localidad->nombre)

@section('content')
<div class="d-flex align-items-center mb-4 gap-2">
    <a href="{{ route('reportes.municipio') }}?municipio_id={{ $localidad->municipio_id }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left"></i></a>
    <div>
        <h4 class="mb-0 fw-bold">{{ $localidad->nombre }}</h4>
        <small class="text-muted">{{ $localidad->municipio->nombre }}, {{ $localidad->municipio->departamento->nombre }} — {{ ucfirst($localidad->tipo) }}</small>
    </div>
    <div class="ms-auto">
        <a href="{{ route('reportes.localidad') }}?localidad_id={{ $localidad->id }}&formato=pdf" class="btn btn-danger btn-sm">
            <i class="bi bi-file-pdf me-1"></i>PDF
        </a>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card shadow-sm text-center p-3">
            <div class="fs-2 fw-bold text-primary">{{ $stats['total'] }}</div>
            <small class="text-muted">Total</small>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card shadow-sm text-center p-3">
            <div class="fs-2 fw-bold" style="color:#1a3a5c">{{ $stats['militantes'] }}</div>
            <small class="text-muted">Militantes</small>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card shadow-sm text-center p-3">
            <div class="fs-2 fw-bold text-secondary">{{ $stats['simpatizantes'] }}</div>
            <small class="text-muted">Simpatizantes</small>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card shadow-sm text-center p-3">
            <div class="fs-2 fw-bold text-success">{{ $stats['activos'] }}</div>
            <small class="text-muted">Activos</small>
        </div>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-white fw-semibold">Lista de miembros</div>
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Identidad</th>
                    <th>Teléfono</th>
                    <th>Tipo</th>
                    <th>Cargo</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
            @forelse($miembros as $i => $m)
            <tr>
                <td class="text-muted">{{ $i+1 }}</td>
                <td class="fw-semibold">{{ $m->nombre_completo }}</td>
                <td><small>{{ $m->identidad ?? '—' }}</small></td>
                <td><small>{{ $m->telefono ?? '—' }}</small></td>
                <td>
                    <span class="badge {{ $m->tipo==='militante'?'badge-militante':'badge-simpatizante' }} text-white">
                        {{ ucfirst($m->tipo) }}
                    </span>
                </td>
                <td><small>{{ $m->cargo?->nombre ?? '—' }}</small></td>
                <td>
                    <span class="badge {{ $m->estado==='activo'?'bg-success':'bg-secondary' }}">{{ ucfirst($m->estado) }}</span>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="text-center text-muted py-4">Sin miembros en esta localidad</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
