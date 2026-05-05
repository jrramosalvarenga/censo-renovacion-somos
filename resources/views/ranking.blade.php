@extends('layouts.app')
@section('title', 'Ranking de Militantes')

@section('content')
<div class="d-flex align-items-center mb-4 gap-2">
    <h4 class="mb-0 fw-bold">🏆 Ranking de Censadores</h4>
    <small class="text-muted ms-2">Top 10 usuarios que más miembros han registrado</small>
</div>

@if(empty($ranking))
    <div class="card shadow-sm">
        <div class="card-body text-center py-5 text-muted">
            <div style="font-size:3rem">🌱</div>
            <p class="mt-2">Aún no hay miembros registrados. ¡Sé el primero!</p>
        </div>
    </div>
@else
<div class="row g-3">
    @foreach($ranking as $i => $r)
    @php $user = $r['user']; $nivel = $r['nivel']; $prog = $r['progreso']; @endphp
    <div class="col-12">
        <div class="card shadow-sm border-0 {{ $i === 0 ? 'border-warning' : '' }}"
             style="{{ $i === 0 ? 'border:2px solid gold!important' : '' }}">
            <div class="card-body py-3">
                <div class="d-flex align-items-center gap-3">

                    {{-- Posición --}}
                    <div style="font-size:1.5rem; min-width:40px; text-align:center; font-weight:800; color:{{ $i===0?'#FFD700':($i===1?'#C0C0C0':($i===2?'#CD7F32':'#aaa')) }}">
                        @if($i === 0) 🥇
                        @elseif($i === 1) 🥈
                        @elseif($i === 2) 🥉
                        @else #{{ $i + 1 }}
                        @endif
                    </div>

                    {{-- Avatar --}}
                    @if($user->avatar)
                        <img src="{{ $user->avatar }}" class="rounded-circle" width="46" height="46" style="object-fit:cover">
                    @else
                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold"
                             style="width:46px;height:46px;background:{{ $nivel['color'] }};font-size:1rem;flex-shrink:0">
                            {{ strtoupper(substr($user->name,0,2)) }}
                        </div>
                    @endif

                    {{-- Info --}}
                    <div class="flex-grow-1">
                        <div class="d-flex align-items-center gap-2">
                            <span class="fw-bold">{{ $user->name }}</span>
                            <span style="font-size:1rem">{{ $nivel['icono'] }}</span>
                            <span class="badge" style="background:{{ $nivel['color'] }};font-size:0.7rem">
                                {{ $nivel['nombre'] }}
                            </span>
                        </div>
                        {{-- Barra de progreso --}}
                        <div style="background:#eee; border-radius:99px; height:5px; margin:0.35rem 0; max-width:300px;">
                            <div style="background:{{ $nivel['color'] }}; width:{{ $prog['porcentaje'] }}%;
                                 height:100%; border-radius:99px;"></div>
                        </div>
                        <small class="text-muted">
                            {{ $r['total'] }} miembros registrados
                            @if($prog['siguiente'])
                                · faltan {{ $prog['faltan'] }} para {{ $prog['siguiente']['icono'] }}
                            @endif
                        </small>
                    </div>

                    {{-- Puntos --}}
                    <div class="text-end">
                        <div style="font-size:1.4rem; font-weight:800; color:{{ $nivel['color'] }}">
                            {{ number_format($r['puntos']) }}
                        </div>
                        <small class="text-muted">puntos</small>
                    </div>

                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif

<div class="mt-4 card shadow-sm border-0">
    <div class="card-body">
        <h6 class="fw-bold mb-3">📊 Sistema de puntos</h6>
        <div class="row g-2">
            <div class="col-md-4"><div class="bg-light rounded p-2 text-center"><strong>+10 pts</strong><br><small class="text-muted">Por cada miembro registrado</small></div></div>
            <div class="col-md-4"><div class="bg-light rounded p-2 text-center"><strong>+5 pts</strong><br><small class="text-muted">Si es militante</small></div></div>
            <div class="col-md-4"><div class="bg-light rounded p-2 text-center"><strong>+2 pts</strong><br><small class="text-muted">Si está activo</small></div></div>
        </div>
        <div class="row g-2 mt-1">
            @foreach(\App\Services\GamificacionService::NIVELES as $n)
            @if($n['min'] > 0)
            <div class="col-md-3 col-6">
                <div class="rounded p-2 text-center" style="background:{{ $n['color'] }}20; border:1px solid {{ $n['color'] }}40">
                    <div style="font-size:1.3rem">{{ $n['icono'] }}</div>
                    <div style="font-size:0.75rem; font-weight:600; color:{{ $n['color'] }}">{{ $n['nombre'] }}</div>
                    <div style="font-size:0.68rem; color:#888">desde {{ $n['min'] }} miembros</div>
                </div>
            </div>
            @endif
            @endforeach
        </div>
    </div>
</div>
@endsection
