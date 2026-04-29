<?php

namespace App\Http\Controllers;

use App\Models\Departamento;
use App\Models\Localidad;
use App\Models\Municipio;
use Illuminate\Http\Request;

class LocalidadController extends Controller
{
    public function index(Request $request)
    {
        $query = Localidad::with('municipio.departamento');

        if ($request->filled('departamento_id')) {
            $query->whereHas('municipio', fn($q) => $q->where('departamento_id', $request->departamento_id));
        }
        if ($request->filled('municipio_id')) {
            $query->where('municipio_id', $request->municipio_id);
        }

        $localidades = $query->orderBy('nombre')->paginate(20)->withQueryString();
        $departamentos = Departamento::orderBy('nombre')->get();
        $municipios = $request->filled('departamento_id')
            ? Municipio::where('departamento_id', $request->departamento_id)->orderBy('nombre')->get()
            : collect();

        return view('localidades.index', compact('localidades', 'departamentos', 'municipios'));
    }

    public function create()
    {
        $departamentos = Departamento::orderBy('nombre')->get();
        return view('localidades.create', compact('departamentos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'municipio_id' => 'required|exists:municipios,id',
            'nombre'       => 'required|string|max:150',
            'tipo'         => 'required|in:aldea,barrio,colonia,ciudad',
        ]);

        Localidad::create($request->only('municipio_id', 'nombre', 'tipo'));
        return redirect()->route('localidades.index')->with('success', 'Localidad creada exitosamente.');
    }

    public function edit(Localidad $localidad)
    {
        $departamentos = Departamento::orderBy('nombre')->get();
        $municipios = Municipio::where('departamento_id', $localidad->municipio->departamento_id)->orderBy('nombre')->get();
        return view('localidades.edit', compact('localidad', 'departamentos', 'municipios'));
    }

    public function update(Request $request, Localidad $localidad)
    {
        $request->validate([
            'municipio_id' => 'required|exists:municipios,id',
            'nombre'       => 'required|string|max:150',
            'tipo'         => 'required|in:aldea,barrio,colonia,ciudad',
        ]);

        $localidad->update($request->only('municipio_id', 'nombre', 'tipo'));
        return redirect()->route('localidades.index')->with('success', 'Localidad actualizada.');
    }

    public function destroy(Localidad $localidad)
    {
        if ($localidad->miembros()->exists()) {
            return back()->with('error', 'No se puede eliminar: tiene miembros registrados.');
        }
        $localidad->delete();
        return redirect()->route('localidades.index')->with('success', 'Localidad eliminada.');
    }
}
