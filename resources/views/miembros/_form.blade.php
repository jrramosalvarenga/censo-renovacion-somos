<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label fw-semibold">Nombres <span class="text-danger">*</span></label>
        <input type="text" name="nombres" class="form-control @error('nombres') is-invalid @enderror"
               value="{{ old('nombres', $miembro->nombres ?? '') }}" required>
        @error('nombres')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold">Apellidos <span class="text-danger">*</span></label>
        <input type="text" name="apellidos" class="form-control @error('apellidos') is-invalid @enderror"
               value="{{ old('apellidos', $miembro->apellidos ?? '') }}" required>
        @error('apellidos')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">Número de identidad (DNI)</label>
        <input type="text" name="identidad" class="form-control @error('identidad') is-invalid @enderror"
               value="{{ old('identidad', $miembro->identidad ?? '') }}" placeholder="0000-0000-00000">
        @error('identidad')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">Fecha de nacimiento</label>
        <input type="date" name="fecha_nacimiento" class="form-control"
               value="{{ old('fecha_nacimiento', isset($miembro) ? $miembro->fecha_nacimiento?->format('Y-m-d') : '') }}">
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">Sexo</label>
        <select name="sexo" class="form-select">
            <option value="">Sin especificar</option>
            <option value="M" @selected(old('sexo', $miembro->sexo ?? '') === 'M')>Masculino</option>
            <option value="F" @selected(old('sexo', $miembro->sexo ?? '') === 'F')>Femenino</option>
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">Teléfono</label>
        <input type="text" name="telefono" class="form-control"
               value="{{ old('telefono', $miembro->telefono ?? '') }}" placeholder="9999-9999">
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">Correo electrónico</label>
        <input type="email" name="email" class="form-control"
               value="{{ old('email', $miembro->email ?? '') }}">
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">Tipo <span class="text-danger">*</span></label>
        <select name="tipo" class="form-select @error('tipo') is-invalid @enderror" required>
            <option value="militante" @selected(old('tipo', $miembro->tipo ?? 'militante') === 'militante')>Militante</option>
            <option value="simpatizante" @selected(old('tipo', $miembro->tipo ?? '') === 'simpatizante')>Simpatizante</option>
        </select>
        @error('tipo')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">Estado</label>
        <select name="estado" class="form-select">
            <option value="activo" @selected(old('estado', $miembro->estado ?? 'activo') === 'activo')>Activo</option>
            <option value="inactivo" @selected(old('estado', $miembro->estado ?? '') === 'inactivo')>Inactivo</option>
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">Cargo</label>
        <select name="cargo_id" class="form-select">
            <option value="">Sin cargo</option>
            @foreach($cargos as $cargo)
                <option value="{{ $cargo->id }}" @selected(old('cargo_id', $miembro->cargo_id ?? '') == $cargo->id)>
                    {{ $cargo->nombre }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-12"><hr class="my-1"><p class="text-muted small mb-2">Ubicación territorial</p></div>

    <div class="col-md-4">
        <label class="form-label fw-semibold">Departamento <span class="text-danger">*</span></label>
        <select name="departamento_id" id="dep_select" class="form-select" required>
            <option value="">Seleccione...</option>
            @foreach($departamentos as $dep)
                <option value="{{ $dep->id }}"
                    @selected(old('departamento_id', isset($miembro) ? $miembro->localidad->municipio->departamento_id : '') == $dep->id)>
                    {{ $dep->nombre }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">Municipio <span class="text-danger">*</span></label>
        <select name="municipio_id" id="mun_select" class="form-select" required>
            <option value="">Seleccione departamento...</option>
            @foreach($municipios ?? [] as $mun)
                <option value="{{ $mun->id }}" @selected(old('municipio_id', isset($miembro) ? $miembro->localidad->municipio_id : '') == $mun->id)>
                    {{ $mun->nombre }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">Aldea / Barrio <span class="text-danger">*</span></label>
        <select name="localidad_id" id="loc_select" class="form-select @error('localidad_id') is-invalid @enderror" required>
            <option value="">Seleccione municipio...</option>
            @foreach($localidades ?? [] as $loc)
                <option value="{{ $loc->id }}" @selected(old('localidad_id', $miembro->localidad_id ?? '') == $loc->id)>
                    {{ $loc->nombre }} ({{ $loc->tipo }})
                </option>
            @endforeach
        </select>
        @error('localidad_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12">
        <label class="form-label fw-semibold">Dirección</label>
        <input type="text" name="direccion" class="form-control" value="{{ old('direccion', $miembro->direccion ?? '') }}">
    </div>

    <div class="col-md-6">
        <label class="form-label fw-semibold">Foto</label>
        <input type="file" name="foto" class="form-control" accept="image/*">
        @if(isset($miembro) && $miembro->foto)
            <div class="mt-2">
                <img src="{{ asset('storage/'.$miembro->foto) }}" class="rounded" height="60" style="object-fit:cover">
                <small class="text-muted ms-2">Foto actual</small>
            </div>
        @endif
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold">Observaciones</label>
        <textarea name="observaciones" class="form-control" rows="2">{{ old('observaciones', $miembro->observaciones ?? '') }}</textarea>
    </div>
</div>
