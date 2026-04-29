@extends('layouts.app')
@section('title', 'Reporte General')

@section('content')
<div class="d-flex align-items-center mb-4 gap-2">
    <a href="{{ route('reportes.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left"></i></a>
    <h4 class="mb-0 fw-bold">Reporte General de Militancia</h4>
    <div class="ms-auto">
        <a href="{{ route('reportes.general') }}?formato=pdf" class="btn btn-danger btn-sm">
            <i class="bi bi-file-pdf me-1"></i>Descargar PDF
        </a>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card shadow-sm text-center p-3">
            <div class="fs-1 fw-bold text-primary">{{ number_format($totales['miembros']) }}</div>
            <div class="text-muted">Total miembros</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm text-center p-3">
            <div class="fs-1 fw-bold" style="color:#b71c1c">{{ number_format($totales['militantes']) }}</div>
            <div class="text-muted">Militantes</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm text-center p-3">
            <div class="fs-1 fw-bold text-secondary">{{ number_format($totales['simpatizantes']) }}</div>
            <div class="text-muted">Simpatizantes</div>
        </div>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-white border-bottom fw-semibold">Militancia por departamento</div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Departamento</th>
                    <th class="text-end">Total</th>
                    <th class="text-end">Militantes</th>
                    <th class="text-end">Simpatizantes</th>
                    <th>Distribución</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            @foreach($departamentos as $dep)
            @if($dep->miembros_count > 0)
            <tr>
                <td class="fw-semibold">{{ $dep->nombre }}</td>
                <td class="text-end">{{ $dep->miembros_count }}</td>
                <td class="text-end text-primary">{{ $dep->militantes_count }}</td>
                <td class="text-end text-secondary">{{ $dep->miembros_count - $dep->militantes_count }}</td>
                <td style="min-width:150px">
                    @if($dep->miembros_count > 0)
                    <div class="progress" style="height:8px">
                        <div class="progress-bar" style="width:{{ round($dep->militantes_count/$dep->miembros_count*100) }}%;background:#b71c1c"></div>
                        <div class="progress-bar bg-secondary" style="width:{{ round(($dep->miembros_count-$dep->militantes_count)/$dep->miembros_count*100) }}%"></div>
                    </div>
                    @endif
                </td>
                <td>
                    <a href="{{ route('reportes.departamento') }}?departamento_id={{ $dep->id }}" class="btn btn-sm btn-outline-primary">Ver</a>
                </td>
            </tr>
            @endif
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
