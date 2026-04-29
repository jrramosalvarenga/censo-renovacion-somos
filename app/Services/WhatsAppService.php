<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    private string $instance;
    private string $token;
    private string $baseUrl;

    public function __construct()
    {
        $this->instance = config('services.ultramsg.instance', '');
        $this->token    = config('services.ultramsg.token', '');
        $this->baseUrl  = "https://api.ultramsg.com/{$this->instance}";
    }

    public function enviar(string $telefono, string $mensaje): array
    {
        $numero = $this->formatearNumero($telefono);

        if (!$numero) {
            return ['ok' => false, 'error' => 'Número inválido'];
        }

        if (!$this->instance || !$this->token) {
            return ['ok' => false, 'error' => 'WhatsApp no configurado'];
        }

        try {
            $response = Http::timeout(15)
                ->withOptions(['verify' => $this->sslCert()])
                ->asForm()
                ->post("{$this->baseUrl}/messages/chat", [
                    'token' => $this->token,
                    'to'    => $numero,
                    'body'  => $mensaje,
                ]);

            $data = $response->json();

            if ($response->successful() && isset($data['sent']) && $data['sent'] === 'true') {
                return ['ok' => true];
            }

            $error = $data['error'] ?? $data['message'] ?? 'Error desconocido';
            return ['ok' => false, 'error' => $error];

        } catch (\Exception $e) {
            Log::error('WhatsApp error: ' . $e->getMessage());
            return ['ok' => false, 'error' => $e->getMessage()];
        }
    }

    // Ruta del certificado SSL según entorno
    private function sslCert(): string|bool
    {
        // En producción (Linux/Docker) usa los certificados del sistema
        if (PHP_OS_FAMILY !== 'Windows') {
            return true;
        }

        // En Windows busca el cacert.pem en varias rutas comunes de Laragon
        $rutas = [
            'C:/laragon/etc/ssl/cacert.pem',
            ini_get('curl.cainfo'),
        ];

        foreach ($rutas as $ruta) {
            if ($ruta && file_exists($ruta)) {
                return $ruta;
            }
        }

        // Último recurso: sin verificación (solo desarrollo local)
        return false;
    }

    private function formatearNumero(string $telefono): ?string
    {
        $solo = preg_replace('/\D/', '', $telefono);

        if (strlen($solo) === 8) {
            return '504' . $solo;
        }

        if (strlen($solo) >= 10) {
            return $solo;
        }

        return null;
    }
}
