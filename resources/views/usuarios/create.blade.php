@extends('layouts.app')
@section('title', 'Nuevo usuario')

@section('content')
<div class="d-flex align-items-center mb-4 gap-2">
    <a href="{{ route('usuarios.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left"></i></a>
    <h4 class="mb-0 fw-bold">Nuevo usuario</h4>
</div>

<div class="card shadow-sm" style="max-width:560px">
    <div class="card-body">
        <form method="POST" action="{{ route('usuarios.store') }}">
            @csrf
            @include('usuarios._form')
            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Crear usuario</button>
                <a href="{{ route('usuarios.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.getElementById('rol_select').addEventListener('change', toggleMunicipio);
document.getElementById('dep_select').addEventListener('change', function() {
    const depId = this.value;
    const munSel = document.getElementById('mun_select');
    if (!depId) { munSel.innerHTML = '<option value="">Seleccione municipio...</option>'; return; }
    fetch(`/api/usuarios/departamentos/${depId}/municipios`)
        .then(r => r.json())
        .then(data => {
            munSel.innerHTML = '<option value="">Seleccione...</option>' +
                data.map(m => `<option value="${m.id}">${m.nombre}</option>`).join('');
        });
});
function toggleMunicipio() {
    const rol = document.getElementById('rol_select').value;
    document.getElementById('municipio_section').style.display = rol === 'operador' ? 'block' : 'none';
}
toggleMunicipio();
</script>
@endsection
