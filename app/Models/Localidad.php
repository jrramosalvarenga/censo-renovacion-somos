<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Localidad extends Model
{
    protected $table = 'localidades';

    protected $fillable = ['municipio_id', 'nombre', 'tipo'];

    public function municipio(): BelongsTo
    {
        return $this->belongsTo(Municipio::class);
    }

    public function miembros(): HasMany
    {
        return $this->hasMany(Miembro::class);
    }
}
