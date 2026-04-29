<?php

namespace Database\Seeders;

use App\Models\Cargo;
use Illuminate\Database\Seeder;

class CargosSeeder extends Seeder
{
    public function run(): void
    {
        $cargos = [
            ['nombre' => 'Presidente', 'descripcion' => 'Presidente del movimiento en la localidad'],
            ['nombre' => 'Secretario', 'descripcion' => 'Secretario general del movimiento'],
            ['nombre' => 'Tesorero', 'descripcion' => 'Tesorero del movimiento'],
            ['nombre' => 'Secretario de Juventud', 'descripcion' => 'Encargado de asuntos de juventud'],
            ['nombre' => 'Secretario de Asuntos de la Mujer', 'descripcion' => 'Encargado de asuntos de la mujer'],
            ['nombre' => 'Secretario de Poder Popular', 'descripcion' => 'Encargado del poder popular comunitario'],
        ];

        foreach ($cargos as $cargo) {
            Cargo::firstOrCreate(['nombre' => $cargo['nombre']], $cargo);
        }
    }
}
