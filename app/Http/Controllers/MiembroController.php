<?php

namespace App\Http\Controllers;

use App\Models\Cargo;
use App\Models\Departamento;
use App\Models\Localidad;
use App\Models\Miembro;
use App\Models\Municipio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MiembroController extends Controller
{
    public function index(Request $request)
    {
        $query = Miembro::with(['localidad.municipio.departamento', 'cargo']);

        if ($request->filled('buscar')) {
            $q = $request->buscar;
            $query->where(function ($query) use ($q) {
                $query->where('nombres', 'ilike', "%$q%")
                      ->orWhere('apellidos', 'ilike', "%$q%")
                      ->orWhere('identidad', 'ilike', "%$q%")
                      ->orWhere('telefono', 'ilike', "%$q%");
            });
        }
        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }
        if ($request->filled('departamento_id')) {
            $query->whereHas('localidad.municipio', function ($q) use ($request) {
                $q->where('departamento_id', $request->departamento_id);
            });
        }
        if ($request->filled('municipio_id')) {
            $query->whereHas('localidad', function ($q) use ($request) {
                $q->where('municipio_id', $request->municipio_id);
            });
        }
        if ($request->filled('localidad_id')) {
            $query->where('localidad_id', $request->localidad_id);
        }
        if ($request->filled('cargo_id')) {
            $query->where('cargo_id', $request->cargo_id);
        }

        $miembros = $query->orderBy('apellidos')->orderBy('nombres')->paginate(20)->withQueryString();
        $departamentos = Departamento::orderBy('nombre')->get();
        $cargos = Cargo::orderBy('nombre')->get();
        $municipios = $request->filled('departamento_id')
            ? Municipio::where('departamento_id', $request->departamento_id)->orderBy('nombre')->get()
            : collect();
        $localidades = $request->filled('municipio_id')
            ? Localidad::where('municipio_id', $request->municipio_id)->orderBy('nombre')->get()
            : collect();

        return view('miembros.index', compact('miembros', 'departamentos', 'cargos', 'municipios', 'localidades'));
    }

    public function create()
    {
        $departamentos = Departamento::orderBy('nombre')->get();
        $cargos = Cargo::orderBy('nombre')->get();
        return view('miembros.create', compact('departamentos', 'cargos'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombres'          => 'required|string|max:100',
            'apellidos'        => 'required|string|max:100',
            'identidad'        => 'nullable|string|max:20|unique:miembros,identidad',
            'fecha_nacimiento' => 'nullable|date',
            'sexo'             => 'nullable|in:M,F',
            'telefono'         => 'nullable|string|max:20',
            'email'            => 'nullable|email|max:150',
            'direccion'        => 'nullable|string|max:255',
            'tipo'             => 'required|in:militante,simpatizante',
            'estado'           => 'required|in:activo,inactivo',
            'localidad_id'     => 'required|exists:localidades,id',
            'cargo_id'         => 'nullable|exists:cargos,id',
            'foto'             => 'nullable|image|max:2048',
            'observaciones'    => 'nullable|string',
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('fotos', 'public');
        }

        Miembro::create($validated);
        return redirect()->route('miembros.index')->with('success', 'Miembro registrado exitosamente.');
    }

    public function show(Miembro $miembro)
    {
        $miembro->load(['localidad.municipio.departamento', 'cargo']);
        return view('miembros.show', compact('miembro'));
    }

    public function edit(Miembro $miembro)
    {
        $departamentos = Departamento::orderBy('nombre')->get();
        $cargos = Cargo::orderBy('nombre')->get();
        $municipios = Municipio::where('departamento_id', $miembro->localidad->municipio->departamento_id)->orderBy('nombre')->get();
        $localidades = Localidad::where('municipio_id', $miembro->localidad->municipio_id)->orderBy('nombre')->get();
        return view('miembros.edit', compact('miembro', 'departamentos', 'cargos', 'municipios', 'localidades'));
    }

    public function update(Request $request, Miembro $miembro)
    {
        $validated = $request->validate([
            'nombres'          => 'required|string|max:100',
            'apellidos'        => 'required|string|max:100',
            'identidad'        => 'nullable|string|max:20|unique:miembros,identidad,' . $miembro->id,
            'fecha_nacimiento' => 'nullable|date',
            'sexo'             => 'nullable|in:M,F',
            'telefono'         => 'nullable|string|max:20',
            'email'            => 'nullable|email|max:150',
            'direccion'        => 'nullable|string|max:255',
            'tipo'             => 'required|in:militante,simpatizante',
            'estado'           => 'required|in:activo,inactivo',
            'localidad_id'     => 'required|exists:localidades,id',
            'cargo_id'         => 'nullable|exists:cargos,id',
            'foto'             => 'nullable|image|max:2048',
            'observaciones'    => 'nullable|string',
        ]);

        if ($request->hasFile('foto')) {
            if ($miembro->foto) {
                Storage::disk('public')->delete($miembro->foto);
            }
            $validated['foto'] = $request->file('foto')->store('fotos', 'public');
        }

        $miembro->update($validated);
        return redirect()->route('miembros.index')->with('success', 'Miembro actualizado exitosamente.');
    }

    public function destroy(Miembro $miembro)
    {
        if ($miembro->foto) {
            Storage::disk('public')->delete($miembro->foto);
        }
        $miembro->delete();
        return redirect()->route('miembros.index')->with('success', 'Miembro eliminado.');
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
