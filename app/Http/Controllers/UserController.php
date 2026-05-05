<?php

namespace App\Http\Controllers;

use App\Models\Departamento;
use App\Models\Municipio;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $usuarios = User::with('municipio.departamento')->orderBy('name')->paginate(20);
        return view('usuarios.index', compact('usuarios'));
    }

    public function create()
    {
        $departamentos = Departamento::orderBy('nombre')->get();
        return view('usuarios.create', compact('departamentos'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:100',
            'email'        => 'required|email|unique:users,email',
            'password'     => 'required|min:8|confirmed',
            'rol'          => 'required|in:operador,supervisor',
            'municipio_id' => 'required_if:rol,operador|nullable|exists:municipios,id',
        ], [
            'municipio_id.required_if' => 'El municipio es obligatorio para operadores.',
        ]);

        $validated['password']   = Hash::make($validated['password']);
        $validated['created_by'] = auth()->id();
        if ($validated['rol'] === 'supervisor') {
            $validated['municipio_id'] = null;
        }

        User::create($validated);
        return redirect()->route('usuarios.index')->with('success', 'Usuario creado exitosamente.');
    }

    public function edit(User $usuario)
    {
        $departamentos = Departamento::orderBy('nombre')->get();
        $municipios    = $usuario->municipio_id
            ? Municipio::where('departamento_id', $usuario->municipio->departamento_id)->orderBy('nombre')->get()
            : collect();
        return view('usuarios.edit', compact('usuario', 'departamentos', 'municipios'));
    }

    public function update(Request $request, User $usuario)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:100',
            'email'        => 'required|email|unique:users,email,'.$usuario->id,
            'password'     => 'nullable|min:8|confirmed',
            'rol'          => 'required|in:operador,supervisor',
            'municipio_id' => 'required_if:rol,operador|nullable|exists:municipios,id',
        ], [
            'municipio_id.required_if' => 'El municipio es obligatorio para operadores.',
        ]);

        if (empty($validated['password'])) {
            unset($validated['password']);
        } else {
            $validated['password'] = Hash::make($validated['password']);
        }
        if ($validated['rol'] === 'supervisor') {
            $validated['municipio_id'] = null;
        }

        $usuario->update($validated);
        return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado.');
    }

    public function destroy(User $usuario)
    {
        if ($usuario->id === auth()->id()) {
            return back()->with('error', 'No puedes eliminar tu propio usuario.');
        }
        $usuario->delete();
        return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado.');
    }

    public function getMunicipios(Departamento $departamento)
    {
        return response()->json($departamento->municipios()->orderBy('nombre')->get());
    }
}
