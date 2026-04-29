<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #222; }
    h1 { font-size: 16px; color: #b71c1c; margin-bottom: 2px; }
    h2 { font-size: 13px; color: #555; margin-top: 0; }
    .stats { display: flex; gap: 20px; margin: 12px 0; }
    .stat-box { background: #f0f4f8; padding: 8px 16px; border-radius: 6px; text-align: center; }
    .stat-num { font-size: 22px; font-weight: bold; color: #b71c1c; }
    table { width: 100%; border-collapse: collapse; margin-top: 12px; }
    th { background: #b71c1c; color: #fff; padding: 6px 8px; text-align: left; font-size: 10px; }
    td { padding: 5px 8px; border-bottom: 1px solid #e0e0e0; }
    tr:nth-child(even) td { background: #f7f9fb; }
    .badge-m { background: #b71c1c; color: #fff; padding: 2px 6px; border-radius: 3px; font-size: 9px; }
    .badge-s { background: #888; color: #fff; padding: 2px 6px; border-radius: 3px; font-size: 9px; }
    .footer { margin-top: 20px; font-size: 9px; color: #aaa; text-align: right; }
</style>
</head>
<body>
<h1>Movimiento Renovación Somos — Honduras</h1>
<h2>Reporte de Militancia: {{ $localidad->nombre }} ({{ ucfirst($localidad->tipo) }})<br>
<small>{{ $localidad->municipio->nombre }}, {{ $localidad->municipio->departamento->nombre }}</small></h2>

<table width="100%">
    <tr>
        <td style="background:#f0f4f8;padding:8px 16px;border-radius:4px;text-align:center">
            <strong style="font-size:20px;color:#b71c1c">{{ $stats['total'] }}</strong><br><small>Total</small>
        </td>
        <td style="background:#f0f4f8;padding:8px 16px;border-radius:4px;text-align:center">
            <strong style="font-size:20px;color:#b71c1c">{{ $stats['militantes'] }}</strong><br><small>Militantes</small>
        </td>
        <td style="background:#f0f4f8;padding:8px 16px;border-radius:4px;text-align:center">
            <strong style="font-size:20px;color:#666">{{ $stats['simpatizantes'] }}</strong><br><small>Simpatizantes</small>
        </td>
        <td style="background:#f0f4f8;padding:8px 16px;border-radius:4px;text-align:center">
            <strong style="font-size:20px;color:#2e7d32">{{ $stats['activos'] }}</strong><br><small>Activos</small>
        </td>
    </tr>
</table>

<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Nombre completo</th>
            <th>Identidad</th>
            <th>Teléfono</th>
            <th>Tipo</th>
            <th>Cargo</th>
            <th>Estado</th>
        </tr>
    </thead>
    <tbody>
    @foreach($miembros as $i => $m)
    <tr>
        <td>{{ $i+1 }}</td>
        <td><strong>{{ $m->nombre_completo }}</strong></td>
        <td>{{ $m->identidad ?? '—' }}</td>
        <td>{{ $m->telefono ?? '—' }}</td>
        <td><span class="{{ $m->tipo==='militante'?'badge-m':'badge-s' }}">{{ ucfirst($m->tipo) }}</span></td>
        <td>{{ $m->cargo?->nombre ?? '—' }}</td>
        <td>{{ ucfirst($m->estado) }}</td>
    </tr>
    @endforeach
    </tbody>
</table>

<div class="footer">Generado el {{ now()->format('d/m/Y H:i') }} — Renovación Somos</div>
</body>
</html>
