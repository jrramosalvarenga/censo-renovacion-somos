@extends('layouts.app')
@section('title', 'Editar miembro')

@section('content')
<div class="d-flex align-items-center mb-4 gap-2">
    <a href="{{ route('miembros.show', $miembro) }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left"></i></a>
    <h4 class="mb-0 fw-bold">Editar miembro</h4>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="POST" action="{{ route('miembros.update', $miembro) }}" enctype="multipart/form-data">
            @csrf @method('PUT')
            @include('miembros._form')
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Guardar cambios</button>
                <a href="{{ route('miembros.show', $miembro) }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
const apiMun = (depId) => `/api/departamentos/${depId}/municipios`;
const apiLoc = (munId) => `/api/municipios/${munId}/localidades`;
const currentMunId = {{ $miembro->localidad->municipio_id }};
const currentLocId = {{ $miembro->localidad_id }};

document.getElementById('dep_select').addEventListener('change', function () {
    const depId = this.value;
    const munSel = document.getElementById('mun_select');
    const locSel = document.getElementById('loc_select');
    munSel.innerHTML = '<option value="">Cargando...</option>';
    locSel.innerHTML = '<option value="">Seleccione municipio...</option>';
    if (!depId) { munSel.innerHTML = '<option value="">Seleccione departamento...</option>'; return; }
    fetch(apiMun(depId)).then(r => r.json()).then(data => {
        munSel.innerHTML = '<option value="">Seleccione...</option>' +
            data.map(m => `<option value="${m.id}">${m.nombre}</option>`).join('');
    });
});

document.getElementById('mun_select').addEventListener('change', function () {
    const munId = this.value;
    const locSel = document.getElementById('loc_select');
    locSel.innerHTML = '<option value="">Cargando...</option>';
    if (!munId) { locSel.innerHTML = '<option value="">Seleccione municipio...</option>'; return; }
    fetch(apiLoc(munId)).then(r => r.json()).then(data => {
        locSel.innerHTML = '<option value="">Seleccione...</option>' +
            data.map(l => `<option value="${l.id}" ${l.id == currentLocId ? 'selected' : ''}>${l.nombre} (${l.tipo})</option>`).join('');
    });
});
</script>
@endsection
