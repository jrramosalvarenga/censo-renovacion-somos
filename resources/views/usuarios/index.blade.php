@extends('layouts.app')
@section('title', 'Usuarios')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0 fw-bold">Usuarios del sistema</h4>
    <a href="{{ route('usuarios.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-person-plus me-1"></i>Nuevo usuario
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Usuario</th>
                        <th>Correo</th>
                        <th>Rol</th>
                        <th>Municipio</th>
                        <th>Acceso</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($usuarios as $u)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            @if($u->avatar)
                                <img src="{{ $u->avatar }}" class="rounded-circle" width="34" height="34" style="object-fit:cover">
                            @else
                                <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold"
                                     style="width:34px;height:34px;background:#b71c1c;font-size:0.75rem">
                                    {{ strtoupper(substr($u->name,0,2)) }}
                                </div>
                            @endif
                            <span class="fw-semibold">{{ $u->name }}</span>
                        </div>
                    </td>
                    <td><small>{{ $u->email }}</small></td>
                    <td>
                        @if($u->rol === 'supervisor')
                            <span class="badge bg-danger">Supervisor</span>
                        @else
                            <span class="badge bg-secondary">Operador</span>
                        @endif
                    </td>
                    <td>
                        @if($u->municipio)
                            <small>{{ $u->municipio->nombre }}</small><br>
                            <small class="text-muted">{{ $u->municipio->departamento->nombre }}</small>
                        @else
                            <small class="text-muted">— Todos —</small>
                        @endif
                    </td>
                    <td>
                        <small class="text-muted">
                            @if($u->google_id)
                                <i class="bi bi-google text-danger me-1"></i>Google
                            @else
                                <i class="bi bi-key me-1"></i>Contraseña
                            @endif
                        </small>
                    </td>
                    <td>
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('usuarios.edit', $u) }}" class="btn btn-outline-primary"><i class="bi bi-pencil"></i></a>
                            @if($u->id !== auth()->id())
                            <form method="POST" action="{{ route('usuarios.destroy', $u) }}" onsubmit="return confirm('¿Eliminar usuario?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted py-4">Sin usuarios registrados</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3">{{ $usuarios->links() }}</div>
    </div>
</div>
@endsection
