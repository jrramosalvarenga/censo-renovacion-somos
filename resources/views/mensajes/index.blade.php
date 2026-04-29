@extends('layouts.app')
@section('title', 'Mensajes WhatsApp')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0 fw-bold"><i class="bi bi-whatsapp text-success me-2"></i>Mensajes Masivos WhatsApp</h4>
        <small class="text-muted">Historial de envíos</small>
    </div>
    <a href="{{ route('mensajes.create') }}" class="btn btn-success">
        <i class="bi bi-send me-1"></i>Nuevo mensaje
    </a>
</div>

@if($mensajes->isEmpty())
    <div class="card shadow-sm">
        <div class="card-body text-center py-5 text-muted">
            <i class="bi bi-chat-dots fs-1 d-block mb-3 text-success opacity-50"></i>
            <p class="mb-1 fw-semibold">Aún no se han enviado mensajes</p>
            <a href="{{ route('mensajes.create') }}" class="btn btn-success mt-2">Enviar primer mensaje</a>
        </div>
    </div>
@else
<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Mensaje</th>
                        <th>Destino</th>
                        <th class="text-center">Enviados</th>
                        <th class="text-center">Fallidos</th>
                        <th>Estado</th>
                        <th>Enviado por</th>
                        <th>Fecha</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                @foreach($mensajes as $m)
                <tr>
                    <td style="max-width:200px">
                        <div class="text-truncate" title="{{ $m->contenido }}">{{ $m->contenido }}</div>
                    </td>
                    <td>
                        <span class="badge bg-light text-dark border">
                            {{ $m->destino_nombre }}
                        </span>
                    </td>
                    <td class="text-center">
                        <span class="text-success fw-bold">{{ $m->enviados }}</span>
                    </td>
                    <td class="text-center">
                        <span class="{{ $m->fallidos > 0 ? 'text-danger fw-bold' : 'text-muted' }}">
                            {{ $m->fallidos }}
                        </span>
                    </td>
                    <td>
                        @php
                            $badge = match($m->estado) {
                                'completado' => 'bg-success',
                                'enviando'   => 'bg-warning text-dark',
                                'fallido'    => 'bg-danger',
                                default      => 'bg-secondary',
                            };
                        @endphp
                        <span class="badge {{ $badge }}">{{ ucfirst($m->estado) }}</span>
                    </td>
                    <td><small>{{ $m->user->name }}</small></td>
                    <td><small>{{ $m->created_at->format('d/m/Y H:i') }}</small></td>
                    <td>
                        <a href="{{ route('mensajes.show', $m) }}" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-eye"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-3">{{ $mensajes->links() }}</div>
    </div>
</div>
@endif
@endsection
