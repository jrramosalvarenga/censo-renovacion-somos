<?php

namespace App\Http\Controllers;

use App\Models\Departamento;
use App\Models\Localidad;
use App\Models\Miembro;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalMiembros = Miembro::count();
        $totalMilitantes = Miembro::where('tipo', 'militante')->count();
        $totalSimpatizantes = Miembro::where('tipo', 'simpatizante')->count();
        $totalLocalidades = Localidad::count();
        $miembrosRecientes = Miembro::with(['localidad.municipio.departamento', 'cargo'])
            ->latest()
            ->take(10)
            ->get();
        $porDepartamento = Departamento::withCount(['municipios as total_miembros' => function ($q) {
            $q->join('localidades', 'localidades.municipio_id', '=', 'municipios.id')
              ->join('miembros', 'miembros.localidad_id', '=', 'localidades.id')
              ->select(\DB::raw('count(miembros.id)'));
        }])->get();

        return view('dashboard', compact(
            'totalMiembros', 'totalMilitantes', 'totalSimpatizantes',
            'totalLocalidades', 'miembrosRecientes', 'porDepartamento'
        ));
    }
}
