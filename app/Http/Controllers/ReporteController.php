<?php

namespace App\Http\Controllers;

use App\Models\Departamento;
use App\Models\Localidad;
use App\Models\Miembro;
use App\Models\Municipio;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ReporteController extends Controller
{
    public function index()
    {
        $departamentos = Departamento::orderBy('nombre')->get();
        return view('reportes.index', compact('departamentos'));
    }

    public function porLocalidad(Request $request)
    {
        $request->validate([
            'localidad_id' => 'required|exists:localidades,id',
        ]);

        $localidad = Localidad::with('municipio.departamento')->findOrFail($request->localidad_id);
        $miembros = Miembro::with('cargo')
            ->where('localidad_id', $localidad->id)
            ->orderBy('apellidos')->orderBy('nombres')
            ->get();

        $stats = [
            'total'         => $miembros->count(),
            'militantes'    => $miembros->where('tipo', 'militante')->count(),
            'simpatizantes' => $miembros->where('tipo', 'simpatizante')->count(),
            'activos'       => $miembros->where('estado', 'activo')->count(),
        ];

        if ($request->filled('formato') && $request->formato === 'pdf') {
            $pdf = Pdf::loadView('reportes.pdf_localidad', compact('localidad', 'miembros', 'stats'));
            return $pdf->download("reporte_{$localidad->nombre}.pdf");
        }

        return view('reportes.localidad', compact('localidad', 'miembros', 'stats'));
    }

    public function porMunicipio(Request $request)
    {
        $request->validate([
            'municipio_id' => 'required|exists:municipios,id',
        ]);

        $municipio = Municipio::with('departamento')->findOrFail($request->municipio_id);
        $localidades = Localidad::withCount([
            'miembros',
            'miembros as militantes_count' => fn($q) => $q->where('tipo', 'militante'),
            'miembros as simpatizantes_count' => fn($q) => $q->where('tipo', 'simpatizante'),
        ])->where('municipio_id', $municipio->id)->orderBy('nombre')->get();

        $totales = [
            'miembros'      => $localidades->sum('miembros_count'),
            'militantes'    => $localidades->sum('militantes_count'),
            'simpatizantes' => $localidades->sum('simpatizantes_count'),
        ];

        if ($request->filled('formato') && $request->formato === 'pdf') {
            $pdf = Pdf::loadView('reportes.pdf_municipio', compact('municipio', 'localidades', 'totales'));
            return $pdf->download("reporte_{$municipio->nombre}.pdf");
        }

        return view('reportes.municipio', compact('municipio', 'localidades', 'totales'));
    }

    public function porDepartamento(Request $request)
    {
        $request->validate([
            'departamento_id' => 'required|exists:departamentos,id',
        ]);

        $departamento = Departamento::findOrFail($request->departamento_id);
        $municipios = Municipio::withCount([
            'localidades as miembros_count' => function ($q) {
                $q->join('miembros', 'miembros.localidad_id', '=', 'localidades.id')
                  ->select(\DB::raw('count(miembros.id)'));
            },
            'localidades as militantes_count' => function ($q) {
                $q->join('miembros', 'miembros.localidad_id', '=', 'localidades.id')
                  ->where('miembros.tipo', 'militante')
                  ->select(\DB::raw('count(miembros.id)'));
            },
        ])->where('departamento_id', $departamento->id)->orderBy('nombre')->get();

        $totales = [
            'miembros'   => $municipios->sum('miembros_count'),
            'militantes' => $municipios->sum('militantes_count'),
        ];

        if ($request->filled('formato') && $request->formato === 'pdf') {
            $pdf = Pdf::loadView('reportes.pdf_departamento', compact('departamento', 'municipios', 'totales'));
            return $pdf->download("reporte_{$departamento->nombre}.pdf");
        }

        return view('reportes.departamento', compact('departamento', 'municipios', 'totales'));
    }

    public function general(Request $request)
    {
        $departamentos = Departamento::withCount([
            'municipios as miembros_count' => function ($q) {
                $q->join('localidades', 'localidades.municipio_id', '=', 'municipios.id')
                  ->join('miembros', 'miembros.localidad_id', '=', 'localidades.id')
                  ->select(\DB::raw('count(miembros.id)'));
            },
            'municipios as militantes_count' => function ($q) {
                $q->join('localidades', 'localidades.municipio_id', '=', 'municipios.id')
                  ->join('miembros', 'miembros.localidad_id', '=', 'localidades.id')
                  ->where('miembros.tipo', 'militante')
                  ->select(\DB::raw('count(miembros.id)'));
            },
        ])->orderBy('nombre')->get();

        $totales = [
            'miembros'   => Miembro::count(),
            'militantes' => Miembro::where('tipo', 'militante')->count(),
            'simpatizantes' => Miembro::where('tipo', 'simpatizante')->count(),
        ];

        if ($request->filled('formato') && $request->formato === 'pdf') {
            $pdf = Pdf::loadView('reportes.pdf_general', compact('departamentos', 'totales'));
            return $pdf->download('reporte_general.pdf');
        }

        return view('reportes.general', compact('departamentos', 'totales'));
    }

    public function getMunicipios(Departamento $departamento)
    {
        return response()->json($departamento->municipios()->orderBy('nombre')->get());
    }

    public function getLocalidades(Municipio $municipio)
    {
        return response()->json($municipio->localidades()->orderBy('nombre')->get());
    }
}
