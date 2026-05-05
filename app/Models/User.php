<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password',
        'rol', 'municipio_id', 'google_id', 'avatar', 'created_by',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    public function municipio(): BelongsTo
    {
        return $this->belongsTo(Municipio::class);
    }

    public function miembrosRegistrados()
    {
        return $this->hasMany(Miembro::class, 'registered_by');
    }

    // Quién creó este usuario
    public function creadoPor()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Usuarios que yo creé (mis enroladores directos)
    public function usuariosCreados()
    {
        return $this->hasMany(User::class, 'created_by');
    }

    public function esSupervisor(): bool
    {
        return $this->rol === 'supervisor';
    }

    public function esOperador(): bool
    {
        return $this->rol === 'operador';
    }
}
