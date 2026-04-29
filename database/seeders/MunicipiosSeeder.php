<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MunicipiosSeeder extends Seeder
{
    public function run(): void
    {
        $municipiosPorDepartamento = [
            'Atlántida' => [
                'Arizona', 'El Porvenir', 'Esparta', 'Jutiapa',
                'La Ceiba', 'La Masica', 'San Francisco', 'Tela',
            ],
            'Choluteca' => [
                'Apacilagua', 'Choluteca', 'Concepción de María', 'Duyure',
                'El Corpus', 'El Triunfo', 'Liure', 'Marcovia', 'Morolica',
                'Namasigüe', 'Orocuina', 'Pespire', 'San Antonio de Flores',
                'San Isidro', 'San José', 'San Marcos de Colón',
            ],
            'Colón' => [
                'Balfate', 'Bonito Oriental', 'Iriona', 'Limón', 'Sabá',
                'Santa Fe', 'Santa Rosa de Aguán', 'Sonaguera', 'Tocoa', 'Trujillo',
            ],
            'Comayagua' => [
                'Ajuterique', 'Comayagua', 'El Rosario', 'Esquías', 'Humuya',
                'La Libertad', 'La Trinidad', 'Lamaní', 'Las Lajas', 'Lejamaní',
                'Meámbar', 'Minas de Oro', 'Ojos de Agua', 'San Jerónimo',
                'San José de Comayagua', 'San José del Potrero', 'San Luis',
                'San Sebastián', 'Siguatepeque', 'Trinidad', 'Villa de San Antonio',
            ],
            'Copán' => [
                'Cabañas', 'Concepción', 'Copán Ruinas', 'Corquín', 'Cucuyagua',
                'Dolores', 'Dulce Nombre', 'El Paraíso', 'Florida', 'La Jigua',
                'La Unión', 'Nueva Arcadia', 'San Agustín', 'San Antonio',
                'San Jerónimo', 'San José', 'San Juan de Opoa', 'San Nicolás',
                'San Pedro', 'Santa Rita', 'San Rufo', 'Trinidad de Copán', 'Veracruz',
            ],
            'Cortés' => [
                'Choloma', 'La Lima', 'Omoa', 'Pimienta', 'Potrerillos',
                'Puerto Cortés', 'San Antonio de Cortés', 'San Francisco de Yojoa',
                'San Manuel', 'San Pedro Sula', 'Santa Cruz de Yojoa', 'Villanueva',
            ],
            'El Paraíso' => [
                'Alauca', 'Danlí', 'El Paraíso', 'Güinope', 'Jacaleapa', 'Liure',
                'Morocelí', 'Oropolí', 'Potrerillos', 'San Antonio de Flores',
                'San Lucas', 'San Matías', 'Soledad', 'Teupasenti', 'Texiguat',
                'Trojes', 'Vado Ancho', 'Yauyupe', 'Yuscarán',
            ],
            'Francisco Morazán' => [
                'Alubarén', 'Cedros', 'Curarén', 'Distrito Central', 'El Porvenir',
                'Guaimaca', 'La Libertad', 'La Venta', 'Lepaterique', 'Maraita',
                'Marale', 'Nueva Armenia', 'Ojojona', 'Orica', 'Reitoca',
                'Sabanagrande', 'San Antonio de Oriente', 'San Buenaventura',
                'San Ignacio', 'San Juan de Flores', 'San Miguelito', 'Santa Ana',
                'Santa Lucía', 'Talanga', 'Tatumbla', 'Valle de Ángeles',
                'Villa de San Francisco', 'Vallecillo',
            ],
            'Gracias a Dios' => [
                'Ahuas', 'Brus Laguna', 'Juan Francisco Bulnes',
                'Puerto Lempira', 'Ramón Villeda Morales', 'Wampusirpi',
            ],
            'Intibucá' => [
                'Camasca', 'Colomoncagua', 'Concepción', 'Dolores', 'Intibucá',
                'Jesús de Otoro', 'La Esperanza', 'Magdalena', 'Masaguara',
                'San Antonio', 'San Francisco de Opalaca', 'San Isidro', 'San Juan',
                'San Marcos de la Sierra', 'San Miguelito', 'Santa Lucía', 'Yamaranguila',
            ],
            'Islas de la Bahía' => [
                'Guanaja', 'José Santos Guardiola', 'Roatán', 'Utila',
            ],
            'La Paz' => [
                'Aguanqueterique', 'Cabañas', 'Cane', 'Chinacla', 'Guajiquiro',
                'La Paz', 'Lauterique', 'Marcala', 'Mercedes de Oriente', 'Opatoro',
                'San Antonio del Norte', 'San Juan', 'San Miguel', 'San Pedro de Tutule',
                'Santa Ana', 'Santa Elena', 'Santa María', 'Santiago de Puringla', 'Yarula',
            ],
            'Lempira' => [
                'Belén', 'Candelaria', 'Cololaca', 'Erandique', 'Gracias', 'Gualcince',
                'Guarita', 'La Campa', 'La Iguala', 'La Unión', 'La Virtud', 'Las Flores',
                'Lepaera', 'Mapulaca', 'Piraera', 'San Andrés', 'San Francisco',
                'San Juan de Guarita', 'San Manuel de Colohete', 'San Marcos de Caiquín',
                'San Rafael', 'San Sebastián', 'Santa Cruz', 'Talgua', 'Tambla',
                'Tomalá', 'Valladolid', 'Virginia',
            ],
            'Ocotepeque' => [
                'Belén Gualcho', 'Concepción', 'Dolores Merendón', 'Fraternidad',
                'La Encarnación', 'La Labor', 'Lucerna', 'Mercedes', 'Ocotepeque',
                'San Fernando', 'San Francisco del Valle', 'San Jorge', 'San Marcos',
                'Santa Fe', 'Sensenti', 'Sinuapa',
            ],
            'Olancho' => [
                'Campamento', 'Catacamas', 'Concordia', 'Dulce Nombre de Culmí',
                'El Rosario', 'Esquipulas del Norte', 'Gualaco', 'Guarizama', 'Guata',
                'Juticalpa', 'La Unión', 'Lafitte', 'Mangulile', 'Manto', 'Patuca',
                'Salamá', 'San Carlos', 'San Esteban', 'San Francisco de Becerra',
                'San Francisco de la Paz', 'Santa María del Real', 'Silca', 'Yocón',
            ],
            'Santa Bárbara' => [
                'Arada', 'Atima', 'Azacualpa', 'Ceguaca', 'Chinda',
                'Concepción del Norte', 'Concepción del Sur', 'El Níspero', 'Gualala',
                'Ilama', 'Las Vegas', 'Macuelizo', 'Naranjito', 'Nueva Frontera',
                'Petoa', 'Protección', 'Quimistán', 'San Francisco de Ojuera',
                'San José de Colinas', 'San Luis', 'San Marcos', 'San Nicolás',
                'San Pedro Zacapa', 'San Vicente Centenario', 'Santa Bárbara',
                'Santa Rita', 'Trinidad', 'Unión',
            ],
            'Valle' => [
                'Alianza', 'Amapala', 'Aramecina', 'Caridad', 'Goascorán',
                'Langue', 'Nacaome', 'San Francisco de Coray', 'San Lorenzo',
            ],
            'Yoro' => [
                'Arenal', 'El Negrito', 'El Progreso', 'Jocón', 'Morazán',
                'Olanchito', 'Sabanarredonda', 'Sulaco', 'Victoria', 'Yorito', 'Yoro',
            ],
        ];

        $now = now();

        foreach ($municipiosPorDepartamento as $departamento => $municipios) {
            $dep = DB::table('departamentos')->where('nombre', $departamento)->first();

            if (!$dep) {
                $this->command->warn("Departamento no encontrado: $departamento");
                continue;
            }

            foreach ($municipios as $nombre) {
                DB::table('municipios')->insertOrIgnore([
                    'departamento_id' => $dep->id,
                    'nombre'          => $nombre,
                    'created_at'      => $now,
                    'updated_at'      => $now,
                ]);
            }
        }

        $total = DB::table('municipios')->count();
        $this->command->info("Municipios insertados: $total");
    }
}
