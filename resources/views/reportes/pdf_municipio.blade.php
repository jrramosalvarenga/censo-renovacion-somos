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
<h1>Municipio: {{ $municipio->nombre }} — {{ $municipio->departamento->nombre }}</h1>
<p><strong>Total:</strong> {{ $totales['miembros'] }} | <strong>Militantes:</strong> {{ $totales['militantes'] }}</p>
<table>
    <thead>
        <tr><th>Localidad</th><th>Tipo</th><th>Total</th><th>Militantes</th><th>Simpatizantes</th></tr>
    </thead>
    <tbody>
    @foreach($localidades as $loc)
    <tr>
        <td>{{ $loc->nombre }}</td>
        <td>{{ ucfirst($loc->tipo) }}</td>
        <td>{{ $loc->miembros_count }}</td>
        <td>{{ $loc->militantes_count }}</td>
        <td>{{ $loc->simpatizantes_count }}</td>
    </tr>
    @endforeach
    </tbody>
</table>
<div class="footer">Generado el {{ now()->format('d/m/Y H:i') }} — Renovación Somos</div>
</body>
</html>
