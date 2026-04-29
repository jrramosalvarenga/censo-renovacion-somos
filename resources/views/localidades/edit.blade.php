@extends('layouts.app')
@section('title', 'Editar localidad')

@section('content')
<div class="d-flex align-items-center mb-4 gap-2">
    <a href="{{ route('localidades.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left"></i></a>
    <h4 class="mb-0 fw-bold">Editar localidad</h4>
</div>

<div class="card shadow-sm" style="max-width:600px">
    <div class="card-body">
        <form method="POST" action="{{ route('localidades.update', $localidad) }}">
            @csrf @method('PUT')
            <div class="mb-3">
                <label class="form-label fw-semibold">Departamento</label>
                <select name="departamento_id" id="dep_sel" class="form-select">
                    @foreach($departamentos as $dep)
                        <option value="{{ $dep->id }}" @selected($dep->id == $localidad->municipio->departamento_id)>{{ $dep->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Municipio</label>
                <select name="municipio_id" id="mun_sel" class="form-select">
                    @foreach($municipios as $mun)
                        <option value="{{ $mun->id }}" @selected($mun->id == $localidad->municipio_id)>{{ $mun->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Nombre</label>
                <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $localidad->nombre) }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Tipo</label>
                <select name="tipo" class="form-select">
                    <option value="barrio" @selected($localidad->tipo=='barrio')>Barrio</option>
                    <option value="aldea" @selected($localidad->tipo=='aldea')>Aldea</option>
                    <option value="colonia" @selected($localidad->tipo=='colonia')>Colonia</option>
                    <option value="ciudad" @selected($localidad->tipo=='ciudad')>Ciudad</option>
                </select>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Guardar cambios</button>
                <a href="{{ route('localidades.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
const currentMunId = {{ $localidad->municipio_id }};
document.getElementById('dep_sel').addEventListener('change', function () {
    const depId = this.value;
    const munSel = document.getElementById('mun_sel');
    if (!depId) { munSel.innerHTML = '<option value="">Seleccione...</option>'; return; }
    fetch(`/api/departamentos/${depId}/municipios`).then(r => r.json()).then(data => {
        munSel.innerHTML = '<option value="">Seleccione...</option>' +
            data.map(m => `<option value="${m.id}" ${m.id == currentMunId ? 'selected' : ''}>${m.nombre}</option>`).join('');
    });
});
</script>
@endsection
