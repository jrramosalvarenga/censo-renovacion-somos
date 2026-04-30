<?php

namespace App\Http\Controllers;

use App\Models\Departamento;
use App\Models\Localidad;
use App\Models\Mensaje;
use App\Models\MensajeEnvio;
use App\Models\Miembro;
use App\Models\Municipio;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MensajeController extends Controller
{
    public function index()
    {
        $mensajes = Mensaje::with('user')
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('mensajes.index', compact('mensajes'));
    }

    public function create()
    {
        $departamentos = Departamento::orderBy('nombre')->get();
        return view('mensajes.create', compact('departamentos'));
    }

    public function preview(Request $request)
    {
        $request->validate([
            'destino_tipo' => 'required|in:todos,departamento,municipio,localidad',
            'destino_id'   => 'required_unless:destino_tipo,todos|nullable|integer',
        ]);

        $miembros = $this->obtenerDestinatarios(
            $request->destino_tipo,
            $request->destino_id
        );

        $conTelefono    = $miembros->whereNotNull('telefono')->where('telefono', '!=', '')->count();
        $sinTelefono    = $miembros->count() - $conTelefono;
        $destinoNombre  = $this->nombreDestino($request->destino_tipo, $request->destino_id);

        return response()->json([
            'total'          => $miembros->count(),
            'con_telefono'   => $conTelefono,
            'sin_telefono'   => $sinTelefono,
            'destino_nombre' => $destinoNombre,
            'muestra'        => $miembros->whereNotNull('telefono')
                                         ->where('telefono', '!=', '')
                                         ->take(5)
                                         ->map(fn($m) => [
                                             'nombre'   => $m->nombre_completo,
                                             'telefono' => $m->telefono,
                                         ])->values(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'contenido'    => 'required|string|min:5|max:1000',
            'destino_tipo' => 'required|in:todos,departamento,municipio,localidad',
            'destino_id'   => 'required_unless:destino_tipo,todos|nullable|integer',
        ]);

        $miembros = $this->obtenerDestinatarios($request->destino_tipo, $request->destino_id)
            ->whereNotNull('telefono')
            ->where('telefono', '!=', '');

        if ($miembros->isEmpty()) {
            return back()->with('error', 'No hay miembros con teléfono en el destino seleccionado.')->withInput();
        }

        $destinoNombre = $this->nombreDestino($request->destino_tipo, $request->destino_id);

        $mensaje = Mensaje::create([
            'user_id'             => Auth::id(),
            'contenido'           => $request->contenido,
            'destino_tipo'        => $request->destino_tipo,
            'destino_id'          => $request->destino_id,
            'destino_nombre'      => $destinoNombre,
            'total_destinatarios' => $miembros->count(),
            'estado'              => 'enviando',
        ]);

        $whatsapp = new WhatsAppService();
        $enviados = 0;
        $fallidos = 0;

        foreach ($miembros as $miembro) {
            $resultado = $whatsapp->enviar($miembro->telefono, $request->contenido);

            MensajeEnvio::create([
                'mensaje_id' => $mensaje->id,
                'miembro_id' => $miembro->id,
                'telefono'   => $miembro->telefono,
                'estado'     => $resultado['ok'] ? 'enviado' : 'fallido',
                'error'      => $resultado['ok'] ? null : $resultado['error'],
                'sent_at'    => $resultado['ok'] ? now() : null,
            ]);

            $resultado['ok'] ? $enviados++ : $fallidos++;

            // Pausa pequeña para no saturar la API
            if ($enviados % 5 === 0) usleep(500000); // 0.5s cada 5 mensajes
        }

        $mensaje->update([
            'enviados' => $enviados,
            'fallidos' => $fallidos,
            'estado'   => $fallidos === $miembros->count() ? 'fallido' : 'completado',
        ]);

        return redirect()->route('mensajes.show', $mensaje)
            ->with('success', "Mensajes enviados: {$enviados} exitosos, {$fallidos} fallidos.");
    }

    public function show(Mensaje $mensaje)
    {
        $mensaje->load('user');
        $envios = MensajeEnvio::with('miembro')
            ->where('mensaje_id', $mensaje->id)
            ->orderBy('estado')
            ->paginate(50);

        return view('mensajes.show', compact('mensaje', 'envios'));
    }

    // ── AJAX: obtener municipios y localidades ──────────

    public function getMunicipios(Departamento $departamento)
    {
        return response()->json($departamento->municipios()->orderBy('nombre')->get());
    }

    public function getLocalidades(Municipio $municipio)
    {
        return response()->json($municipio->localidades()->orderBy('nombre')->get());
    }

    // ── Helpers ─────────────────────────────────────────

    private function obtenerDestinatarios(string $tipo, ?int $id)
    {
        return match($tipo) {
            'todos'        => Miembro::where('estado', 'activo')->get(),
            'departamento' => Miembro::where('estado', 'activo')
                                ->whereHas('localidad.municipio', fn($q) => $q->where('departamento_id', $id))
                                ->get(),
            'municipio'    => Miembro::where('estado', 'activo')
                                ->whereHas('localidad', fn($q) => $q->where('municipio_id', $id))
                                ->get(),
            'localidad'    => Miembro::where('estado', 'activo')
                                ->where('localidad_id', $id)
                                ->get(),
            default        => collect(),
        };
    }

    private function nombreDestino(string $tipo, ?int $id): string
    {
        return match($tipo) {
            'todos'        => 'Todos los miembros de Honduras',
            'departamento' => 'Depto. ' . (Departamento::find($id)?->nombre ?? ''),
            'municipio'    => 'Municipio ' . (Municipio::find($id)?->nombre ?? ''),
            'localidad'    => Localidad::find($id)?->nombre ?? '',
            default        => '',
        };
    }

    // ── Cumpleaños ──────────────────────────────────────

    public function cumpleanos()
    {
        $hoy      = now();
        $mes      = $hoy->month;
        $dia      = $hoy->day;

        $miembros = Miembro::whereNotNull('telefono')
            ->where('telefono', '!=', '')
            ->whereNotNull('fecha_nacimiento')
            ->whereMonth('fecha_nacimiento', $mes)
            ->whereDay('fecha_nacimiento', $dia)
            ->where('estado', 'activo')
            ->get();

        return view('mensajes.cumpleanos', compact('miembros', 'hoy'));
    }

    public function enviarCumpleanos(Request $request)
    {
        $request->validate([
            'mensaje_template' => 'required|string|min:10',
        ]);

        $hoy      = now();
        $mes      = $hoy->month;
        $dia      = $hoy->day;

        $miembros = Miembro::whereNotNull('telefono')
            ->where('telefono', '!=', '')
            ->whereNotNull('fecha_nacimiento')
            ->whereMonth('fecha_nacimiento', $mes)
            ->whereDay('fecha_nacimiento', $dia)
            ->where('estado', 'activo')
            ->get();

        if ($miembros->isEmpty()) {
            return back()->with('error', 'No hay cumpleañeros con teléfono registrado hoy.');
        }

        $mensaje = Mensaje::create([
            'user_id'             => Auth::id(),
            'contenido'           => $request->mensaje_template,
            'destino_tipo'        => 'todos',
            'destino_id'          => null,
            'destino_nombre'      => 'Cumpleañeros del ' . $hoy->format('d/m/Y'),
            'total_destinatarios' => $miembros->count(),
            'estado'              => 'enviando',
        ]);

        $whatsapp = new WhatsAppService();
        $enviados = 0;
        $fallidos = 0;

        foreach ($miembros as $miembro) {
            // Personalizar mensaje con el nombre del miembro
            $texto = str_replace(
                ['[NOMBRE]', '[nombre]', '{nombre}', '{NOMBRE}'],
                $miembro->nombres,
                $request->mensaje_template
            );

            $resultado = $whatsapp->enviar($miembro->telefono, $texto);

            MensajeEnvio::create([
                'mensaje_id' => $mensaje->id,
                'miembro_id' => $miembro->id,
                'telefono'   => $miembro->telefono,
                'estado'     => $resultado['ok'] ? 'enviado' : 'fallido',
                'error'      => $resultado['ok'] ? null : $resultado['error'],
                'sent_at'    => $resultado['ok'] ? now() : null,
            ]);

            $resultado['ok'] ? $enviados++ : $fallidos++;

            if ($enviados % 5 === 0) usleep(500000);
        }

        $mensaje->update([
            'enviados' => $enviados,
            'fallidos' => $fallidos,
            'estado'   => $fallidos === $miembros->count() ? 'fallido' : 'completado',
        ]);

        return redirect()->route('mensajes.show', $mensaje)
            ->with('success', "¡Felicitaciones enviadas! {$enviados} mensajes exitosos.");
    }
}
