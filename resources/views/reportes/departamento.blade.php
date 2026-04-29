@extends('layouts.app')
@section('title', 'Reporte — '.$departamento->nombre)

@section('content')
<div class="d-flex align-items-center mb-4 gap-2">
    <a href="{{ route('reportes.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left"></i></a>
    <h4 class="mb-0 fw-bold">{{ $departamento->nombre }}</h4>
    <div class="ms-auto">
        <a href="{{ route('reportes.departamento') }}?departamento_id={{ $departamento->id }}&formato=pdf" class="btn btn-danger btn-sm">
            <i class="bi bi-file-pdf me-1"></i>PDF
        </a>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card shadow-sm text-center p-3">
            <div class="fs-1 fw-bold text-primary">{{ number_format($totales['miembros']) }}</div>
            <div class="text-muted small">Total miembros</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm text-center p-3">
            <div class="fs-1 fw-bold" style="color:#b71c1c">{{ number_format($totales['militantes']) }}</div>
            <div class="text-muted small">Militantes</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm text-center p-3">
            <div class="fs-1 fw-bold text-secondary">{{ number_format($totales['miembros'] - $totales['militantes']) }}</div>
            <div class="text-muted small">Simpatizantes</div>
        </div>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-white fw-semibold">Municipios de {{ $departamento->nombre }}</div>
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th>Municipio</th>
                    <th class="text-end">Total</th>
                    <th class="text-end">Militantes</th>
                    <th class="text-end">Simpatizantes</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            @forelse($municipios as $mun)
            <tr>
                <td class="fw-semibold">{{ $mun->nombre }}</td>
                <td class="text-end">{{ $mun->miembros_count }}</td>
                <td class="text-end text-primary">{{ $mun->militantes_count }}</td>
                <td class="text-end text-secondary">{{ $mun->miembros_count - $mun->militantes_count }}</td>
                <td>
                    <a href="{{ route('reportes.municipio') }}?municipio_id={{ $mun->id }}" class="btn btn-sm btn-outline-primary">Ver</a>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="text-center text-muted py-4">Sin municipios con datos</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
