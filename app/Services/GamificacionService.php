<?php

namespace App\Services;

use App\Models\User;

class GamificacionService
{
    const NIVELES = [
        ['min' => 500, 'nombre' => 'Pilar del Movimiento', 'icono' => '👑', 'color' => '#FFD700', 'badge' => 'warning'],
        ['min' => 200, 'nombre' => 'Líder Regional',       'icono' => '🦅', 'color' => '#9C27B0', 'badge' => 'purple'],
        ['min' => 100, 'nombre' => 'Líder Territorial',    'icono' => '🏆', 'color' => '#b71c1c', 'badge' => 'danger'],
        ['min' => 50,  'nombre' => 'Movilizador',          'icono' => '💪', 'color' => '#1565C0', 'badge' => 'primary'],
        ['min' => 25,  'nombre' => 'Organizador',          'icono' => '🔥', 'color' => '#E65100', 'badge' => 'orange'],
        ['min' => 10,  'nombre' => 'Promotor',             'icono' => '⭐', 'color' => '#2E7D32', 'badge' => 'success'],
        ['min' => 1,   'nombre' => 'Activista',            'icono' => '🌱', 'color' => '#546E7A', 'badge' => 'secondary'],
        ['min' => 0,   'nombre' => 'Nuevo miembro',        'icono' => '🔰', 'color' => '#78909C', 'badge' => 'light'],
    ];

    public static function calcularPuntos(User $user): int
    {
        $miembros = $user->miembrosRegistrados()->get();
        $puntos   = 0;
        foreach ($miembros as $m) {
            $puntos += 10;
            if ($m->tipo === 'militante') $puntos += 5;
            if ($m->estado === 'activo')  $puntos += 2;
        }
        return $puntos;
    }

    public static function totalMiembros(User $user): int
    {
        return $user->miembrosRegistrados()->count();
    }

    public static function nivel(int $totalMiembros): array
    {
        foreach (self::NIVELES as $nivel) {
            if ($totalMiembros >= $nivel['min']) {
                return $nivel;
            }
        }
        return self::NIVELES[array_key_last(self::NIVELES)];
    }

    public static function progreso(int $totalMiembros): array
    {
        $niveles  = array_reverse(self::NIVELES);
        $actual   = null;
        $siguiente = null;

        foreach ($niveles as $i => $n) {
            if ($totalMiembros >= $n['min']) {
                $actual    = $n;
                $siguiente = $niveles[$i + 1] ?? null;
                break;
            }
        }

        if (!$actual) {
            $actual    = $niveles[0];
            $siguiente = $niveles[1] ?? null;
        }

        $porcentaje = 100;
        $faltan     = 0;

        if ($siguiente) {
            $rango      = $siguiente['min'] - $actual['min'];
            $avance     = $totalMiembros - $actual['min'];
            $porcentaje = $rango > 0 ? min(100, (int) ($avance / $rango * 100)) : 100;
            $faltan     = max(0, $siguiente['min'] - $totalMiembros);
        }

        return [
            'actual'     => $actual,
            'siguiente'  => $siguiente,
            'porcentaje' => $porcentaje,
            'faltan'     => $faltan,
            'total'      => $totalMiembros,
        ];
    }

    public static function ranking(): array
    {
        return User::withCount(['miembrosRegistrados as total_registrados'])
            ->having('total_registrados', '>', 0)
            ->orderByDesc('total_registrados')
            ->limit(10)
            ->get()
            ->map(function ($u) {
                $total  = $u->total_registrados;
                $puntos = $u->miembrosRegistrados()
                    ->selectRaw('SUM(CASE WHEN tipo=\'militante\' THEN 15 WHEN tipo=\'simpatizante\' THEN 10 END) + SUM(CASE WHEN estado=\'activo\' THEN 2 ELSE 0 END) as pts')
                    ->value('pts') ?? 0;
                return [
                    'user'    => $u,
                    'total'   => $total,
                    'puntos'  => $puntos,
                    'nivel'   => self::nivel($total),
                    'progreso'=> self::progreso($total),
                ];
            })->toArray();
    }
}
