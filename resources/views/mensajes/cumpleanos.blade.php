@extends('layouts.app')
@section('title', 'Felicitaciones de Cumpleaños')

@section('content')
<div class="d-flex align-items-center mb-4 gap-2">
    <a href="{{ route('mensajes.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left"></i></a>
    <h4 class="mb-0 fw-bold"><i class="bi bi-cake2 text-danger me-2"></i>Felicitaciones de Cumpleaños</h4>
</div>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card shadow-sm">
            <div class="card-header bg-white border-bottom">
                <span class="fw-semibold">Mensaje de Rafa Sarmiento</span>
                <small class="text-muted ms-2">— usa <code>[NOMBRE]</code> para personalizar</small>
            </div>
            <div class="card-body">

                @if($miembros->isEmpty())
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        Hoy <strong>{{ $hoy->format('d \d\e F') }}</strong> no hay cumpleañeros registrados con teléfono.
                    </div>
                @else
                    <div class="alert alert-success py-2 mb-3">
                        <i class="bi bi-people me-2"></i>
                        <strong>{{ $miembros->count() }}</strong> cumpleañero(s) hoy —
                        {{ $hoy->format('d \d\e F') }} 🎂
                    </div>
                @endif

                <form method="POST" action="{{ route('mensajes.cumpleanos.enviar') }}">
                    @csrf
                    <div class="mb-3">
                        <textarea name="mensaje_template" id="msg_template"
                                  class="form-control @error('mensaje_template') is-invalid @enderror"
                                  rows="9" maxlength="1000">{{ old('mensaje_template',
'¡Feliz cumpleaños, [NOMBRE]! 🎂

Hoy quiero tomarte un momento para felicitarte en tu día especial. Tu presencia y compromiso con Honduras son una inspiración para mí y para todo nuestro movimiento.

Que este nuevo año de vida te llene de salud, de logros y de la energía que Honduras necesita de cada uno de nosotros.

Con mucho cariño,
*Rafa Sarmiento*
_Renovación Somos_ 🇭🇳') }}</textarea>
                        @error('mensaje_template')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted"><span id="char_count">0</span>/1000 caracteres</small>
                    </div>

                    <button type="submit" class="btn btn-danger w-100"
                            @if($miembros->isEmpty()) disabled @endif
                            onclick="return confirm('¿Enviar felicitaciones a {{ $miembros->count() }} cumpleañero(s)?')">
                        <i class="bi bi-whatsapp me-2"></i>
                        Enviar felicitaciones a {{ $miembros->count() }} cumpleañero(s)
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        {{-- Vista previa del mensaje --}}
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-success text-white py-2">
                <small class="fw-bold"><i class="bi bi-phone me-1"></i>Vista previa</small>
            </div>
            <div class="card-body" style="background:#e5ddd5; min-height:160px; border-radius:0 0 8px 8px;">
                <div class="d-flex justify-content-end">
                    <div class="bg-white rounded p-2 shadow-sm"
                         style="max-width:90%; font-size:0.85rem; border-radius:8px 0 8px 8px; word-break:break-word; white-space:pre-wrap">
                        <div id="preview_msg" class="text-muted fst-italic">El mensaje aparecerá aquí...</div>
                        <div class="text-end mt-1">
                            <small class="text-muted" style="font-size:0.7rem">{{ now()->format('H:i') }} ✓✓</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Lista de cumpleañeros --}}
        @if($miembros->isNotEmpty())
        <div class="card shadow-sm">
            <div class="card-header bg-white border-bottom">
                <small class="fw-bold text-danger"><i class="bi bi-cake2 me-1"></i>Cumpleañeros de hoy</small>
            </div>
            <ul class="list-group list-group-flush">
                @foreach($miembros as $m)
                <li class="list-group-item d-flex justify-content-between align-items-center py-2">
                    <div>
                        <div class="fw-semibold small">{{ $m->nombre_completo }}</div>
                        <small class="text-muted">
                            <i class="bi bi-whatsapp text-success me-1"></i>{{ $m->telefono }}
                            @if($m->fecha_nacimiento)
                                · {{ now()->year - $m->fecha_nacimiento->year }} años
                            @endif
                        </small>
                    </div>
                    <span class="badge bg-danger rounded-pill">🎂</span>
                </li>
                @endforeach
            </ul>
        </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
const ta = document.getElementById('msg_template');
const preview = document.getElementById('preview_msg');
const counter = document.getElementById('char_count');

function actualizarPreview() {
    const texto = ta.value;
    counter.textContent = texto.length;
    // Mostrar con nombre de ejemplo
    const ejemplo = texto.replace(/\[NOMBRE\]/gi, 'María');
    preview.textContent = ejemplo || '...';
    preview.classList.toggle('fst-italic', !texto);
    preview.classList.toggle('text-muted', !texto);
}

ta.addEventListener('input', actualizarPreview);
actualizarPreview();
</script>
@endsection
