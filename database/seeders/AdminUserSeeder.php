<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@renovacionsomos.hn'],
            [
                'name'     => 'Administrador',
                'password' => Hash::make('Renovacion2024!'),
                'rol'      => 'supervisor',
            ]
        );

        // Actualiza a supervisor si ya existía sin rol
        User::where('email', 'admin@renovacionsomos.hn')
            ->whereNull('rol')
            ->update(['rol' => 'supervisor']);

        // Asegura que el admin tenga rol supervisor
        User::where('email', 'admin@renovacionsomos.hn')
            ->update(['rol' => 'supervisor']);

        User::firstOrCreate(
            ['email' => 'administrador@renovacionsomos.hn'],
            [
                'name'     => 'Administrador',
                'password' => Hash::make('Renovacionsomos2026..'),
                'rol'      => 'supervisor',
            ]
        );

        User::firstOrCreate(
            ['email' => 'administrador2@renovacionsomos.hn'],
            [
                'name'     => 'Administrador 2',
                'password' => Hash::make('Renovacionsomos2026..'),
                'rol'      => 'supervisor',
            ]
        );
    }
}
