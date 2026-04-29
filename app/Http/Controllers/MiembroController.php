<?php

namespace App\Http\Controllers;

use App\Models\Cargo;
use App\Models\Departamento;
use App\Models\Localidad;
use App\Models\Miembro;
use App\Models\Municipio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MiembroController extends Controller
{
    private function soloMiMunicipio(): bool
    {
        return Auth::user()->esOperador();
    }

    public function index(Request $request)
    {
        $user  = Auth::user();
        $query = Miembro::with(['localidad.municipio.departamento', 'cargo']);

        // Operador solo ve su municipio
        if ($user->esOperador()) {
            $query->whereHas('localidad', fn($q) => $q->where('municipio_id', $user->municipio_id));
        }

        if ($request->filled('buscar')) {
            $q = $request->buscar;
            $query->where(fn($query) => $query
                ->where('nombres', 'ilike', "%$q%")
                ->orWhere('apellidos', 'ilike', "%$q%")
                ->orWhere('identidad', 'ilike', "%$q%")
                ->orWhere('telefono', 'ilike', "%$q%")
            );
        }
        if ($request->filled('tipo'))    $query->where('tipo', $request->tipo);
        if ($request->filled('estado'))  $query->where('estado', $request->estado);
        if ($request->filled('cargo_id')) $query->where('cargo_id', $request->cargo_id);

        if ($user->esSupervisor()) {
            if ($request->filled('departamento_id')) {
                $query->whereHas('localidad.municipio', fn($q) => $q->where('departamento_id', $request->departamento_id));
            }
            if ($request->filled('municipio_id')) {
                $query->whereHas('localidad', fn($q) => $q->where('municipio_id', $request->municipio_id));
            }
        }
        if ($request->filled('localidad_id')) $query->where('localidad_id', $request->localidad_id);

        $miembros     = $query->orderBy('apellidos')->orderBy('nombres')->paginate(20)->withQueryString();
        $departamentos = $user->esSupervisor() ? Departamento::orderBy('nombre')->get() : collect();
        $cargos       = Cargo::orderBy('nombre')->get();
        $municipios   = $request->filled('departamento_id')
            ? Municipio::where('departamento_id', $request->departamento_id)->orderBy('nombre')->get()
            : collect();
        $localidades  = collect();
        if ($request->filled('municipio_id')) {
            $localidades = Localidad::where('municipio_id', $request->municipio_id)->orderBy('nombre')->get();
        } elseif ($user->esOperador()) {
            $localidades = Localidad::where('municipio_id', $user->municipio_id)->orderBy('nombre')->get();
        }

        return view('miembros.index', compact('miembros', 'departamentos', 'cargos', 'municipios', 'localidades'));
    }

    public function create()
    {
        $user         = Auth::user();
        $departamentos = $user->esSupervisor() ? Departamento::orderBy('nombre')->get() : collect();
        $cargos       = Cargo::orderBy('nombre')->get();

        // Operador: pasa sus localidades directamente
        $municipios  = collect();
        $localidades = collect();
        if ($user->esOperador() && $user->municipio_id) {
            $municipios  = Municipio::where('id', $user->municipio_id)->get();
            $localidades = Localidad::where('municipio_id', $user->municipio_id)->orderBy('nombre')->get();
        }

        return view('miembros.create', compact('departamentos', 'cargos', 'municipios', 'localidades'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

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

        // Operador solo puede registrar en su municipio
        if ($user->esOperador()) {
            $localidad = Localidad::findOrFail($validated['localidad_id']);
            if ($localidad->municipio_id !== $user->municipio_id) {
                return back()->withErrors(['localidad_id' => 'Solo puedes registrar miembros de tu municipio.']);
            }

            // Verificar que no esté ya enrolado (por identidad)
            if (!empty($validated['identidad'])) {
                $existe = Miembro::where('identidad', $validated['identidad'])->exists();
                if ($existe) {
                    return back()->withErrors(['identidad' => 'Esta persona ya está registrada en el sistema.'])->withInput();
                }
            }
        }

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('fotos', 'public');
        }

        Miembro::create($validated);
        return redirect()->route('miembros.index')->with('success', 'Miembro registrado exitosamente.');
    }

    public function show(Miembro $miembro)
    {
        $this->autorizarAcceso($miembro);
        $miembro->load(['localidad.municipio.departamento', 'cargo']);
        return view('miembros.show', compact('miembro'));
    }

    public function edit(Miembro $miembro)
    {
        $this->soloSupervisor();
        $departamentos = Departamento::orderBy('nombre')->get();
        $cargos        = Cargo::orderBy('nombre')->get();
        $municipios    = Municipio::where('departamento_id', $miembro->localidad->municipio->departamento_id)->orderBy('nombre')->get();
        $localidades   = Localidad::where('municipio_id', $miembro->localidad->municipio_id)->orderBy('nombre')->get();
        return view('miembros.edit', compact('miembro', 'departamentos', 'cargos', 'municipios', 'localidades'));
    }

    public function update(Request $request, Miembro $miembro)
    {
        $this->soloSupervisor();

        $validated = $request->validate([
            'nombres'          => 'required|string|max:100',
            'apellidos'        => 'required|string|max:100',
            'identidad'        => 'nullable|string|max:20|unique:miembros,identidad,'.$miembro->id,
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
            if ($miembro->foto) Storage::disk('public')->delete($miembro->foto);
            $validated['foto'] = $request->file('foto')->store('fotos', 'public');
        }

        $miembro->update($validated);
        return redirect()->route('miembros.index')->with('success', 'Miembro actualizado exitosamente.');
    }

    public function destroy(Miembro $miembro)
    {
        $this->soloSupervisor();
        if ($miembro->foto) Storage::disk('public')->delete($miembro->foto);
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

    private function soloSupervisor(): void
    {
        if (Auth::user()->esOperador()) {
            abort(403, 'Solo los supervisores pueden realizar esta acción.');
        }
    }

    private function autorizarAcceso(Miembro $miembro): void
    {
        $user = Auth::user();
        if ($user->esOperador() && $miembro->localidad->municipio_id !== $user->municipio_id) {
            abort(403, 'No tienes acceso a este miembro.');
        }
    }
}
