@extends('layouts.app')
@section('title', 'Militancia')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0 fw-bold">Militancia</h4>
    <a href="{{ route('miembros.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-person-plus me-1"></i>Nuevo miembro
    </a>
</div>

<div class="card shadow-sm mb-3">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <input type="text" name="buscar" class="form-control form-control-sm" placeholder="Buscar nombre, DPI, teléfono..." value="{{ request('buscar') }}">
            </div>
            <div class="col-md-2">
                <select name="tipo" class="form-select form-select-sm">
                    <option value="">Todos los tipos</option>
                    <option value="militante" @selected(request('tipo')=='militante')>Militante</option>
                    <option value="simpatizante" @selected(request('tipo')=='simpatizante')>Simpatizante</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="estado" class="form-select form-select-sm">
                    <option value="">Todos los estados</option>
                    <option value="activo" @selected(request('estado')=='activo')>Activo</option>
                    <option value="inactivo" @selected(request('estado')=='inactivo')>Inactivo</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="departamento_id" id="filtro_dep" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">Departamento</option>
                    @foreach($departamentos as $dep)
                        <option value="{{ $dep->id }}" @selected(request('departamento_id')==$dep->id)>{{ $dep->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="municipio_id" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">Municipio</option>
                    @foreach($municipios as $mun)
                        <option value="{{ $mun->id }}" @selected(request('municipio_id')==$mun->id)>{{ $mun->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-1">
                <button class="btn btn-primary btn-sm w-100" type="submit"><i class="bi bi-search"></i></button>
            </div>
            @if(request()->hasAny(['buscar','tipo','estado','departamento_id','municipio_id','localidad_id','cargo_id']))
            <div class="col-auto">
                <a href="{{ route('miembros.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-x-lg"></i></a>
            </div>
            @endif
        </form>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        @if($miembros->isEmpty())
            <div class="text-center py-5 text-muted">
                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                No se encontraron miembros.
            </div>
        @else
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Identidad</th>
                        <th>Tipo</th>
                        <th>Cargo</th>
                        <th>Localidad</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($miembros as $m)
                <tr>
                    <td class="text-muted small">{{ $m->id }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            @if($m->foto)
                                <img src="{{ asset('storage/'.$m->foto) }}" class="rounded-circle" width="32" height="32" style="object-fit:cover">
                            @else
                                <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white" style="width:32px;height:32px;font-size:0.75rem">
                                    {{ strtoupper(substr($m->nombres,0,1).substr($m->apellidos,0,1)) }}
                                </div>
                            @endif
                            <div>
                                <div class="fw-semibold">{{ $m->nombre_completo }}</div>
                                @if($m->telefono)<small class="text-muted">{{ $m->telefono }}</small>@endif
                            </div>
                        </div>
                    </td>
                    <td><small>{{ $m->identidad ?? '—' }}</small></td>
                    <td>
                        <span class="badge {{ $m->tipo === 'militante' ? 'badge-militante' : 'badge-simpatizante' }} text-white">
                            {{ ucfirst($m->tipo) }}
                        </span>
                    </td>
                    <td><small>{{ $m->cargo?->nombre ?? '—' }}</small></td>
                    <td>
                        <small>{{ $m->localidad->nombre }}</small><br>
                        <small class="text-muted">{{ $m->localidad->municipio->nombre }}, {{ $m->localidad->municipio->departamento->nombre }}</small>
                    </td>
                    <td>
                        <span class="badge {{ $m->estado === 'activo' ? 'bg-success' : 'bg-secondary' }}">
                            {{ ucfirst($m->estado) }}
                        </span>
                    </td>
                    <td>
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('miembros.show', $m) }}" class="btn btn-outline-secondary" title="Ver"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('miembros.edit', $m) }}" class="btn btn-outline-primary" title="Editar"><i class="bi bi-pencil"></i></a>
                            <form method="POST" action="{{ route('miembros.destroy', $m) }}" onsubmit="return confirm('¿Eliminar este miembro?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-outline-danger" title="Eliminar"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-3">
            {{ $miembros->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
