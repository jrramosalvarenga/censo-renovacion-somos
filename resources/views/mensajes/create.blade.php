@extends('layouts.app')
@section('title', 'Nuevo mensaje masivo')

@section('content')
<div class="d-flex align-items-center mb-4 gap-2">
    <a href="{{ route('mensajes.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left"></i></a>
    <h4 class="mb-0 fw-bold"><i class="bi bi-whatsapp text-success me-2"></i>Nuevo mensaje masivo</h4>
</div>

<div class="row g-4">
    {{-- Formulario --}}
    <div class="col-lg-7">
        <div class="card shadow-sm">
            <div class="card-body">
                <form method="POST" action="{{ route('mensajes.store') }}" id="form-mensaje">
                    @csrf

                    {{-- Destino --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold">¿A quién enviar? <span class="text-danger">*</span></label>
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            <div class="form-check form-check-inline m-0">
                                <input class="form-check-input" type="radio" name="destino_tipo" id="d_todos" value="todos" {{ old('destino_tipo','todos')==='todos' ? 'checked':'' }}>
                                <label class="form-check-label fw-semibold text-danger" for="d_todos">
                                    🇭🇳 Todos Honduras
                                </label>
                            </div>
                            <div class="form-check form-check-inline m-0">
                                <input class="form-check-input" type="radio" name="destino_tipo" id="d_dep" value="departamento" {{ old('destino_tipo')==='departamento' ? 'checked':'' }}>
                                <label class="form-check-label" for="d_dep">Por departamento</label>
                            </div>
                            <div class="form-check form-check-inline m-0">
                                <input class="form-check-input" type="radio" name="destino_tipo" id="d_mun" value="municipio" {{ old('destino_tipo')==='municipio' ? 'checked':'' }}>
                                <label class="form-check-label" for="d_mun">Por municipio</label>
                            </div>
                            <div class="form-check form-check-inline m-0">
                                <input class="form-check-input" type="radio" name="destino_tipo" id="d_loc" value="localidad" {{ old('destino_tipo')==='localidad' ? 'checked':'' }}>
                                <label class="form-check-label" for="d_loc">Por comunidad</label>
                            </div>
                        </div>

                        {{-- Selectores territoriales --}}
                        <div id="selector_dep" class="row g-2" style="display:none!important">
                            <div class="col-12">
                                <select name="destino_id" id="sel_dep" class="form-select">
                                    <option value="">Seleccione departamento...</option>
                                    @foreach($departamentos as $dep)
                                        <option value="{{ $dep->id }}">{{ $dep->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div id="selector_mun" style="display:none!important">
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <select id="sel_dep_mun" class="form-select" onchange="cargarMunicipios(this.value, 'sel_mun')">
                                        <option value="">Seleccione departamento...</option>
                                        @foreach($departamentos as $dep)
                                            <option value="{{ $dep->id }}">{{ $dep->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <select name="destino_id" id="sel_mun" class="form-select">
                                        <option value="">Seleccione municipio...</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div id="selector_loc" style="display:none!important">
                            <div class="row g-2">
                                <div class="col-md-4">
                                    <select id="sel_dep_loc" class="form-select" onchange="cargarMunicipios(this.value, 'sel_mun_loc')">
                                        <option value="">Departamento...</option>
                                        @foreach($departamentos as $dep)
                                            <option value="{{ $dep->id }}">{{ $dep->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <select id="sel_mun_loc" class="form-select" onchange="cargarLocalidades(this.value)">
                                        <option value="">Municipio...</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <select name="destino_id" id="sel_loc" class="form-select">
                                        <option value="">Comunidad...</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Mensaje --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Mensaje <span class="text-danger">*</span></label>
                        <textarea name="contenido" id="contenido" class="form-control @error('contenido') is-invalid @enderror"
                                  rows="6" maxlength="1000" placeholder="Escribe el mensaje que recibirán los miembros por WhatsApp..."
                                  required>{{ old('contenido') }}</textarea>
                        <div class="d-flex justify-content-between mt-1">
                            @error('contenido')
                                <div class="text-danger small">{{ $message }}</div>
                            @else
                                <small class="text-muted">Máximo 1,000 caracteres</small>
                            @enderror
                            <small class="text-muted"><span id="char-count">0</span>/1000</small>
                        </div>
                    </div>

                    {{-- Botones --}}
                    <div class="d-flex gap-2 mt-4">
                        <button type="button" class="btn btn-outline-success" onclick="previsualizar()">
                            <i class="bi bi-eye me-1"></i>Previsualizar destinatarios
                        </button>
                        <button type="submit" class="btn btn-success" id="btn-enviar">
                            <i class="bi bi-whatsapp me-1"></i>Enviar mensajes
                        </button>
                        <a href="{{ route('mensajes.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Panel derecho --}}
    <div class="col-lg-5">
        {{-- Vista previa del mensaje --}}
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-success text-white py-2">
                <small class="fw-bold"><i class="bi bi-phone me-1"></i>Vista previa WhatsApp</small>
            </div>
            <div class="card-body" style="background:#e5ddd5; min-height:120px; border-radius:0 0 8px 8px;">
                <div class="d-flex justify-content-end">
                    <div class="bg-white rounded p-2 shadow-sm" style="max-width:85%; min-width:80px; font-size:0.9rem; border-radius:8px 0 8px 8px; word-break:break-word">
                        <div id="preview-texto" class="text-muted fst-italic">El mensaje aparecerá aquí...</div>
                        <div class="text-end mt-1">
                            <small class="text-muted" style="font-size:0.7rem">{{ now()->format('H:i') }} ✓✓</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Panel de previsualización de destinatarios --}}
        <div class="card shadow-sm" id="panel-preview" style="display:none">
            <div class="card-header bg-white border-bottom py-2">
                <small class="fw-bold text-success"><i class="bi bi-people me-1"></i>Destinatarios</small>
            </div>
            <div class="card-body" id="preview-contenido">
            </div>
        </div>

        {{-- Info de configuración --}}
        @if(!config('services.ultramsg.instance') || !config('services.ultramsg.token'))
        <div class="alert alert-warning mt-3">
            <i class="bi bi-exclamation-triangle me-2"></i>
            <strong>WhatsApp no configurado.</strong><br>
            <small>Agrega <code>ULTRAMSG_INSTANCE</code> y <code>ULTRAMSG_TOKEN</code> en el .env para activar el envío real.</small>
        </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
const tiposDestino = ['todos', 'departamento', 'municipio', 'localidad'];

// Mostrar/ocultar selectores según tipo de destino
document.querySelectorAll('input[name="destino_tipo"]').forEach(radio => {
    radio.addEventListener('change', actualizarSelector);
});
actualizarSelector();

function actualizarSelector() {
    const tipo = document.querySelector('input[name="destino_tipo"]:checked')?.value;
    document.getElementById('selector_dep').style.setProperty('display', tipo === 'departamento' ? 'block' : 'none', 'important');
    document.getElementById('selector_mun').style.setProperty('display', tipo === 'municipio'    ? 'block' : 'none', 'important');
    document.getElementById('selector_loc').style.setProperty('display', tipo === 'localidad'    ? 'block' : 'none', 'important');
    // Asegura que solo el select activo tenga name="destino_id"
    document.querySelectorAll('[name="destino_id"]').forEach(s => s.removeAttribute('name'));
    if (tipo === 'departamento') document.getElementById('sel_dep').name = 'destino_id';
    if (tipo === 'municipio')    document.getElementById('sel_mun').name = 'destino_id';
    if (tipo === 'localidad')    document.getElementById('sel_loc').name = 'destino_id';
}

// Contador de caracteres
document.getElementById('contenido').addEventListener('input', function() {
    document.getElementById('char-count').textContent = this.value.length;
    document.getElementById('preview-texto').textContent = this.value || '...';
    document.getElementById('preview-texto').classList.toggle('fst-italic', !this.value);
    document.getElementById('preview-texto').classList.toggle('text-muted', !this.value);
});

// Cargar municipios
function cargarMunicipios(depId, targetId) {
    const sel = document.getElementById(targetId);
    if (!depId) { sel.innerHTML = '<option value="">Seleccione municipio...</option>'; return; }
    fetch(`/mensajes/api/departamentos/${depId}/municipios`)
        .then(r => r.json())
        .then(data => {
            sel.innerHTML = '<option value="">Seleccione municipio...</option>' +
                data.map(m => `<option value="${m.id}">${m.nombre}</option>`).join('');
        });
}

// Cargar localidades
function cargarLocalidades(munId) {
    const sel = document.getElementById('sel_loc');
    if (!munId) { sel.innerHTML = '<option value="">Seleccione comunidad...</option>'; return; }
    fetch(`/mensajes/api/municipios/${munId}/localidades`)
        .then(r => r.json())
        .then(data => {
            sel.innerHTML = '<option value="">Seleccione comunidad...</option>' +
                data.map(l => `<option value="${l.id}">${l.nombre}</option>`).join('');
        });
}

// Previsualizar destinatarios
function previsualizar() {
    const tipo   = document.querySelector('input[name="destino_tipo"]:checked')?.value;
    const destId = document.querySelector('[name="destino_id"]')?.value;

    if (tipo !== 'todos' && !destId) {
        alert('Selecciona un destino primero.');
        return;
    }

    fetch(`/mensajes/preview?destino_tipo=${tipo}&destino_id=${destId || ''}`)
        .then(r => r.json())
        .then(data => {
            const panel = document.getElementById('panel-preview');
            const cont  = document.getElementById('preview-contenido');

            cont.innerHTML = `
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <div class="bg-success bg-opacity-10 rounded p-2 text-center">
                            <div class="fs-4 fw-bold text-success">${data.total}</div>
                            <small class="text-muted">Total miembros</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="bg-primary bg-opacity-10 rounded p-2 text-center">
                            <div class="fs-4 fw-bold text-primary">${data.con_telefono}</div>
                            <small class="text-muted">Con teléfono</small>
                        </div>
                    </div>
                </div>
                ${data.sin_telefono > 0 ? `<div class="alert alert-warning py-1 small mb-3"><i class="bi bi-exclamation-triangle me-1"></i>${data.sin_telefono} miembros sin teléfono serán omitidos.</div>` : ''}
                <p class="small text-muted mb-2"><strong>Destino:</strong> ${data.destino_nombre}</p>
                ${data.muestra.length > 0 ? `
                <p class="small text-muted mb-1"><strong>Muestra:</strong></p>
                <ul class="list-unstyled small mb-0">
                    ${data.muestra.map(m => `<li class="border-bottom py-1"><i class="bi bi-person me-1 text-success"></i>${m.nombre} — <code>${m.telefono}</code></li>`).join('')}
                    ${data.con_telefono > 5 ? `<li class="text-muted">... y ${data.con_telefono - 5} más</li>` : ''}
                </ul>` : '<p class="text-danger small mb-0">No hay miembros con teléfono en este destino.</p>'}
            `;
            panel.style.display = 'block';
        });
}

// Confirmar antes de enviar
document.getElementById('form-mensaje').addEventListener('submit', function(e) {
    const tipo = document.querySelector('input[name="destino_tipo"]:checked')?.value;
    const msg  = tipo === 'todos'
        ? '¿Enviar a TODOS los miembros de Honduras? Esta acción no se puede deshacer.'
        : '¿Confirmas el envío masivo de mensajes?';
    if (!confirm(msg)) e.preventDefault();
});
</script>
@endsection
