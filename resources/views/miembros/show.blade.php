@extends('layouts.app')
@section('title', $miembro->nombre_completo)

@section('content')
<div class="d-flex align-items-center mb-4 gap-2">
    <a href="{{ route('miembros.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left"></i></a>
    <h4 class="mb-0 fw-bold">Detalle del miembro</h4>
    <div class="ms-auto d-flex gap-2">
        <a href="{{ route('miembros.edit', $miembro) }}" class="btn btn-primary btn-sm">
            <i class="bi bi-pencil me-1"></i>Editar
        </a>
        <form method="POST" action="{{ route('miembros.destroy', $miembro) }}" onsubmit="return confirm('¿Eliminar este miembro?')">
            @csrf @method('DELETE')
            <button class="btn btn-outline-danger btn-sm"><i class="bi bi-trash me-1"></i>Eliminar</button>
        </form>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-3 text-center">
        <div class="card shadow-sm p-3">
            @if($miembro->foto)
                @php $fotoUrl = str_starts_with($miembro->foto, 'http') ? $miembro->foto : asset('storage/'.$miembro->foto); @endphp
                <img src="{{ $fotoUrl }}" class="rounded-circle mb-3 mx-auto d-block" width="100" height="100" style="object-fit:cover">
            @else
                <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white mx-auto mb-3" style="width:100px;height:100px;font-size:2rem">
                    {{ strtoupper(substr($miembro->nombres,0,1).substr($miembro->apellidos,0,1)) }}
                </div>
            @endif
            <h5 class="fw-bold mb-1">{{ $miembro->nombre_completo }}</h5>
            <span class="badge {{ $miembro->tipo === 'militante' ? 'badge-militante' : 'badge-simpatizante' }} text-white mb-2">
                {{ ucfirst($miembro->tipo) }}
            </span><br>
            <span class="badge {{ $miembro->estado === 'activo' ? 'bg-success' : 'bg-secondary' }}">
                {{ ucfirst($miembro->estado) }}
            </span>
            @if($miembro->cargo)
                <div class="mt-2 small text-muted"><i class="bi bi-briefcase me-1"></i>{{ $miembro->cargo->nombre }}</div>
            @endif
        </div>
    </div>
    <div class="col-md-9">
        <div class="card shadow-sm">
            <div class="card-body">
                <h6 class="text-muted text-uppercase fw-bold mb-3" style="font-size:0.75rem">Datos personales</h6>
                <div class="row g-3">
                    <div class="col-md-4">
                        <small class="text-muted d-block">No. Identidad</small>
                        <span>{{ $miembro->identidad ?? '—' }}</span>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">Fecha de nacimiento</small>
                        <span>{{ $miembro->fecha_nacimiento ? $miembro->fecha_nacimiento->format('d/m/Y') : '—' }}</span>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">Sexo</small>
                        <span>{{ $miembro->sexo === 'M' ? 'Masculino' : ($miembro->sexo === 'F' ? 'Femenino' : '—') }}</span>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">Teléfono</small>
                        <span>{{ $miembro->telefono ?? '—' }}</span>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">Correo electrónico</small>
                        <span>{{ $miembro->email ?? '—' }}</span>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">Dirección</small>
                        <span>{{ $miembro->direccion ?? '—' }}</span>
                    </div>
                </div>

                <hr>
                <h6 class="text-muted text-uppercase fw-bold mb-3" style="font-size:0.75rem">Ubicación territorial</h6>
                <div class="row g-3">
                    <div class="col-md-4">
                        <small class="text-muted d-block">Departamento</small>
                        <span>{{ $miembro->localidad->municipio->departamento->nombre }}</span>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">Municipio</small>
                        <span>{{ $miembro->localidad->municipio->nombre }}</span>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">{{ ucfirst($miembro->localidad->tipo) }}</small>
                        <span>{{ $miembro->localidad->nombre }}</span>
                    </div>
                </div>

                @if($miembro->observaciones)
                <hr>
                <h6 class="text-muted text-uppercase fw-bold mb-2" style="font-size:0.75rem">Observaciones</h6>
                <p class="mb-0">{{ $miembro->observaciones }}</p>
                @endif

                <hr>
                <small class="text-muted">Registrado el {{ $miembro->created_at->format('d/m/Y H:i') }}</small>
            </div>
        </div>
    </div>
</div>
@endsection
