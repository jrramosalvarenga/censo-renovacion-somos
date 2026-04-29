@extends('layouts.app')
@section('title', 'Detalle del envío')

@section('content')
<div class="d-flex align-items-center mb-4 gap-2">
    <a href="{{ route('mensajes.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left"></i></a>
    <h4 class="mb-0 fw-bold"><i class="bi bi-whatsapp text-success me-2"></i>Detalle del envío</h4>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card shadow-sm text-center p-3">
            <div class="fs-2 fw-bold text-secondary">{{ $mensaje->total_destinatarios }}</div>
            <small class="text-muted">Total</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm text-center p-3">
            <div class="fs-2 fw-bold text-success">{{ $mensaje->enviados }}</div>
            <small class="text-muted">Enviados</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm text-center p-3">
            <div class="fs-2 fw-bold text-danger">{{ $mensaje->fallidos }}</div>
            <small class="text-muted">Fallidos</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm text-center p-3">
            <div class="fs-2 fw-bold text-primary">{{ $mensaje->porcentaje }}%</div>
            <small class="text-muted">Éxito</small>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-4">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Detalles del envío</h6>
                <dl class="mb-0 small">
                    <dt class="text-muted">Destino</dt>
                    <dd>{{ $mensaje->destino_nombre }}</dd>
                    <dt class="text-muted">Estado</dt>
                    <dd>
                        @php $badge = match($mensaje->estado) { 'completado'=>'bg-success','fallido'=>'bg-danger','enviando'=>'bg-warning text-dark',default=>'bg-secondary' }; @endphp
                        <span class="badge {{ $badge }}">{{ ucfirst($mensaje->estado) }}</span>
                    </dd>
                    <dt class="text-muted">Enviado por</dt>
                    <dd>{{ $mensaje->user->name }}</dd>
                    <dt class="text-muted">Fecha</dt>
                    <dd>{{ $mensaje->created_at->format('d/m/Y H:i') }}</dd>
                </dl>
                <hr>
                <h6 class="fw-bold mb-2">Mensaje enviado</h6>
                <div class="bg-light rounded p-2 small" style="word-break:break-word">
                    {{ $mensaje->contenido }}
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                <span class="fw-semibold small">Registro de envíos</span>
                <div class="d-flex gap-2">
                    <span class="badge bg-success">✓ {{ $mensaje->enviados }} enviados</span>
                    @if($mensaje->fallidos > 0)
                    <span class="badge bg-danger">✗ {{ $mensaje->fallidos }} fallidos</span>
                    @endif
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 small">
                        <thead class="table-light">
                            <tr>
                                <th>Miembro</th>
                                <th>Teléfono</th>
                                <th>Estado</th>
                                <th>Hora</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($envios as $e)
                        <tr>
                            <td>{{ $e->miembro->nombre_completo }}</td>
                            <td><code>{{ $e->telefono }}</code></td>
                            <td>
                                @if($e->estado === 'enviado')
                                    <span class="badge bg-success"><i class="bi bi-check2 me-1"></i>Enviado</span>
                                @else
                                    <span class="badge bg-danger" title="{{ $e->error }}">
                                        <i class="bi bi-x me-1"></i>Fallido
                                    </span>
                                    @if($e->error)
                                    <br><small class="text-danger">{{ $e->error }}</small>
                                    @endif
                                @endif
                            </td>
                            <td>{{ $e->sent_at?->format('H:i:s') ?? '—' }}</td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="p-3">{{ $envios->links() }}</div>
            </div>
        </div>
    </div>
</div>
@endsection
