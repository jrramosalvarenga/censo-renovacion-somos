<?php

namespace App\Services;

use Cloudinary\Cloudinary;
use Cloudinary\Configuration\Configuration;
use Illuminate\Http\UploadedFile;

class CloudinaryService
{
    private ?Cloudinary $cloudinary;

    public function __construct()
    {
        $url = config('services.cloudinary.url');
        if ($url) {
            Configuration::instance($url);
            $this->cloudinary = new Cloudinary();
        } else {
            $this->cloudinary = null;
        }
    }

    public function subir(UploadedFile $archivo, string $carpeta = 'fotos'): ?string
    {
        if (!$this->cloudinary) {
            return null;
        }

        $resultado = $this->cloudinary->uploadApi()->upload(
            $archivo->getRealPath(),
            ['folder' => "censo/{$carpeta}", 'resource_type' => 'image']
        );

        return $resultado['secure_url'] ?? null;
    }

    public function eliminar(string $url): void
    {
        if (!$this->cloudinary || !str_contains($url, 'cloudinary')) {
            return;
        }

        // Extraer public_id de la URL
        preg_match('/\/censo\/[^\/]+\/([^.]+)/', $url, $m);
        if (!empty($m)) {
            $this->cloudinary->uploadApi()->destroy('censo/' . $m[1]);
        }
    }

    public function disponible(): bool
    {
        return $this->cloudinary !== null;
    }
}
