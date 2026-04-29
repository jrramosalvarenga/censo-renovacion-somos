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
            return ['ok' => false, 'error' => 'WhatsApp no configurado (falta ULTRAMSG_INSTANCE o ULTRAMSG_TOKEN)'];
        }

        try {
            $response = Http::timeout(15)
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

    private function formatearNumero(string $telefono): ?string
    {
        // Quitar todo excepto dígitos
        $solo = preg_replace('/\D/', '', $telefono);

        if (strlen($solo) === 8) {
            // Número Honduras sin código de país → agregar 504
            return '504' . $solo;
        }

        if (strlen($solo) >= 10) {
            return $solo;
        }

        return null;
    }
}
