<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MensajeEnvio extends Model
{
    protected $table = 'mensaje_envios';
    public $timestamps = false;

    protected $fillable = [
        'mensaje_id', 'miembro_id', 'telefono', 'estado', 'error', 'sent_at',
    ];

    protected $casts = ['sent_at' => 'datetime'];

    public function mensaje(): BelongsTo
    {
        return $this->belongsTo(Mensaje::class);
    }

    public function miembro(): BelongsTo
    {
        return $this->belongsTo(Miembro::class);
    }
}
