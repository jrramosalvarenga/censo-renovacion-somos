@extends('layouts.app')
@section('title', 'Ranking de Censadores')

@section('styles')
<style>
    .mi-perfil-card {
        border-radius: 20px;
        border: none;
        overflow: hidden;
    }
    .progress-glass {
        height: 8px;
        border-radius: 99px;
        background: rgba(255,255,255,0.2);
        margin: 0.5rem 0 0.3rem;
    }
    .progress-glass-fill {
        height: 100%;
        border-radius: 99px;
        background: rgba(255,255,255,0.9);
        transition: width 1.2s cubic-bezier(.4,0,.2,1);
    }
    .logro-item {
        width: 76px;
        text-align: center;
        cursor: default;
        transition: transform 0.15s;
    }
    .logro-item:hover { transform: translateY(-3px); }
    .logro-icon { font-size: 2rem; line-height: 1; }
    .logro-nombre { font-size: 0.65rem; margin-top: 0.2rem; line-height: 1.2; }
    .logro-sub    { font-size: 0.6rem; }
    .podio-wrap { display: flex; justify-content: center; align-items: flex-end; gap: 0; }
    .podio-col { flex: 1; text-align: center; max-width: 140px; }
    .podio-pedestal {
        border-radius: 8px 8px 0 0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.6rem;
        font-weight: 800;
        color: white;
        margin-top: 0.5rem;
    }
    .rank-me td:first-child { border-left: 3px solid #FFD700; }
    .rank-me { background: #fffbea !important; }
    .nivel-guia-card {
        border-radius: 10px;
        padding: 0.7rem 0.5rem;
        text-align: center;
        transition: transform 0.15s;
    }
    .nivel-guia-card:hover { transform: translateY(-2px); }
</style>
@endsection

@section('content')

{{-- Cabecera --}}
<div class="d-flex align-items-center mb-4 gap-3">
    <div>
        <h4 class="mb-0 fw-bold">🏆 Ranking de Censadores</h4>
        <small class="text-muted">Sube de nivel registrando más militantes y simpatizantes</small>
    </div>
</div>

{{-- ── Mi Perfil ─────────────────────────────────────────── --}}
@if($miPosicion)
@php
    $mp    = $miPosicion;
    $mniv  = $mp['nivel'];
    $mprog = $mp['progreso'];
@endphp
<div class="card mi-perfil-card shadow-sm mb-4"
     style="background: linear-gradient(135deg, {{ $mniv['color'] }}ee, {{ $mniv['color'] }}99);">
    <div class="card-body py-3">
        <div class="row align-items-center g-3">
            <div class="col-auto">
                @if(auth()->user()->avatar)
                    <img src="{{ auth()->user()->avatar }}" class="rounded-circle border border-3 border-white"
                         width="68" height="68" style="object-fit:cover">
                @else
                    <div class="rounded-circle border border-3 border-white d-flex align-items-center justify-content-center fw-bold text-white"
                         style="width:68px;height:68px;background:rgba(255,255,255,0.2);font-size:1.4rem;flex-shrink:0">
                        {{ strtoupper(substr(auth()->user()->name,0,2)) }}
                    </div>
                @endif
            </div>
            <div class="col text-white">
                <div class="fw-bold" style="font-size:1.15rem">{{ auth()->user()->name }}</div>
                <div style="font-size:0.78rem;opacity:0.88;margin-bottom:0.2rem">
                    {{ $mniv['icono'] }} {{ $mniv['nombre'] }}
                    &nbsp;·&nbsp;
                    @if($mp['puntos'] > 0)
                        Posición #{{ $mp['posicion'] }} en el ranking
                    @else
                        Aún sin registros
                    @endif
                </div>
                {{-- Desglose piramidal --}}
                <div class="d-flex gap-3 mb-1" style="font-size:0.78rem">
                    <div>
                        <span style="opacity:0.7">Propios:</span>
                        <strong>{{ $mp['total'] }}</strong>
                        <span style="opacity:0.6;font-size:0.7rem">({{ number_format($mp['puntos_directos']) }} pts)</span>
                    </div>
                    @if($mp['total_red'] > 0)
                    <div>
                        <span style="opacity:0.7">Red:</span>
                        <strong>{{ $mp['total_red'] }}</strong>
                        <span style="opacity:0.6;font-size:0.7rem">(+{{ number_format($mp['puntos_red_bonus']) }} pts)</span>
                    </div>
                    <div>
                        <span style="opacity:0.7">Equiv.:</span>
                        <strong>{{ $mp['total_equivalente'] }}</strong>
                    </div>
                    @endif
                </div>
                <div class="progress-glass">
                    <div class="progress-glass-fill" style="width:{{ $mprog['porcentaje'] }}%"></div>
                </div>
                @if($mprog['siguiente'])
                <div style="font-size:0.72rem;opacity:0.82">
                    Faltan <strong>{{ $mprog['faltan'] }}</strong> equiv. para
                    {{ $mprog['siguiente']['icono'] }} {{ $mprog['siguiente']['nombre'] }}
                </div>
                @else
                <div style="font-size:0.75rem;font-weight:700">🎉 ¡Nivel máximo alcanzado!</div>
                @endif
            </div>
            <div class="col-auto text-white text-end">
                <div style="font-size:2.2rem;font-weight:800;line-height:1">
                    {{ number_format($mp['puntos']) }}
                </div>
                <div style="font-size:0.7rem;opacity:0.8">puntos totales</div>
                @if($mp['total_red'] > 0)
                <div style="font-size:0.65rem;opacity:0.65;margin-top:0.1rem">
                    incl. red piramidal
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endif

{{-- ── Mis Logros ─────────────────────────────────────────── --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <h6 class="fw-bold mb-3">🎖️ Mis Logros</h6>
        <div class="d-flex flex-wrap gap-3">
            @foreach($logros as $l)
            <div class="logro-item" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $l['desc'] }}">
                <div class="logro-icon"
                     style="{{ $l['obtenido'] ? '' : 'filter:grayscale(1) opacity(0.25)' }}">
                    {{ $l['icono'] }}
                </div>
                <div class="logro-nombre fw-{{ $l['obtenido'] ? 'bold' : 'normal' }}"
                     style="color:{{ $l['obtenido'] ? '#222' : '#bbb' }}">
                    {{ $l['nombre'] }}
                </div>
                @if($l['obtenido'])
                    <div class="logro-sub fw-bold" style="color:#2E7D32">✓ Obtenido</div>
                @else
                    <div class="logro-sub" style="color:#bbb">+{{ $l['min'] }}</div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- ── Podio (top 3) ─────────────────────────────────────── --}}
@php $activos = collect($ranking)->filter(fn($r) => $r['puntos'] > 0)->values(); @endphp
@if($activos->count() >= 2)
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body pb-0">
        <h6 class="fw-bold mb-4 text-center">🥇 Podio</h6>
        <div class="podio-wrap">

            {{-- 2.° --}}
            @php $r2 = $activos[1]; @endphp
            <div class="podio-col">
                <div style="font-size:1.1rem">{{ $r2['nivel']['icono'] }}</div>
                @if($r2['user']->avatar)
                    <img src="{{ $r2['user']->avatar }}" class="rounded-circle mx-auto d-block"
                         width="52" height="52" style="object-fit:cover;margin-top:0.25rem">
                @else
                    <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto text-white fw-bold"
                         style="width:52px;height:52px;background:{{ $r2['nivel']['color'] }};font-size:1rem;margin-top:0.25rem">
                        {{ strtoupper(substr($r2['user']->name,0,2)) }}
                    </div>
                @endif
                <div class="fw-bold mt-1" style="font-size:0.78rem;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;max-width:120px;margin:0 auto">
                    {{ $r2['user']->name }}
                    @if($r2['user']->id === auth()->id())
                        <span class="badge bg-warning text-dark" style="font-size:0.55rem">Tú</span>
                    @endif
                </div>
                <div style="font-size:0.68rem;color:#888">{{ number_format($r2['puntos']) }} pts</div>
                <div class="podio-pedestal" style="height:80px;background:#C0C0C0">🥈</div>
            </div>

            {{-- 1.° --}}
            @php $r1 = $activos[0]; @endphp
            <div class="podio-col">
                <div style="font-size:1.5rem">{{ $r1['nivel']['icono'] }}</div>
                @if($r1['user']->avatar)
                    <img src="{{ $r1['user']->avatar }}" class="rounded-circle mx-auto d-block"
                         width="64" height="64"
                         style="object-fit:cover;margin-top:0.25rem;border:3px solid #FFD700">
                @else
                    <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto text-white fw-bold"
                         style="width:64px;height:64px;background:{{ $r1['nivel']['color'] }};font-size:1.2rem;margin-top:0.25rem;border:3px solid #FFD700">
                        {{ strtoupper(substr($r1['user']->name,0,2)) }}
                    </div>
                @endif
                <div class="fw-bold mt-1" style="font-size:0.85rem;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;max-width:130px;margin:0 auto">
                    {{ $r1['user']->name }}
                    @if($r1['user']->id === auth()->id())
                        <span class="badge bg-warning text-dark" style="font-size:0.55rem">Tú</span>
                    @endif
                </div>
                <div style="font-size:0.72rem;color:#888">{{ number_format($r1['puntos']) }} pts</div>
                <div class="podio-pedestal" style="height:110px;background:#FFD700">🥇</div>
            </div>

            {{-- 3.° --}}
            @if($activos->count() >= 3)
            @php $r3 = $activos[2]; @endphp
            <div class="podio-col">
                <div style="font-size:1.1rem">{{ $r3['nivel']['icono'] }}</div>
                @if($r3['user']->avatar)
                    <img src="{{ $r3['user']->avatar }}" class="rounded-circle mx-auto d-block"
                         width="52" height="52" style="object-fit:cover;margin-top:0.25rem">
                @else
                    <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto text-white fw-bold"
                         style="width:52px;height:52px;background:{{ $r3['nivel']['color'] }};font-size:1rem;margin-top:0.25rem">
                        {{ strtoupper(substr($r3['user']->name,0,2)) }}
                    </div>
                @endif
                <div class="fw-bold mt-1" style="font-size:0.78rem;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;max-width:120px;margin:0 auto">
                    {{ $r3['user']->name }}
                    @if($r3['user']->id === auth()->id())
                        <span class="badge bg-warning text-dark" style="font-size:0.55rem">Tú</span>
                    @endif
                </div>
                <div style="font-size:0.68rem;color:#888">{{ number_format($r3['puntos']) }} pts</div>
                <div class="podio-pedestal" style="height:60px;background:#CD7F32">🥉</div>
            </div>
            @endif

        </div>
    </div>
</div>
@endif

{{-- ── Tabla completa ─────────────────────────────────────── --}}
@if($activos->count() > 0)
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-dark text-white fw-semibold" style="border-radius:12px 12px 0 0">
        <i class="bi bi-list-ol me-2"></i>Clasificación completa
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" style="width:46px">#</th>
                        <th>Censador</th>
                        <th>Nivel</th>
                        <th class="text-center" title="Miembros registrados directamente por el usuario">Propios</th>
                        <th class="text-center" title="Miembros registrados por enroladores que tú creaste">Red 🔗</th>
                        <th class="text-center" title="Propios + 50% de red — determina el nivel">Equiv.</th>
                        <th class="text-end">Puntos</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($activos as $r)
                    @php $esYo = $r['user']->id === auth()->id(); @endphp
                    <tr class="{{ $esYo ? 'rank-me' : '' }}">
                        <td class="text-center fw-bold"
                            style="color:{{ $r['posicion']===1?'#FFD700':($r['posicion']===2?'#9E9E9E':($r['posicion']===3?'#CD7F32':'#ccc')) }}">
                            @if($r['posicion'] === 1) 🥇
                            @elseif($r['posicion'] === 2) 🥈
                            @elseif($r['posicion'] === 3) 🥉
                            @else #{{ $r['posicion'] }}
                            @endif
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                @if($r['user']->avatar)
                                    <img src="{{ $r['user']->avatar }}" class="rounded-circle flex-shrink-0"
                                         width="34" height="34" style="object-fit:cover">
                                @else
                                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold flex-shrink-0"
                                         style="width:34px;height:34px;background:{{ $r['nivel']['color'] }};font-size:0.75rem">
                                        {{ strtoupper(substr($r['user']->name,0,2)) }}
                                    </div>
                                @endif
                                <div>
                                    <div class="fw-semibold" style="font-size:0.88rem">
                                        {{ $r['user']->name }}
                                        @if($esYo)
                                            <span class="badge bg-warning text-dark ms-1" style="font-size:0.6rem">Tú</span>
                                        @endif
                                    </div>
                                    @if($r['user']->municipio)
                                    <div class="text-muted" style="font-size:0.7rem">
                                        {{ $r['user']->municipio->nombre }}
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>
                            <div>
                                <span class="badge" style="background:{{ $r['nivel']['color'] }};font-size:0.7rem">
                                    {{ $r['nivel']['icono'] }} {{ $r['nivel']['nombre'] }}
                                </span>
                            </div>
                            <div style="background:#eee;border-radius:99px;height:4px;margin-top:4px;max-width:80px">
                                <div style="background:{{ $r['nivel']['color'] }};width:{{ $r['progreso']['porcentaje'] }}%;height:100%;border-radius:99px"></div>
                            </div>
                        </td>
                        <td class="text-center fw-bold" style="color:{{ $r['nivel']['color'] }}">
                            {{ number_format($r['total']) }}
                        </td>
                        <td class="text-center">
                            @if($r['total_red'] > 0)
                                <span class="fw-semibold" style="color:#7B1FA2">
                                    +{{ number_format($r['total_red']) }}
                                </span>
                                <div style="font-size:0.62rem;color:#aaa">
                                    +{{ number_format($r['puntos_red_bonus']) }} pts
                                </div>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td class="text-center fw-bold text-dark">
                            {{ number_format($r['total_equivalente']) }}
                        </td>
                        <td class="text-end fw-bold" style="color:{{ $r['nivel']['color'] }}">
                            {{ number_format($r['puntos']) }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@else
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body text-center py-5 text-muted">
        <div style="font-size:3rem">🌱</div>
        <p class="mt-2">Aún no hay miembros registrados. ¡Sé el primero!</p>
    </div>
</div>
@endif

{{-- ── Sistema de puntos ───────────────────────────────────── --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <h6 class="fw-bold mb-3">💡 Cómo ganar puntos</h6>
        <div class="row g-2 mb-3">
            <div class="col-md-4">
                <div class="rounded p-3 text-center bg-light">
                    <div style="font-size:1.5rem">👤</div>
                    <div class="fw-bold">+10 pts</div>
                    <small class="text-muted">Por cada miembro registrado</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="rounded p-3 text-center bg-light">
                    <div style="font-size:1.5rem">⚡</div>
                    <div class="fw-bold">+5 pts extra</div>
                    <small class="text-muted">Si el miembro es militante</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="rounded p-3 text-center bg-light">
                    <div style="font-size:1.5rem">✅</div>
                    <div class="fw-bold">+2 pts extra</div>
                    <small class="text-muted">Si el miembro está activo</small>
                </div>
            </div>
        </div>
        <div class="alert alert-purple mb-0 d-flex gap-3 align-items-start"
             style="background:#f3e5f5;border-left:4px solid #9C27B0;border-radius:8px;padding:0.75rem 1rem">
            <div style="font-size:1.6rem;line-height:1">🔗</div>
            <div>
                <div class="fw-bold" style="color:#6A1B9A">Sistema piramidal</div>
                <div style="font-size:0.82rem;color:#555">
                    Cuando creas un usuario enrolador y ese usuario registra miembros,
                    <strong>tú recibes el 30 % de sus puntos</strong> como bonus de red.
                    Además, esos miembros cuentan como <strong>50 % en tu total equivalente</strong>
                    para subir de nivel.
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ── Escala de niveles ───────────────────────────────────── --}}
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <h6 class="fw-bold mb-3">📈 Escala de Niveles</h6>
        <div class="row g-2">
            @foreach(\App\Services\GamificacionService::NIVELES as $n)
            @php $esActual = isset($miPosicion) && $miPosicion['nivel']['nombre'] === $n['nombre']; @endphp
            <div class="col-md-3 col-6">
                <div class="nivel-guia-card"
                     style="background:{{ $n['color'] }}{{ $esActual ? 'ff' : '18' }};
                            border:{{ $esActual ? '2px solid '.$n['color'] : '1px solid '.$n['color'].'33' }}">
                    <div style="font-size:1.5rem">{{ $n['icono'] }}</div>
                    <div style="font-size:0.72rem;font-weight:700;color:{{ $esActual ? '#fff' : $n['color'] }}">
                        {{ $n['nombre'] }}
                    </div>
                    <div style="font-size:0.65rem;color:{{ $esActual ? 'rgba(255,255,255,0.8)' : '#999' }}">
                        @if($n['min'] === 0) Inicio
                        @else desde {{ $n['min'] }} miembros
                        @endif
                    </div>
                    @if($esActual)
                    <div style="font-size:0.58rem;background:rgba(255,255,255,0.2);border-radius:4px;padding:1px 5px;margin-top:3px;color:#fff;font-weight:600">
                        Tu nivel actual
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));
</script>
@endsection
