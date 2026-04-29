<div class="mb-3">
    <label class="form-label fw-semibold">Nombre completo <span class="text-danger">*</span></label>
    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
           value="{{ old('name', $usuario->name ?? '') }}" required>
    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
<div class="mb-3">
    <label class="form-label fw-semibold">Correo electrónico <span class="text-danger">*</span></label>
    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
           value="{{ old('email', $usuario->email ?? '') }}" required>
    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
<div class="mb-3">
    <label class="form-label fw-semibold">
        Contraseña {{ isset($usuario) ? '(dejar vacío para no cambiar)' : '' }}
        @if(!isset($usuario))<span class="text-danger">*</span>@endif
    </label>
    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
           {{ !isset($usuario) ? 'required' : '' }} minlength="8" autocomplete="new-password">
    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
<div class="mb-3">
    <label class="form-label fw-semibold">Confirmar contraseña</label>
    <input type="password" name="password_confirmation" class="form-control" minlength="8" autocomplete="new-password">
</div>
<div class="mb-3">
    <label class="form-label fw-semibold">Rol <span class="text-danger">*</span></label>
    <select name="rol" id="rol_select" class="form-select @error('rol') is-invalid @enderror" required>
        <option value="operador" @selected(old('rol', $usuario->rol ?? 'operador') === 'operador')>
            Operador — Solo su municipio
        </option>
        <option value="supervisor" @selected(old('rol', $usuario->rol ?? '') === 'supervisor')>
            Supervisor — Acceso completo + reportes
        </option>
    </select>
    @error('rol')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div id="municipio_section">
    <div class="alert alert-info py-2 small mb-3">
        <i class="bi bi-info-circle me-1"></i>
        El operador solo podrá registrar miembros en el municipio asignado.
    </div>
    <div class="mb-3">
        <label class="form-label fw-semibold">Departamento</label>
        <select name="departamento_id" id="dep_select" class="form-select">
            <option value="">Seleccione...</option>
            @foreach($departamentos as $dep)
                <option value="{{ $dep->id }}"
                    @selected(old('departamento_id', isset($usuario) && $usuario->municipio ? $usuario->municipio->departamento_id : '') == $dep->id)>
                    {{ $dep->nombre }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label fw-semibold">Municipio <span class="text-danger">*</span></label>
        <select name="municipio_id" id="mun_select" class="form-select @error('municipio_id') is-invalid @enderror">
            <option value="">Seleccione departamento primero...</option>
            @foreach($municipios ?? [] as $mun)
                <option value="{{ $mun->id }}" @selected(old('municipio_id', $usuario->municipio_id ?? '') == $mun->id)>
                    {{ $mun->nombre }}
                </option>
            @endforeach
        </select>
        @error('municipio_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>
