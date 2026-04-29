<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Mensaje extends Model
{
    protected $table = 'mensajes';

    protected $fillable = [
        'user_id', 'contenido', 'destino_tipo', 'destino_id',
        'destino_nombre', 'total_destinatarios', 'enviados', 'fallidos', 'estado',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function envios(): HasMany
    {
        return $this->hasMany(MensajeEnvio::class);
    }

    public function getPorcentajeAttribute(): int
    {
        if ($this->total_destinatarios === 0) return 0;
        return (int) round($this->enviados / $this->total_destinatarios * 100);
    }
}
