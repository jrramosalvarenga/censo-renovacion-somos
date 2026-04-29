@extends('layouts.app')
@section('title', 'Localidades')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0 fw-bold">Localidades</h4>
    <a href="{{ route('localidades.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg me-1"></i>Nueva localidad
    </a>
</div>

<div class="card shadow-sm mb-3">
    <div class="card-body py-2">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <select name="departamento_id" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">Todos los departamentos</option>
                    @foreach($departamentos as $dep)
                        <option value="{{ $dep->id }}" @selected(request('departamento_id')==$dep->id)>{{ $dep->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <select name="municipio_id" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">Todos los municipios</option>
                    @foreach($municipios as $mun)
                        <option value="{{ $mun->id }}" @selected(request('municipio_id')==$mun->id)>{{ $mun->nombre }}</option>
                    @endforeach
                </select>
            </div>
            @if(request()->hasAny(['departamento_id','municipio_id']))
            <div class="col-auto">
                <a href="{{ route('localidades.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-x-lg"></i></a>
            </div>
            @endif
        </form>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        @if($localidades->isEmpty())
            <div class="text-center py-5 text-muted"><i class="bi bi-inbox fs-1 d-block mb-2"></i>Sin localidades registradas.</div>
        @else
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Localidad</th>
                        <th>Tipo</th>
                        <th>Municipio</th>
                        <th>Departamento</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($localidades as $loc)
                <tr>
                    <td class="fw-semibold">{{ $loc->nombre }}</td>
                    <td><span class="badge bg-light text-dark border">{{ ucfirst($loc->tipo) }}</span></td>
                    <td>{{ $loc->municipio->nombre }}</td>
                    <td>{{ $loc->municipio->departamento->nombre }}</td>
                    <td>
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('localidades.edit', $loc) }}" class="btn btn-outline-primary"><i class="bi bi-pencil"></i></a>
                            <form method="POST" action="{{ route('localidades.destroy', $loc) }}" onsubmit="return confirm('¿Eliminar esta localidad?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-3">{{ $localidades->links() }}</div>
        @endif
    </div>
</div>
@endsection
