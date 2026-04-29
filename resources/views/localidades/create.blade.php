@extends('layouts.app')
@section('title', 'Nueva localidad')

@section('content')
<div class="d-flex align-items-center mb-4 gap-2">
    <a href="{{ route('localidades.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left"></i></a>
    <h4 class="mb-0 fw-bold">Nueva localidad</h4>
</div>

<div class="card shadow-sm" style="max-width:600px">
    <div class="card-body">
        <form method="POST" action="{{ route('localidades.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-semibold">Departamento <span class="text-danger">*</span></label>
                <select name="departamento_id" id="dep_sel" class="form-select" required>
                    <option value="">Seleccione...</option>
                    @foreach($departamentos as $dep)
                        <option value="{{ $dep->id }}" @selected(old('departamento_id') == $dep->id)>{{ $dep->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Municipio <span class="text-danger">*</span></label>
                <select name="municipio_id" id="mun_sel" class="form-select @error('municipio_id') is-invalid @enderror" required>
                    <option value="">Seleccione departamento primero...</option>
                </select>
                @error('municipio_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Nombre de la localidad <span class="text-danger">*</span></label>
                <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror"
                       value="{{ old('nombre') }}" required>
                @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Tipo <span class="text-danger">*</span></label>
                <select name="tipo" class="form-select" required>
                    <option value="barrio" @selected(old('tipo')=='barrio')>Barrio</option>
                    <option value="aldea" @selected(old('tipo')=='aldea')>Aldea</option>
                    <option value="colonia" @selected(old('tipo')=='colonia')>Colonia</option>
                    <option value="ciudad" @selected(old('tipo')=='ciudad')>Ciudad</option>
                </select>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Guardar</button>
                <a href="{{ route('localidades.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.getElementById('dep_sel').addEventListener('change', function () {
    const depId = this.value;
    const munSel = document.getElementById('mun_sel');
    if (!depId) { munSel.innerHTML = '<option value="">Seleccione departamento primero...</option>'; return; }
    fetch(`/api/departamentos/${depId}/municipios`).then(r => r.json()).then(data => {
        munSel.innerHTML = '<option value="">Seleccione...</option>' +
            data.map(m => `<option value="${m.id}">${m.nombre}</option>`).join('');
    });
});
</script>
@endsection
