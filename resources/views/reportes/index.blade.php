@extends('layouts.app')
@section('title', 'Reportes')

@section('content')
<h4 class="mb-4 fw-bold">Reportes de Militancia</h4>

<div class="row g-3">
    <div class="col-md-6">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h6 class="fw-bold mb-3"><i class="bi bi-globe-americas me-2 text-primary"></i>Reporte General</h6>
                <p class="text-muted small">Resumen de militancia por todos los departamentos de Honduras.</p>
                <a href="{{ route('reportes.general') }}" class="btn btn-primary btn-sm">Ver reporte</a>
                <a href="{{ route('reportes.general') }}?formato=pdf" class="btn btn-outline-danger btn-sm">
                    <i class="bi bi-file-pdf me-1"></i>PDF
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h6 class="fw-bold mb-3"><i class="bi bi-map me-2 text-success"></i>Reporte por Departamento</h6>
                <form method="GET" action="{{ route('reportes.departamento') }}" class="d-flex gap-2 align-items-end">
                    <div class="flex-grow-1">
                        <label class="form-label small mb-1">Seleccione departamento</label>
                        <select name="departamento_id" class="form-select form-select-sm" required>
                            <option value="">Seleccione...</option>
                            @foreach($departamentos as $dep)
                                <option value="{{ $dep->id }}">{{ $dep->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button class="btn btn-success btn-sm">Ver</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h6 class="fw-bold mb-3"><i class="bi bi-building me-2 text-warning"></i>Reporte por Municipio</h6>
                <form method="GET" action="{{ route('reportes.municipio') }}">
                    <div class="mb-2">
                        <select name="departamento_id" id="dep_r" class="form-select form-select-sm mb-2" onchange="loadMunicipios(this.value)">
                            <option value="">Seleccione departamento...</option>
                            @foreach($departamentos as $dep)
                                <option value="{{ $dep->id }}">{{ $dep->nombre }}</option>
                            @endforeach
                        </select>
                        <select name="municipio_id" id="mun_r" class="form-select form-select-sm" required>
                            <option value="">Primero seleccione departamento...</option>
                        </select>
                    </div>
                    <button class="btn btn-warning btn-sm text-white">Ver reporte</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h6 class="fw-bold mb-3"><i class="bi bi-pin-map me-2 text-danger"></i>Reporte por Localidad</h6>
                <form method="GET" action="{{ route('reportes.localidad') }}">
                    <div class="mb-2">
                        <select name="departamento_id" id="dep_l" class="form-select form-select-sm mb-2" onchange="loadMunicipiosLoc(this.value)">
                            <option value="">Seleccione departamento...</option>
                            @foreach($departamentos as $dep)
                                <option value="{{ $dep->id }}">{{ $dep->nombre }}</option>
                            @endforeach
                        </select>
                        <select name="municipio_id" id="mun_l" class="form-select form-select-sm mb-2" onchange="loadLocalidades(this.value)">
                            <option value="">Primero seleccione departamento...</option>
                        </select>
                        <select name="localidad_id" id="loc_l" class="form-select form-select-sm" required>
                            <option value="">Primero seleccione municipio...</option>
                        </select>
                    </div>
                    <button class="btn btn-danger btn-sm">Ver reporte</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function loadMunicipios(depId) {
    const sel = document.getElementById('mun_r');
    if (!depId) { sel.innerHTML = '<option value="">Primero seleccione departamento...</option>'; return; }
    fetch(`/reportes/api/departamentos/${depId}/municipios`).then(r => r.json()).then(data => {
        sel.innerHTML = '<option value="">Seleccione...</option>' + data.map(m => `<option value="${m.id}">${m.nombre}</option>`).join('');
    });
}
function loadMunicipiosLoc(depId) {
    const sel = document.getElementById('mun_l');
    document.getElementById('loc_l').innerHTML = '<option value="">Primero seleccione municipio...</option>';
    if (!depId) { sel.innerHTML = '<option value="">Primero seleccione departamento...</option>'; return; }
    fetch(`/reportes/api/departamentos/${depId}/municipios`).then(r => r.json()).then(data => {
        sel.innerHTML = '<option value="">Seleccione...</option>' + data.map(m => `<option value="${m.id}">${m.nombre}</option>`).join('');
    });
}
function loadLocalidades(munId) {
    const sel = document.getElementById('loc_l');
    if (!munId) { sel.innerHTML = '<option value="">Primero seleccione municipio...</option>'; return; }
    fetch(`/reportes/api/municipios/${munId}/localidades`).then(r => r.json()).then(data => {
        sel.innerHTML = '<option value="">Seleccione...</option>' + data.map(l => `<option value="${l.id}">${l.nombre} (${l.tipo})</option>`).join('');
    });
}
</script>
@endsection
