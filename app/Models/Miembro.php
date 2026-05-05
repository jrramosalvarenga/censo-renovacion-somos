<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Miembro extends Model
{
    protected $table = 'miembros';

    protected $fillable = [
        'nombres', 'apellidos', 'identidad', 'fecha_nacimiento', 'sexo',
        'telefono', 'email', 'direccion', 'tipo', 'estado',
        'localidad_id', 'cargo_id', 'foto', 'observaciones', 'registered_by',
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
    ];

    public function localidad(): BelongsTo
    {
        return $this->belongsTo(Localidad::class);
    }

    public function cargo(): BelongsTo
    {
        return $this->belongsTo(Cargo::class);
    }

    public function getNombreCompletoAttribute(): string
    {
        return $this->nombres . ' ' . $this->apellidos;
    }
}
