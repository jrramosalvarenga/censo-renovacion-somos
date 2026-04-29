<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 11px; }
    h1 { font-size: 16px; color: #b71c1c; }
    table { width: 100%; border-collapse: collapse; margin-top: 12px; }
    th { background: #b71c1c; color: #fff; padding: 6px 8px; text-align: left; font-size: 10px; }
    td { padding: 5px 8px; border-bottom: 1px solid #ddd; }
    tr:nth-child(even) td { background: #f7f9fb; }
    .footer { margin-top: 20px; font-size: 9px; color: #aaa; text-align: right; }
</style>
</head>
<body>
<h1>Movimiento Renovación Somos — Reporte General</h1>
<p><strong>Total miembros:</strong> {{ $totales['miembros'] }} |
   <strong>Militantes:</strong> {{ $totales['militantes'] }} |
   <strong>Simpatizantes:</strong> {{ $totales['simpatizantes'] }}</p>
<table>
    <thead>
        <tr><th>Departamento</th><th>Total</th><th>Militantes</th><th>Simpatizantes</th></tr>
    </thead>
    <tbody>
    @foreach($departamentos as $dep)
    @if($dep->miembros_count > 0)
    <tr>
        <td><strong>{{ $dep->nombre }}</strong></td>
        <td>{{ $dep->miembros_count }}</td>
        <td>{{ $dep->militantes_count }}</td>
        <td>{{ $dep->miembros_count - $dep->militantes_count }}</td>
    </tr>
    @endif
    @endforeach
    </tbody>
</table>
<div class="footer">Generado el {{ now()->format('d/m/Y H:i') }} — Renovación Somos</div>
</body>
</html>
