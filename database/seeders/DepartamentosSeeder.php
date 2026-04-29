<?php

namespace Database\Seeders;

use App\Models\Departamento;
use Illuminate\Database\Seeder;

class DepartamentosSeeder extends Seeder
{
    public function run(): void
    {
        $departamentos = [
            'Atlántida', 'Choluteca', 'Colón', 'Comayagua', 'Copán',
            'Cortés', 'El Paraíso', 'Francisco Morazán', 'Gracias a Dios',
            'Intibucá', 'Islas de la Bahía', 'La Paz', 'Lempira',
            'Ocotepeque', 'Olancho', 'Santa Bárbara', 'Valle', 'Yoro',
        ];

        foreach ($departamentos as $nombre) {
            Departamento::firstOrCreate(['nombre' => $nombre]);
        }
    }
}
