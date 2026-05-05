<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;

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

    const LOGROS = [
        ['id' => 'primer_paso',    'nombre' => 'Primer Paso',          'icono' => '🌱', 'desc' => 'Registrar tu primer miembro',  'min' => 1],
        ['id' => 'en_marcha',      'nombre' => 'En Marcha',            'icono' => '🚀', 'desc' => 'Registrar 5 miembros',          'min' => 5],
        ['id' => 'promotor',       'nombre' => 'Promotor',             'icono' => '⭐', 'desc' => 'Llegar a 10 miembros',          'min' => 10],
        ['id' => 'organizador',    'nombre' => 'Organizador',          'icono' => '🔥', 'desc' => 'Llegar a 25 miembros',          'min' => 25],
        ['id' => 'movilizador',    'nombre' => 'Movilizador',          'icono' => '💪', 'desc' => 'Llegar a 50 miembros',          'min' => 50],
        ['id' => 'lider',          'nombre' => 'Líder Territorial',    'icono' => '🏆', 'desc' => 'Llegar a 100 miembros',         'min' => 100],
        ['id' => 'lider_regional', 'nombre' => 'Líder Regional',       'icono' => '🦅', 'desc' => 'Llegar a 200 miembros',         'min' => 200],
        ['id' => 'pilar',          'nombre' => 'Pilar del Movimiento', 'icono' => '👑', 'desc' => 'Llegar a 500 miembros',         'min' => 500],
    ];

    // ── Puntos de un usuario para el widget (SQL, sin N+1) ─────────────
    public static function puntosDirectosSQL(User $user): int
    {
        return (int) DB::table('miembros')
            ->where('registered_by', $user->id)
            ->selectRaw("COALESCE(SUM((CASE WHEN tipo='militante' THEN 15 WHEN tipo='simpatizante' THEN 10 ELSE 0 END) + (CASE WHEN estado='activo' THEN 2 ELSE 0 END)), 0) as pts")
            ->value('pts');
    }

    // ── Miembros directos ───────────────────────────────────────────────
    public static function totalMiembros(User $user): int
    {
        return $user->miembrosRegistrados()->count();
    }

    // ── Miembros de la red (nivel 2): enrolados por usuarios que yo creé
    public static function totalRed(User $user): int
    {
        try {
            return DB::table('miembros')
                ->join('users', 'users.id', '=', 'miembros.registered_by')
                ->where('users.created_by', $user->id)
                ->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    // ── Total equivalente para determinar nivel (directo + 50 % red) ───
    public static function totalEquivalente(User $user): int
    {
        return self::totalMiembros($user) + (int) round(self::totalRed($user) * 0.5);
    }

    // ── Nivel según total equivalente ───────────────────────────────────
    public static function nivel(int $totalEquivalente): array
    {
        foreach (self::NIVELES as $nivel) {
            if ($totalEquivalente >= $nivel['min']) {
                return $nivel;
            }
        }
        return self::NIVELES[array_key_last(self::NIVELES)];
    }

    // ── Progreso hacia el siguiente nivel ──────────────────────────────
    public static function progreso(int $totalEquivalente): array
    {
        $niveles   = array_reverse(self::NIVELES);
        $actual    = null;
        $siguiente = null;

        foreach ($niveles as $i => $n) {
            if ($totalEquivalente >= $n['min']) {
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
            $avance     = $totalEquivalente - $actual['min'];
            $porcentaje = $rango > 0 ? min(100, (int) ($avance / $rango * 100)) : 100;
            $faltan     = max(0, $siguiente['min'] - $totalEquivalente);
        }

        return [
            'actual'     => $actual,
            'siguiente'  => $siguiente,
            'porcentaje' => $porcentaje,
            'faltan'     => $faltan,
            'total'      => $totalEquivalente,
        ];
    }

    // ── Logros (acepta total precomputado para evitar queries extra) ────
    public static function logros(User $user, ?int $totalEquivalente = null): array
    {
        $total = $totalEquivalente ?? self::totalEquivalente($user);
        return array_map(fn($l) => array_merge($l, ['obtenido' => $total >= $l['min']]), self::LOGROS);
    }

    // ── Ranking piramidal (2 queries totales) ───────────────────────────
    public static function rankingCompleto(): array
    {
        // Query 1: todos los usuarios con sus stats directas
        $users = User::withCount(['miembrosRegistrados as total_directos'])
            ->addSelect([
                'puntos_directos' => DB::table('miembros')
                    ->selectRaw("COALESCE(SUM((CASE WHEN tipo='militante' THEN 15 WHEN tipo='simpatizante' THEN 10 ELSE 0 END) + (CASE WHEN estado='activo' THEN 2 ELSE 0 END)), 0)")
                    ->whereColumn('miembros.registered_by', 'users.id'),
            ])
            ->get()
            ->keyBy('id');

        // Query 2: aggregate de red — miembros registrados por usuarios que yo creé
        try {
            $redStats = DB::table('miembros')
                ->join('users as u2', 'u2.id', '=', 'miembros.registered_by')
                ->whereNotNull('u2.created_by')
                ->groupBy('u2.created_by')
                ->selectRaw("
                    u2.created_by,
                    COUNT(miembros.id) as total_red,
                    COALESCE(SUM(
                        (CASE WHEN miembros.tipo='militante' THEN 15 WHEN miembros.tipo='simpatizante' THEN 10 ELSE 0 END) +
                        (CASE WHEN miembros.estado='activo' THEN 2 ELSE 0 END)
                    ), 0) as puntos_red
                ")
                ->get()
                ->keyBy('created_by');
        } catch (\Exception $e) {
            $redStats = collect();
        }

        return $users
            ->map(function ($u) use ($redStats) {
                $red        = $redStats->get($u->id);
                $directos   = (int) ($u->total_directos ?? 0);
                $redCount   = $red ? (int) $red->total_red   : 0;
                $pDirectos  = (int) ($u->puntos_directos ?? 0);
                $pRed       = $red ? (int) $red->puntos_red  : 0;

                // El 30 % de los puntos de la red suma al total
                $pRedBonus  = (int) round($pRed * 0.3);
                $pTotal     = $pDirectos + $pRedBonus;

                // Equivalente: propios + 50 % red (para determinar nivel)
                $equivalente = $directos + (int) round($redCount * 0.5);

                return [
                    'user'             => $u,
                    'total'            => $directos,
                    'total_red'        => $redCount,
                    'total_equivalente'=> $equivalente,
                    'puntos'           => $pTotal,
                    'puntos_directos'  => $pDirectos,
                    'puntos_red_bonus' => $pRedBonus,
                    'nivel'            => self::nivel($equivalente),
                    'progreso'         => self::progreso($equivalente),
                ];
            })
            ->sortByDesc('puntos')
            ->values()
            ->map(fn($r, $i) => array_merge($r, ['posicion' => $i + 1]))
            ->toArray();
    }

    // Mantenido por compatibilidad (usado en el widget con calcularPuntos)
    public static function calcularPuntos(User $user): int
    {
        return self::puntosDirectosSQL($user);
    }
}
