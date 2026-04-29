@extends('layouts.app')
@section('title', 'Nuevo miembro')

@section('content')
<div class="d-flex align-items-center mb-4 gap-2">
    <a href="{{ route('miembros.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left"></i></a>
    <h4 class="mb-0 fw-bold">Nuevo miembro</h4>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="POST" action="{{ route('miembros.store') }}" enctype="multipart/form-data">
            @csrf
            @include('miembros._form')
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Registrar miembro</button>
                <a href="{{ route('miembros.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
const apiMun = (depId) => `/api/departamentos/${depId}/municipios`;
const apiLoc = (munId) => `/api/municipios/${munId}/localidades`;

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
            data.map(l => `<option value="${l.id}">${l.nombre} (${l.tipo})</option>`).join('');
    });
});
</script>
@endsection
