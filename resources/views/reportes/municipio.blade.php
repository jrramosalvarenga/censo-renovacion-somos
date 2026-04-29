@extends('layouts.app')
@section('title', 'Reporte — '.$municipio->nombre)

@section('content')
<div class="d-flex align-items-center mb-4 gap-2">
    <a href="{{ route('reportes.departamento') }}?departamento_id={{ $municipio->departamento_id }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left"></i></a>
    <div>
        <h4 class="mb-0 fw-bold">{{ $municipio->nombre }}</h4>
        <small class="text-muted">{{ $municipio->departamento->nombre }}</small>
    </div>
    <div class="ms-auto">
        <a href="{{ route('reportes.municipio') }}?municipio_id={{ $municipio->id }}&formato=pdf" class="btn btn-danger btn-sm">
            <i class="bi bi-file-pdf me-1"></i>PDF
        </a>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card shadow-sm text-center p-3">
            <div class="fs-1 fw-bold text-primary">{{ number_format($totales['miembros']) }}</div>
            <div class="text-muted small">Total</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm text-center p-3">
            <div class="fs-1 fw-bold" style="color:#1a3a5c">{{ number_format($totales['militantes']) }}</div>
            <div class="text-muted small">Militantes</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm text-center p-3">
            <div class="fs-1 fw-bold text-secondary">{{ number_format($totales['simpatizantes']) }}</div>
            <div class="text-muted small">Simpatizantes</div>
        </div>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-white fw-semibold">Localidades de {{ $municipio->nombre }}</div>
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th>Localidad</th>
                    <th>Tipo</th>
                    <th class="text-end">Total</th>
                    <th class="text-end">Militantes</th>
                    <th class="text-end">Simpatizantes</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            @forelse($localidades as $loc)
            <tr>
                <td class="fw-semibold">{{ $loc->nombre }}</td>
                <td><span class="badge bg-light text-dark border">{{ ucfirst($loc->tipo) }}</span></td>
                <td class="text-end">{{ $loc->miembros_count }}</td>
                <td class="text-end text-primary">{{ $loc->militantes_count }}</td>
                <td class="text-end text-secondary">{{ $loc->simpatizantes_count }}</td>
                <td>
                    <a href="{{ route('reportes.localidad') }}?localidad_id={{ $loc->id }}" class="btn btn-sm btn-outline-primary">Ver</a>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="text-center text-muted py-4">Sin localidades con datos</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
