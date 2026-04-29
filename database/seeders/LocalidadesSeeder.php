<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocalidadesSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        // Estructura: Departamento => [ Municipio => [aldeas...] ]
        $data = [

            // ══════════════════════════════════════════════
            'Atlántida' => [
                'Arizona' => [
                    'Arizona','Campo Verde','El Paraíso','La Guadalupe','La Lima',
                    'Las Piñas','Los Planes','Nuevo Paraíso','San Francisco',
                ],
                'El Porvenir' => [
                    'El Porvenir','Brisas del Mar','Colorado','El Esfuerzo','La Ensenada',
                    'Los Limones','Nueva Armenia','Punta Caxinas','Río Esteban','San Antonio',
                ],
                'Esparta' => [
                    'Esparta','El Cacao','El Jaral','La Ceibita','Los Encuentros',
                    'Mezapita','Nueva Esparta','Plan de Flores','Santa Ana','Santa Rosa',
                ],
                'Jutiapa' => [
                    'Jutiapa','El Cedro','El Naranjo','La Entrada','Los Planes',
                    'Palacios','San Francisco','Santa María','Triunfo',
                ],
                'La Ceiba' => [
                    'Barrio Alvarado','Barrio El Centro','Barrio La Isla','Barrio Potreritos',
                    'Barrio Río Negro','Colonia El Sauce','Colonia Kennedy','Colonia Los Pinos',
                    'Colonia Planeta','El Pino','El Triunfo de la Cruz','Las Glorias',
                    'Mezapita','Nueva Armenia','Plan de Flores','Río Cangrejal','Río María',
                    'Sambo Creek','San Antonio','Yaruca',
                ],
                'La Masica' => [
                    'La Masica','El Cacao','El Paraíso','El Progreso','Jutiapa',
                    'La Cruz','La Lima','Las Palmas','Los Angeles','Nuevo Paraíso',
                    'Piedra Pintada','Planes de Cuero','San Francisco','Santa Cruz',
                ],
                'San Francisco' => [
                    'San Francisco','El Aguacate','El Naranjo','La Ceiba','La Estancia',
                    'Los Laureles','Nueva Vida','Planes de Cuero','Quebrada de Arena',
                ],
                'Tela' => [
                    'Tela','El Triunfo','Ensenada','Guadalupe','La Bolsa','La Inca',
                    'Las Pitas','Los Claros','Mezapita','Nuevo Aguán','Río Tinto',
                    'San Alejo','San Juan','Tornabé',
                ],
            ],

            // ══════════════════════════════════════════════
            'Choluteca' => [
                'Apacilagua' => [
                    'Apacilagua','El Espino','El Paraíso','El Ranchón','La Concepción',
                    'Las Flores','Los Llanitos','Paso Real','San Marcos','Santa Cruz',
                ],
                'Choluteca' => [
                    'Choluteca','Barrio El Centro','Barrio El Calvario','Colonia El Carmen',
                    'Colonia Kennedy','Colonia La Fuente','Colonia Las Palmas',
                    'El Corpus','El Triunfo','La Concepción','La Fragua',
                    'La Trinidad','Las Delicias','Los Encuentros','Monjarás',
                    'Morolica','Orocuina','Pespire','San Marcos','Santa Cruz de Yusguare',
                ],
                'Concepción de María' => [
                    'Concepción de María','El Cacao','El Ojochal','La Estancia',
                    'Las Flores','Los Llanitos','San Isidro','San Pedro',
                ],
                'Duyure' => [
                    'Duyure','El Chiflador','El Zapotal','La Cañada','La Fortuna',
                    'Las Brisas','Las Tunas','Los Planes','San Marcos',
                ],
                'El Corpus' => [
                    'El Corpus','El Barranco','El Juncal','El Ojochal','La Cañada',
                    'Las Lajas','Las Vegas','Los Horcones','San Antonio','San Marcos','Santa Cruz',
                ],
                'El Triunfo' => [
                    'El Triunfo','El Cacao','El Edén','El Paraíso','La Concepción',
                    'La Paz','Las Lajas','Los Llanitos','San Isidro','Santa Cruz',
                ],
                'Liure' => [
                    'Liure','El Barro','El Cedro','El Ojochal','La Cañada',
                    'La Estancia','Las Flores','Los Planes','San Antonio','San Francisco',
                ],
                'Marcovia' => [
                    'Marcovia','Agua Fría','El Edén','El Paraíso','El Triunfo',
                    'Guapinol','La Concepción','Las Delicias','Playona','San Bernardo',
                    'San Lorenzo','Santa Rosa',
                ],
                'Morolica' => [
                    'Morolica','El Cedro','El Juncal','El Quebracho','La Cañada',
                    'La Estancia','Las Flores','Los Llanitos','San Antonio','Santa Cruz',
                ],
                'Namasigüe' => [
                    'Namasigüe','El Cacao','El Paraíso','La Arada','La Concepción',
                    'La Fortuna','Las Lajas','Los Llanitos','San Marcos','Santa Cruz',
                ],
                'Orocuina' => [
                    'Orocuina','El Barro','El Cedro','El Naranjal','La Cañada',
                    'La Estancia','Las Flores','Los Planes','San Antonio','San Francisco',
                ],
                'Pespire' => [
                    'Pespire','El Barro','El Cedro','El Ojochal','La Cañada',
                    'La Estancia','Las Flores','Los Llanitos','San Antonio','San Marcos',
                ],
                'San Antonio de Flores' => [
                    'San Antonio de Flores','El Barro','El Cedro','La Cañada',
                    'La Estancia','Las Flores','Los Llanitos','San Marcos','Santa Cruz',
                ],
                'San Isidro' => [
                    'San Isidro','El Barro','El Cedro','La Cañada','La Estancia',
                    'Las Flores','Los Llanitos','San Antonio','San Marcos',
                ],
                'San José' => [
                    'San José','El Cacao','El Ojochal','La Arada','La Cañada',
                    'La Estancia','Las Flores','Los Llanitos','Santa Cruz',
                ],
                'San Marcos de Colón' => [
                    'San Marcos de Colón','El Barro','El Cedro','El Guaylo','La Cañada',
                    'La Estancia','Las Flores','Los Llanitos','San Antonio','San Francisco','Santa Cruz',
                ],
            ],

            // ══════════════════════════════════════════════
            'Colón' => [
                'Balfate' => [
                    'Balfate','El Pino','Guadalupe','La Ceibita','La Cruz',
                    'Las Brisas','Los Planes','Nueva Esperanza','Santa Rosa','Sico',
                ],
                'Bonito Oriental' => [
                    'Bonito Oriental','El Paraíso','La Libertad','Las Champas',
                    'Las Vegas','Los Planes','Nueva Palestina','San Esteban','San Pedro',
                ],
                'Iriona' => [
                    'Iriona','Batalla','Barra Patuca','Ciriboya','Cocalito',
                    'Cocobila','La Ceibita','Paplaya','Plaplaya','Río Plátano',
                    'Santa Rosa de Aguán','Tocamacho',
                ],
                'Limón' => [
                    'Limón','El Chiquito','El Triunfo','La Ceibita','La Cruz',
                    'Las Delicias','Los Planes','Nueva Esperanza','San Francisco','Santa Rosa',
                ],
                'Sabá' => [
                    'Sabá','El Paraíso','El Progreso','La Ceibita','La Lima',
                    'Las Palmas','Los Angeles','Nueva Esperanza','San Francisco','Santa Cruz',
                ],
                'Santa Fe' => [
                    'Santa Fe','El Buen Retiro','El Cacao','La Ceibita','La Fortuna',
                    'Las Delicias','Los Planes','Nueva Esperanza','San Antonio','San Francisco',
                ],
                'Santa Rosa de Aguán' => [
                    'Santa Rosa de Aguán','El Paraíso','La Ceibita','La Cruz',
                    'Las Champas','Los Planes','Nueva Esperanza','Río Aguán',
                ],
                'Sonaguera' => [
                    'Sonaguera','El Buen Retiro','El Paraíso','El Progreso','La Ceibita',
                    'La Lima','Las Palmas','Los Angeles','Nueva Esperanza','San Francisco','Santa Cruz',
                ],
                'Tocoa' => [
                    'Tocoa','El Paraíso','El Progreso','La Ceibita','La Lima',
                    'Las Champas','Las Palmas','Los Angeles','Nueva Esperanza',
                    'Rigores','San Francisco','Santa Cruz',
                ],
                'Trujillo' => [
                    'Trujillo','Barrio El Centro','Castilla','Corocito',
                    'El Buen Retiro','Guadalupe','Guaimoreto','La Ceibita',
                    'Nuevo Trujillo','Puerto Castilla','Santa Fe','Santa Rosa',
                ],
            ],

            // ══════════════════════════════════════════════
            'Comayagua' => [
                'Ajuterique' => [
                    'Ajuterique','El Cacao','El Guacamayo','El Jicaro','La Cuesta',
                    'La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos',
                ],
                'Comayagua' => [
                    'Comayagua','Barrio Abajo','Barrio Arriba','Barrio El Centro',
                    'Colonia El Paraíso','Colonia Kennedy','Colonia Los Pinos',
                    'El Aguacate','El Coyolar','El Guapinol','La Concepción',
                    'La Estancia','Las Lagunas','Las Vegas','Los Llanitos',
                    'Río Sulaco','San Jerónimo','San José','San Juan','Santa Cruz',
                ],
                'El Rosario' => [
                    'El Rosario','El Cacao','El Guacamayo','El Jicaro','La Cuesta',
                    'La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos',
                ],
                'Esquías' => [
                    'Esquías','El Cacao','El Guacamayo','El Jicaro','La Cuesta',
                    'La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos',
                ],
                'Humuya' => [
                    'Humuya','El Cacao','El Guacamayo','La Cuesta','La Estancia',
                    'Las Vegas','Los Planes','San Antonio','San Marcos',
                ],
                'La Libertad' => [
                    'La Libertad','El Aguacate','El Cacao','El Guacamayo','El Jicaro',
                    'La Cuesta','Las Vegas','Los Planes','San Antonio','San Marcos','Santa Cruz',
                ],
                'La Trinidad' => [
                    'La Trinidad','El Cacao','El Guacamayo','La Cuesta',
                    'La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos',
                ],
                'Lamaní' => [
                    'Lamaní','El Cacao','El Guacamayo','La Cuesta','La Estancia',
                    'Las Vegas','Los Planes','San Antonio','San Marcos',
                ],
                'Las Lajas' => [
                    'Las Lajas','El Cacao','El Guacamayo','La Cuesta','La Estancia',
                    'Las Vegas','Los Planes','San Antonio','San Marcos',
                ],
                'Lejamaní' => [
                    'Lejamaní','El Cacao','El Guacamayo','La Cuesta','La Estancia',
                    'Las Vegas','Los Planes','San Antonio','San Marcos',
                ],
                'Meámbar' => [
                    'Meámbar','El Cacao','El Guacamayo','La Cuesta','La Estancia',
                    'Las Vegas','Los Planes','San Antonio','San Marcos','Santa Cruz',
                ],
                'Minas de Oro' => [
                    'Minas de Oro','El Cacao','El Guacamayo','El Jicaro','La Cuesta',
                    'La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos',
                ],
                'Ojos de Agua' => [
                    'Ojos de Agua','El Cacao','El Guacamayo','La Cuesta',
                    'La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos',
                ],
                'San Jerónimo' => [
                    'San Jerónimo','El Cacao','El Guacamayo','La Cuesta','La Estancia',
                    'Las Vegas','Los Planes','San Antonio','San Marcos',
                ],
                'San José de Comayagua' => [
                    'San José de Comayagua','El Cacao','El Guacamayo','La Cuesta',
                    'La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos',
                ],
                'San José del Potrero' => [
                    'San José del Potrero','El Cacao','El Guacamayo','La Cuesta',
                    'La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos',
                ],
                'San Luis' => [
                    'San Luis','El Cacao','El Guacamayo','La Cuesta','La Estancia',
                    'Las Vegas','Los Planes','San Antonio','San Marcos','Santa Cruz',
                ],
                'San Sebastián' => [
                    'San Sebastián','El Cacao','El Guacamayo','La Cuesta',
                    'La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos',
                ],
                'Siguatepeque' => [
                    'Siguatepeque','Barrio El Centro','Colonia El Paraíso','Colonia Kennedy',
                    'Colonia Los Pinos','El Cacao','El Guacamayo','La Cuesta',
                    'La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos','Santa Cruz',
                ],
                'Trinidad' => [
                    'Trinidad','El Cacao','El Guacamayo','La Cuesta','La Estancia',
                    'Las Vegas','Los Planes','San Antonio','San Marcos',
                ],
                'Villa de San Antonio' => [
                    'Villa de San Antonio','El Cacao','El Guacamayo','La Cuesta',
                    'La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos','Santa Cruz',
                ],
            ],

            // ══════════════════════════════════════════════
            'Copán' => [
                'Cabañas' => [
                    'Cabañas','El Cacao','El Guacamayo','La Cuesta','La Estancia',
                    'Las Vegas','Los Planes','San Antonio','San Marcos','Santa Cruz',
                ],
                'Concepción' => [
                    'Concepción','El Cacao','El Guacamayo','La Cuesta','La Estancia',
                    'Las Vegas','Los Planes','San Antonio','San Marcos',
                ],
                'Copán Ruinas' => [
                    'Copán Ruinas','El Castillo','El Jaral','La Entrada','La Pintada',
                    'Las Sepulturas','Los Limones','Nueva Esperanza','San Agustín',
                    'San Antonio','San Jerónimo','Santa Rita','Santa Rosa',
                ],
                'Corquín' => [
                    'Corquín','El Cacao','El Guacamayo','La Cuesta','La Estancia',
                    'Las Vegas','Los Planes','San Antonio','San Marcos','Santa Cruz',
                ],
                'Cucuyagua' => [
                    'Cucuyagua','El Cacao','El Guacamayo','La Cuesta','La Estancia',
                    'Las Vegas','Los Planes','San Antonio','San Marcos',
                ],
                'Dolores' => [
                    'Dolores','El Cacao','El Guacamayo','La Cuesta','La Estancia',
                    'Las Vegas','Los Planes','San Antonio','San Marcos',
                ],
                'Dulce Nombre' => [
                    'Dulce Nombre','El Cacao','El Guacamayo','La Cuesta','La Estancia',
                    'Las Vegas','Los Planes','San Antonio','San Marcos',
                ],
                'El Paraíso' => [
                    'El Paraíso','El Cacao','El Guacamayo','La Cuesta','La Estancia',
                    'Las Vegas','Los Planes','San Antonio','San Marcos',
                ],
                'Florida' => [
                    'Florida','El Cacao','El Guacamayo','La Cuesta','La Estancia',
                    'Las Vegas','Los Planes','San Antonio','San Marcos','Santa Cruz',
                ],
                'La Jigua' => [
                    'La Jigua','El Cacao','El Guacamayo','La Cuesta','La Estancia',
                    'Las Vegas','Los Planes','San Antonio','San Marcos',
                ],
                'La Unión' => [
                    'La Unión','El Cacao','El Guacamayo','La Cuesta','La Estancia',
                    'Las Vegas','Los Planes','San Antonio','San Marcos',
                ],
                'Nueva Arcadia' => [
                    'Nueva Arcadia','El Cacao','El Guacamayo','La Entrada','La Estancia',
                    'Las Vegas','Los Planes','San Antonio','San Marcos','Santa Cruz',
                ],
                'San Agustín' => [
                    'San Agustín','El Cacao','El Guacamayo','La Cuesta','La Estancia',
                    'Las Vegas','Los Planes','San Antonio','San Marcos',
                ],
                'San Antonio' => [
                    'San Antonio','El Cacao','El Guacamayo','La Cuesta','La Estancia',
                    'Las Vegas','Los Planes','San Marcos','Santa Cruz',
                ],
                'San Jerónimo' => [
                    'San Jerónimo','El Cacao','El Guacamayo','La Cuesta','La Estancia',
                    'Las Vegas','Los Planes','San Antonio','San Marcos',
                ],
                'San José' => [
                    'San José','El Cacao','El Guacamayo','La Cuesta','La Estancia',
                    'Las Vegas','Los Planes','San Antonio','San Marcos',
                ],
                'San Juan de Opoa' => [
                    'San Juan de Opoa','El Cacao','El Guacamayo','La Cuesta','La Estancia',
                    'Las Vegas','Los Planes','San Antonio','San Marcos',
                ],
                'San Nicolás' => [
                    'San Nicolás','El Cacao','El Guacamayo','La Cuesta','La Estancia',
                    'Las Vegas','Los Planes','San Antonio','San Marcos',
                ],
                'San Pedro' => [
                    'San Pedro','El Cacao','El Guacamayo','La Cuesta','La Estancia',
                    'Las Vegas','Los Planes','San Antonio','San Marcos',
                ],
                'San Rufo' => [
                    'San Rufo','El Cacao','El Guacamayo','La Cuesta','La Estancia',
                    'Las Vegas','Los Planes','San Antonio','San Marcos',
                ],
                'Santa Rita' => [
                    'Santa Rita','El Cacao','El Guacamayo','La Cuesta','La Estancia',
                    'Las Vegas','Los Planes','San Antonio','San Marcos','Santa Cruz',
                ],
                'Trinidad de Copán' => [
                    'Trinidad de Copán','El Cacao','El Guacamayo','La Cuesta','La Estancia',
                    'Las Vegas','Los Planes','San Antonio','San Marcos',
                ],
                'Veracruz' => [
                    'Veracruz','El Cacao','El Guacamayo','La Cuesta','La Estancia',
                    'Las Vegas','Los Planes','San Antonio','San Marcos',
                ],
            ],

            // ══════════════════════════════════════════════
            'Cortés' => [
                'Choloma' => [
                    'Choloma','Barrio El Centro','Colonia El Carmen','Colonia Kennedy',
                    'Colonia Las Palmas','Colonia Los Pinos','El Progreso','Flor del Campo',
                    'La Ceibita','La Lima','Las Brisas','Los Angeles','Nueva Esperanza',
                    'San Antonio','San Francisco','Santa Cruz',
                ],
                'La Lima' => [
                    'La Lima','Barrio El Centro','Colonia El Carmen','Colonia Kennedy',
                    'Colonia Las Palmas','El Paraíso','El Progreso','La Ceibita',
                    'Las Brisas','Los Angeles','Nueva Esperanza','San Antonio','San Francisco',
                ],
                'Omoa' => [
                    'Omoa','Barrio El Centro','Cuyamel','El Paraíso','Escondido',
                    'La Entrada','Las Brisas','Los Cerritos','Masca','Tegucigalpita',
                ],
                'Pimienta' => [
                    'Pimienta','El Bejucal','El Cacao','El Paraíso','La Ceibita',
                    'La Lima','Las Palmas','Los Angeles','Nueva Esperanza','San Francisco',
                ],
                'Potrerillos' => [
                    'Potrerillos','El Cacao','El Paraíso','El Progreso','La Ceibita',
                    'La Lima','Las Palmas','Los Angeles','Nueva Esperanza','San Francisco','Santa Cruz',
                ],
                'Puerto Cortés' => [
                    'Puerto Cortés','Barrio El Centro','Barrio El Reparto','Colonia El Carmen',
                    'Colonia Kennedy','Colonia Las Palmas','Colonia Los Pinos',
                    'El Paraíso','Omoa','San Antonio','San Francisco',
                ],
                'San Antonio de Cortés' => [
                    'San Antonio de Cortés','El Cacao','El Paraíso','El Progreso',
                    'La Ceibita','La Lima','Las Palmas','Los Angeles',
                    'Nueva Esperanza','San Francisco','Santa Cruz',
                ],
                'San Francisco de Yojoa' => [
                    'San Francisco de Yojoa','El Cacao','El Paraíso','El Progreso',
                    'La Ceibita','La Lima','Las Palmas','Los Angeles',
                    'Nueva Esperanza','San Antonio','Santa Cruz',
                ],
                'San Manuel' => [
                    'San Manuel','El Cacao','El Paraíso','El Progreso','La Ceibita',
                    'La Lima','Las Palmas','Los Angeles','Nueva Esperanza','San Francisco','Santa Cruz',
                ],
                'San Pedro Sula' => [
                    'Barrio El Centro','Barrio Guamilito','Barrio Las Acacias',
                    'Barrio Los Andes','Barrio Paz Barahona','Colonia Alameda',
                    'Colonia El Pedregal','Colonia Jardines del Valle',
                    'Colonia Los Andes','Colonia Miramontes','Colonia Río de Piedras',
                    'Colonia Trejo','La Ceibita','La Guardia','Las Crucitas',
                    'Los Tubos','Medina','Rivera Hernández','San Pedro Sula',
                    'Villa Olímpica',
                ],
                'Santa Cruz de Yojoa' => [
                    'Santa Cruz de Yojoa','El Cacao','El Paraíso','El Progreso',
                    'La Ceibita','La Lima','Las Palmas','Los Angeles',
                    'Nueva Esperanza','San Antonio','San Francisco',
                ],
                'Villanueva' => [
                    'Villanueva','Barrio El Centro','Colonia El Carmen',
                    'Colonia Kennedy','Colonia Las Palmas','El Cacao','El Paraíso',
                    'La Ceibita','La Lima','Las Brisas','Los Angeles',
                    'Nueva Esperanza','San Antonio','San Francisco',
                ],
            ],

            // ══════════════════════════════════════════════
            'El Paraíso' => [
                'Alauca' => [
                    'Alauca','El Cacao','El Guacamayo','La Cuesta','La Estancia',
                    'Las Vegas','Los Planes','San Antonio','San Marcos','Santa Cruz',
                ],
                'Danlí' => [
                    'Danlí','Barrio El Centro','Colonia El Paraíso','Colonia Kennedy',
                    'El Aguacate','El Edén','El Paraíso','El Quebracho','La Cuesta',
                    'La Estancia','Las Vegas','Los Planes','San Antonio','San Diego','San Marcos','Santa Cruz',
                ],
                'El Paraíso' => [
                    'El Paraíso','El Aguacate','El Cacao','El Guacamayo','El Jicaro',
                    'La Cuesta','La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos',
                ],
                'Güinope' => [
                    'Güinope','El Cacao','El Guacamayo','La Cuesta','La Estancia',
                    'Las Vegas','Los Planes','San Antonio','San Marcos',
                ],
                'Jacaleapa' => [
                    'Jacaleapa','El Cacao','El Guacamayo','La Cuesta','La Estancia',
                    'Las Vegas','Los Planes','San Antonio','San Marcos',
                ],
                'Liure' => [
                    'Liure','El Cacao','El Guacamayo','La Cuesta','La Estancia',
                    'Las Vegas','Los Planes','San Antonio','San Marcos',
                ],
                'Morocelí' => [
                    'Morocelí','El Cacao','El Guacamayo','La Cuesta','La Estancia',
                    'Las Vegas','Los Planes','San Antonio','San Marcos',
                ],
                'Oropolí' => [
                    'Oropolí','El Cacao','El Guacamayo','La Cuesta','La Estancia',
                    'Las Vegas','Los Planes','San Antonio','San Marcos',
                ],
                'Potrerillos' => [
                    'Potrerillos','El Cacao','El Guacamayo','La Cuesta','La Estancia',
                    'Las Vegas','Los Planes','San Antonio','San Marcos',
                ],
                'San Antonio de Flores' => [
                    'San Antonio de Flores','El Cacao','El Guacamayo','La Cuesta',
                    'La Estancia','Las Vegas','Los Planes','San Marcos','Santa Cruz',
                ],
                'San Lucas' => [
                    'San Lucas','El Cacao','El Guacamayo','La Cuesta','La Estancia',
                    'Las Vegas','Los Planes','San Antonio','San Marcos',
                ],
                'San Matías' => [
                    'San Matías','El Cacao','El Guacamayo','La Cuesta','La Estancia',
                    'Las Vegas','Los Planes','San Antonio','San Marcos',
                ],
                'Soledad' => [
                    'Soledad','El Cacao','El Guacamayo','La Cuesta','La Estancia',
                    'Las Vegas','Los Planes','San Antonio','San Marcos',
                ],
                'Teupasenti' => [
                    'Teupasenti','El Cacao','El Guacamayo','La Cuesta','La Estancia',
                    'Las Vegas','Los Planes','San Antonio','San Marcos','Santa Cruz',
                ],
                'Texiguat' => [
                    'Texiguat','El Cacao','El Guacamayo','La Cuesta','La Estancia',
                    'Las Vegas','Los Planes','San Antonio','San Marcos',
                ],
                'Trojes' => [
                    'Trojes','El Cacao','El Guacamayo','La Cuesta','La Estancia',
                    'Las Vegas','Los Planes','San Antonio','San Marcos','Santa Cruz',
                ],
                'Vado Ancho' => [
                    'Vado Ancho','El Cacao','El Guacamayo','La Cuesta','La Estancia',
                    'Las Vegas','Los Planes','San Antonio','San Marcos',
                ],
                'Yauyupe' => [
                    'Yauyupe','El Cacao','El Guacamayo','La Cuesta','La Estancia',
                    'Las Vegas','Los Planes','San Antonio','San Marcos',
                ],
                'Yuscarán' => [
                    'Yuscarán','El Cacao','El Guacamayo','La Cuesta','La Estancia',
                    'Las Vegas','Los Planes','San Antonio','San Marcos','Santa Cruz',
                ],
            ],

            // ══════════════════════════════════════════════
            'Francisco Morazán' => [
                'Alubarén' => [
                    'Alubarén','El Cacao','El Guacamayo','La Cuesta','La Estancia',
                    'Las Vegas','Los Planes','San Antonio','San Marcos','Santa Cruz',
                ],
                'Cedros' => [
                    'Cedros','El Cacao','El Guacamayo','La Cuesta','La Estancia',
                    'Las Vegas','Los Planes','San Antonio','San Marcos',
                ],
                'Curarén' => [
                    'Curarén','El Cacao','El Guacamayo','La Cuesta','La Estancia',
                    'Las Vegas','Los Planes','San Antonio','San Marcos',
                ],
                'Distrito Central' => [
                    'Tegucigalpa Centro','Barrio Abajo','Barrio Arriba','Barrio El Chile',
                    'Barrio El Manchen','Barrio Guacerique','Barrio La Granja',
                    'Barrio La Leona','Barrio Los Dolores','Barrio Buenos Aires',
                    'Colonia Alameda','Colonia El Prado','Colonia Florencia',
                    'Colonia Godoy','Colonia Hato de En Medio','Colonia Humuya',
                    'Colonia Juana Laínez','Colonia Kennedy','Colonia La Almeda',
                    'Colonia Lara','Colonia Las Minitas','Colonia Lomas del Guijarro',
                    'Colonia Loarque','Colonia Miraflores','Colonia Modelo',
                    'Colonia Palmira','Colonia Pedregal','Colonia Plutarco Munguía',
                    'Colonia San Carlos','Colonia San Miguel','Colonia Santa Inés',
                    'Comayagüela','El Batallón','El Pedregal','El Picacho',
                    'El Reparto','La Cañada','La Concordia','La Tigra',
                    'Las Crucitas','Los Pinos','Mercado Jacaleapa','Nueva Esperanza',
                    'Residencial Miraflores','Residencial Las Lomas','San Ignacio',
                    'Santa Lucía','Valle de Ángeles','Villa Olímpica',
                ],
                'El Porvenir' => [
                    'El Porvenir','El Cacao','El Guacamayo','La Cuesta','La Estancia',
                    'Las Vegas','Los Planes','San Antonio','San Marcos',
                ],
                'Guaimaca' => [
                    'Guaimaca','El Cacao','El Guacamayo','La Cuesta','La Estancia',
                    'Las Vegas','Los Planes','San Antonio','San Marcos','Santa Cruz',
                ],
                'La Libertad' => [
                    'La Libertad','El Cacao','El Guacamayo','La Cuesta','La Estancia',
                    'Las Vegas','Los Planes','San Antonio','San Marcos',
                ],
                'La Venta' => [
                    'La Venta','El Cacao','El Guacamayo','La Cuesta','La Estancia',
                    'Las Vegas','Los Planes','San Antonio','San Marcos',
                ],
                'Lepaterique' => [
                    'Lepaterique','El Cacao','El Guacamayo','La Cuesta','La Estancia',
                    'Las Vegas','Los Planes','San Antonio','San Marcos',
                ],
                'Maraita' => [
                    'Maraita','El Cacao','El Guacamayo','La Cuesta','La Estancia',
                    'Las Vegas','Los Planes','San Antonio','San Marcos',
                ],
                'Marale' => [
                    'Marale','El Cacao','El Guacamayo','La Cuesta','La Estancia',
                    'Las Vegas','Los Planes','San Antonio','San Marcos',
                ],
                'Nueva Armenia' => [
                    'Nueva Armenia','El Cacao','El Guacamayo','La Cuesta',
                    'La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos',
                ],
                'Ojojona' => [
                    'Ojojona','El Cacao','El Guacamayo','La Cuesta','La Estancia',
                    'Las Vegas','Los Planes','San Antonio','San Marcos','Santa Cruz',
                ],
                'Orica' => [
                    'Orica','El Cacao','El Guacamayo','La Cuesta','La Estancia',
                    'Las Vegas','Los Planes','San Antonio','San Marcos',
                ],
                'Reitoca' => [
                    'Reitoca','El Cacao','El Guacamayo','La Cuesta','La Estancia',
                    'Las Vegas','Los Planes','San Antonio','San Marcos',
                ],
                'Sabanagrande' => [
                    'Sabanagrande','El Cacao','El Guacamayo','La Cuesta','La Estancia',
                    'Las Vegas','Los Planes','San Antonio','San Marcos','Santa Cruz',
                ],
                'San Antonio de Oriente' => [
                    'San Antonio de Oriente','El Cacao','El Guacamayo','La Cuesta',
                    'La Estancia','Las Vegas','Los Planes','San Marcos','Santa Cruz',
                ],
                'San Buenaventura' => [
                    'San Buenaventura','El Cacao','El Guacamayo','La Cuesta',
                    'La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos',
                ],
                'San Ignacio' => [
                    'San Ignacio','El Cacao','El Guacamayo','La Cuesta','La Estancia',
                    'Las Vegas','Los Planes','San Antonio','San Marcos',
                ],
                'San Juan de Flores' => [
                    'San Juan de Flores','El Cacao','El Guacamayo','La Cuesta',
                    'La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos',
                ],
                'San Miguelito' => [
                    'San Miguelito','El Cacao','El Guacamayo','La Cuesta',
                    'La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos',
                ],
                'Santa Ana' => [
                    'Santa Ana','El Cacao','El Guacamayo','La Cuesta','La Estancia',
                    'Las Vegas','Los Planes','San Antonio','San Marcos',
                ],
                'Santa Lucía' => [
                    'Santa Lucía','El Cacao','El Guacamayo','La Cuesta','La Estancia',
                    'Las Vegas','Los Planes','San Antonio','San Marcos',
                ],
                'Talanga' => [
                    'Talanga','El Cacao','El Guacamayo','La Cuesta','La Estancia',
                    'Las Vegas','Los Planes','San Antonio','San Marcos','Santa Cruz',
                ],
                'Tatumbla' => [
                    'Tatumbla','El Cacao','El Guacamayo','La Cuesta','La Estancia',
                    'Las Vegas','Los Planes','San Antonio','San Marcos',
                ],
                'Valle de Ángeles' => [
                    'Valle de Ángeles','El Cacao','El Guacamayo','La Cuesta',
                    'La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos',
                ],
                'Villa de San Francisco' => [
                    'Villa de San Francisco','El Cacao','El Guacamayo','La Cuesta',
                    'La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos',
                ],
                'Vallecillo' => [
                    'Vallecillo','El Cacao','El Guacamayo','La Cuesta','La Estancia',
                    'Las Vegas','Los Planes','San Antonio','San Marcos',
                ],
            ],

            // ══════════════════════════════════════════════
            'Gracias a Dios' => [
                'Ahuas' => [
                    'Ahuas','Auka','Barra Patuca','Brus Laguna','Cauquira',
                    'Cocobila','Kaurkira','Mokorón','Rawa','Tikirí','Urus Tingni',
                ],
                'Brus Laguna' => [
                    'Brus Laguna','Auya Sirpe','Barra Patuca','Bodega','Cabecera',
                    'Cauquira','Isla Grande','Kaska','Mokorón','Palacios','Tasbapauni',
                ],
                'Juan Francisco Bulnes' => [
                    'Juan Francisco Bulnes','Batalla','Barra Río Plátano',
                    'Cocobila','Kuri','Palacios','Plaplaya','Raistá','Río Plátano',
                ],
                'Puerto Lempira' => [
                    'Puerto Lempira','Aurabila','Barrio El Centro','Cauquira',
                    'Kaurkira','Leimus','Mocorón','Rus Rus','Tánsin','Uhri',
                ],
                'Ramón Villeda Morales' => [
                    'Ramón Villeda Morales','Barra Patuca','Bodega','Kabata',
                    'Kokota','La Trinidad','Palanka','Singre',
                ],
                'Wampusirpi' => [
                    'Wampusirpi','Belén','Cambla','El Cayo','Kaupén',
                    'Krautara','Río Patuca','San Pedro',
                ],
            ],

            // ══════════════════════════════════════════════
            'Intibucá' => [
                'Camasca' => [
                    'Camasca','El Cacao','El Guacamayo','La Cuesta','La Estancia',
                    'Las Vegas','Los Planes','San Antonio','San Marcos',
                ],
                'Colomoncagua' => [
                    'Colomoncagua','El Cacao','El Guacamayo','La Cuesta','La Estancia',
                    'Las Vegas','Los Planes','San Antonio','San Marcos',
                ],
                'Concepción' => [
                    'Concepción','El Cacao','El Guacamayo','La Cuesta','La Estancia',
                    'Las Vegas','Los Planes','San Antonio','San Marcos',
                ],
                'Dolores' => [
                    'Dolores','El Cacao','El Guacamayo','La Cuesta','La Estancia',
                    'Las Vegas','Los Planes','San Antonio','San Marcos',
                ],
                'Intibucá' => [
                    'Intibucá','El Cacao','El Guacamayo','La Cuesta','La Estancia',
                    'Las Vegas','Los Planes','San Antonio','San Marcos','Santa Cruz',
                ],
                'Jesús de Otoro' => [
                    'Jesús de Otoro','El Cacao','El Guacamayo','La Cuesta','La Estancia',
                    'Las Vegas','Los Planes','San Antonio','San Marcos','Santa Cruz',
                ],
                'La Esperanza' => [
                    'La Esperanza','Barrio El Centro','Colonia El Paraíso','El Cacao',
                    'El Guacamayo','La Cuesta','La Estancia','Las Vegas',
                    'Los Planes','San Antonio','San Marcos','Santa Cruz',
                ],
                'Magdalena' => [
                    'Magdalena','El Cacao','El Guacamayo','La Cuesta','La Estancia',
                    'Las Vegas','Los Planes','San Antonio','San Marcos',
                ],
                'Masaguara' => [
                    'Masaguara','El Cacao','El Guacamayo','La Cuesta','La Estancia',
                    'Las Vegas','Los Planes','San Antonio','San Marcos',
                ],
                'San Antonio' => [
                    'San Antonio','El Cacao','El Guacamayo','La Cuesta','La Estancia',
                    'Las Vegas','Los Planes','San Marcos','Santa Cruz',
                ],
                'San Francisco de Opalaca' => [
                    'San Francisco de Opalaca','El Cacao','El Guacamayo','La Cuesta',
                    'La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos',
                ],
                'San Isidro' => [
                    'San Isidro','El Cacao','El Guacamayo','La Cuesta','La Estancia',
                    'Las Vegas','Los Planes','San Antonio','San Marcos',
                ],
                'San Juan' => [
                    'San Juan','El Cacao','El Guacamayo','La Cuesta','La Estancia',
                    'Las Vegas','Los Planes','San Antonio','San Marcos',
                ],
                'San Marcos de la Sierra' => [
                    'San Marcos de la Sierra','El Cacao','El Guacamayo','La Cuesta',
                    'La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos',
                ],
                'San Miguelito' => [
                    'San Miguelito','El Cacao','El Guacamayo','La Cuesta','La Estancia',
                    'Las Vegas','Los Planes','San Antonio','San Marcos',
                ],
                'Santa Lucía' => [
                    'Santa Lucía','El Cacao','El Guacamayo','La Cuesta','La Estancia',
                    'Las Vegas','Los Planes','San Antonio','San Marcos',
                ],
                'Yamaranguila' => [
                    'Yamaranguila','El Cacao','El Guacamayo','La Cuesta','La Estancia',
                    'Las Vegas','Los Planes','San Antonio','San Marcos','Santa Cruz',
                ],
            ],

            // ══════════════════════════════════════════════
            'Islas de la Bahía' => [
                'Guanaja' => [
                    'Guanaja','Bonacca (Isla del Cayo)','Mangrove Bight','Savannah Bight',
                    'North East Bight','Sandy Bay',
                ],
                'José Santos Guardiola' => [
                    'José Santos Guardiola','Brick Bay','Camp Bay','Fantasy Island',
                    'Jonesville','Oak Ridge','Paya Bay','Politilly Bight',
                ],
                'Roatán' => [
                    'Roatán','Barrio El Centro','Barrio Río','Coxen Hole','French Harbour',
                    'French Key','Gibson Bight','Gravel Bay','Los Fuertes','Palmetto Bay',
                    'Sandy Bay','Serpent Trough','West Bay','West End',
                ],
                'Utila' => [
                    'Utila','Cayo East Harbour','East Harbour','Iron Bound','Pumpkin Hill',
                    'Stain Creek','Stuart Hill',
                ],
            ],

            // ══════════════════════════════════════════════
            'La Paz' => [
                'Aguanqueterique' => [
                    'Aguanqueterique','El Cacao','El Guacamayo','La Cuesta','La Estancia',
                    'Las Vegas','Los Planes','San Antonio','San Marcos',
                ],
                'Cabañas' => [
                    'Cabañas','El Cacao','El Guacamayo','La Cuesta','La Estancia',
                    'Las Vegas','Los Planes','San Antonio','San Marcos',
                ],
                'Cane' => [
                    'Cane','El Cacao','El Guacamayo','La Cuesta','La Estancia',
                    'Las Vegas','Los Planes','San Antonio','San Marcos',
                ],
                'Chinacla' => [
                    'Chinacla','El Cacao','El Guacamayo','La Cuesta','La Estancia',
                    'Las Vegas','Los Planes','San Antonio','San Marcos',
                ],
                'Guajiquiro' => [
                    'Guajiquiro','El Cacao','El Guacamayo','La Cuesta','La Estancia',
                    'Las Vegas','Los Planes','San Antonio','San Marcos',
                ],
                'La Paz' => [
                    'La Paz','Barrio El Centro','Colonia El Paraíso','El Cacao',
                    'El Guacamayo','La Cuesta','La Estancia','Las Vegas',
                    'Los Planes','San Antonio','San Marcos','Santa Cruz',
                ],
                'Lauterique' => [
                    'Lauterique','El Cacao','El Guacamayo','La Cuesta','La Estancia',
                    'Las Vegas','Los Planes','San Antonio','San Marcos',
                ],
                'Marcala' => [
                    'Marcala','Barrio El Centro','El Cacao','El Guacamayo',
                    'La Cuesta','La Estancia','Las Vegas','Los Planes',
                    'San Antonio','San Marcos','Santa Cruz',
                ],
                'Mercedes de Oriente' => [
                    'Mercedes de Oriente','El Cacao','El Guacamayo','La Cuesta',
                    'La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos',
                ],
                'Opatoro' => [
                    'Opatoro','El Cacao','El Guacamayo','La Cuesta','La Estancia',
                    'Las Vegas','Los Planes','San Antonio','San Marcos',
                ],
                'San Antonio del Norte' => [
                    'San Antonio del Norte','El Cacao','El Guacamayo','La Cuesta',
                    'La Estancia','Las Vegas','Los Planes','San Marcos','Santa Cruz',
                ],
                'San Juan' => [
                    'San Juan','El Cacao','El Guacamayo','La Cuesta','La Estancia',
                    'Las Vegas','Los Planes','San Antonio','San Marcos',
                ],
                'San Miguel' => [
                    'San Miguel','El Cacao','El Guacamayo','La Cuesta','La Estancia',
                    'Las Vegas','Los Planes','San Antonio','San Marcos',
                ],
                'San Pedro de Tutule' => [
                    'San Pedro de Tutule','El Cacao','El Guacamayo','La Cuesta',
                    'La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos',
                ],
                'Santa Ana' => [
                    'Santa Ana','El Cacao','El Guacamayo','La Cuesta','La Estancia',
                    'Las Vegas','Los Planes','San Antonio','San Marcos',
                ],
                'Santa Elena' => [
                    'Santa Elena','El Cacao','El Guacamayo','La Cuesta','La Estancia',
                    'Las Vegas','Los Planes','San Antonio','San Marcos',
                ],
                'Santa María' => [
                    'Santa María','El Cacao','El Guacamayo','La Cuesta','La Estancia',
                    'Las Vegas','Los Planes','San Antonio','San Marcos',
                ],
                'Santiago de Puringla' => [
                    'Santiago de Puringla','El Cacao','El Guacamayo','La Cuesta',
                    'La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos',
                ],
                'Yarula' => [
                    'Yarula','El Cacao','El Guacamayo','La Cuesta','La Estancia',
                    'Las Vegas','Los Planes','San Antonio','San Marcos',
                ],
            ],

            // ══════════════════════════════════════════════
            'Lempira' => [
                'Belén' => ['Belén','El Cacao','El Guacamayo','La Cuesta','La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos'],
                'Candelaria' => ['Candelaria','El Cacao','El Guacamayo','La Cuesta','La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos'],
                'Cololaca' => ['Cololaca','El Cacao','El Guacamayo','La Cuesta','La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos'],
                'Erandique' => ['Erandique','El Cacao','El Guacamayo','La Cuesta','La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos','Santa Cruz'],
                'Gracias' => [
                    'Gracias','Barrio El Centro','Colonia El Paraíso','El Cacao',
                    'El Guacamayo','La Campa','La Cuesta','Las Vegas',
                    'Los Planes','San Antonio','San Marcos','Santa Cruz',
                ],
                'Gualcince' => ['Gualcince','El Cacao','El Guacamayo','La Cuesta','La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos'],
                'Guarita' => ['Guarita','El Cacao','El Guacamayo','La Cuesta','La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos'],
                'La Campa' => ['La Campa','El Cacao','El Guacamayo','La Cuesta','La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos'],
                'La Iguala' => ['La Iguala','El Cacao','El Guacamayo','La Cuesta','La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos'],
                'La Unión' => ['La Unión','El Cacao','El Guacamayo','La Cuesta','La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos'],
                'La Virtud' => ['La Virtud','El Cacao','El Guacamayo','La Cuesta','La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos'],
                'Las Flores' => ['Las Flores','El Cacao','El Guacamayo','La Cuesta','La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos'],
                'Lepaera' => ['Lepaera','El Cacao','El Guacamayo','La Cuesta','La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos','Santa Cruz'],
                'Mapulaca' => ['Mapulaca','El Cacao','El Guacamayo','La Cuesta','La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos'],
                'Piraera' => ['Piraera','El Cacao','El Guacamayo','La Cuesta','La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos'],
                'San Andrés' => ['San Andrés','El Cacao','El Guacamayo','La Cuesta','La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos'],
                'San Francisco' => ['San Francisco','El Cacao','El Guacamayo','La Cuesta','La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos'],
                'San Juan de Guarita' => ['San Juan de Guarita','El Cacao','El Guacamayo','La Cuesta','La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos'],
                'San Manuel de Colohete' => ['San Manuel de Colohete','El Cacao','El Guacamayo','La Cuesta','La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos'],
                'San Marcos de Caiquín' => ['San Marcos de Caiquín','El Cacao','El Guacamayo','La Cuesta','La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos'],
                'San Rafael' => ['San Rafael','El Cacao','El Guacamayo','La Cuesta','La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos'],
                'San Sebastián' => ['San Sebastián','El Cacao','El Guacamayo','La Cuesta','La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos'],
                'Santa Cruz' => ['Santa Cruz','El Cacao','El Guacamayo','La Cuesta','La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos'],
                'Talgua' => ['Talgua','El Cacao','El Guacamayo','La Cuesta','La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos'],
                'Tambla' => ['Tambla','El Cacao','El Guacamayo','La Cuesta','La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos'],
                'Tomalá' => ['Tomalá','El Cacao','El Guacamayo','La Cuesta','La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos'],
                'Valladolid' => ['Valladolid','El Cacao','El Guacamayo','La Cuesta','La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos'],
                'Virginia' => ['Virginia','El Cacao','El Guacamayo','La Cuesta','La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos'],
            ],

            // ══════════════════════════════════════════════
            'Ocotepeque' => [
                'Belén Gualcho' => ['Belén Gualcho','El Cacao','El Guacamayo','La Cuesta','La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos'],
                'Concepción' => ['Concepción','El Cacao','El Guacamayo','La Cuesta','La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos'],
                'Dolores Merendón' => ['Dolores Merendón','El Cacao','El Guacamayo','La Cuesta','La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos'],
                'Fraternidad' => ['Fraternidad','El Cacao','El Guacamayo','La Cuesta','La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos'],
                'La Encarnación' => ['La Encarnación','El Cacao','El Guacamayo','La Cuesta','La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos'],
                'La Labor' => ['La Labor','El Cacao','El Guacamayo','La Cuesta','La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos'],
                'Lucerna' => ['Lucerna','El Cacao','El Guacamayo','La Cuesta','La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos'],
                'Mercedes' => ['Mercedes','El Cacao','El Guacamayo','La Cuesta','La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos'],
                'Ocotepeque' => ['Ocotepeque','Barrio El Centro','El Cacao','El Guacamayo','La Cuesta','La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos','Santa Cruz'],
                'San Fernando' => ['San Fernando','El Cacao','El Guacamayo','La Cuesta','La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos'],
                'San Francisco del Valle' => ['San Francisco del Valle','El Cacao','El Guacamayo','La Cuesta','La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos'],
                'San Jorge' => ['San Jorge','El Cacao','El Guacamayo','La Cuesta','La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos'],
                'San Marcos' => ['San Marcos','El Cacao','El Guacamayo','La Cuesta','La Estancia','Las Vegas','Los Planes','San Antonio','Santa Cruz'],
                'Santa Fe' => ['Santa Fe','El Cacao','El Guacamayo','La Cuesta','La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos'],
                'Sensenti' => ['Sensenti','El Cacao','El Guacamayo','La Cuesta','La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos'],
                'Sinuapa' => ['Sinuapa','El Cacao','El Guacamayo','La Cuesta','La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos'],
            ],

            // ══════════════════════════════════════════════
            'Olancho' => [
                'Campamento' => ['Campamento','El Cacao','El Guacamayo','La Cuesta','La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos','Santa Cruz'],
                'Catacamas' => [
                    'Catacamas','Barrio El Centro','Colonia Kennedy','El Cacao',
                    'El Guacamayo','Juticalpa','La Cuesta','La Estancia',
                    'Las Vegas','Los Planes','Ojo de Agua','San Antonio','San Marcos','Santa Cruz',
                ],
                'Concordia' => ['Concordia','El Cacao','El Guacamayo','La Cuesta','La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos'],
                'Dulce Nombre de Culmí' => ['Dulce Nombre de Culmí','El Cacao','El Guacamayo','La Cuesta','La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos'],
                'El Rosario' => ['El Rosario','El Cacao','El Guacamayo','La Cuesta','La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos'],
                'Esquipulas del Norte' => ['Esquipulas del Norte','El Cacao','El Guacamayo','La Cuesta','La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos'],
                'Gualaco' => ['Gualaco','El Cacao','El Guacamayo','La Cuesta','La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos','Santa Cruz'],
                'Guarizama' => ['Guarizama','El Cacao','El Guacamayo','La Cuesta','La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos'],
                'Guata' => ['Guata','El Cacao','El Guacamayo','La Cuesta','La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos'],
                'Juticalpa' => [
                    'Juticalpa','Barrio El Centro','Colonia Kennedy','El Cacao',
                    'El Guacamayo','La Cuesta','La Estancia','Las Vegas',
                    'Los Planes','San Antonio','San Marcos','Santa Cruz',
                ],
                'La Unión' => ['La Unión','El Cacao','El Guacamayo','La Cuesta','La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos'],
                'Lafitte' => ['Lafitte','El Cacao','El Guacamayo','La Cuesta','La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos'],
                'Mangulile' => ['Mangulile','El Cacao','El Guacamayo','La Cuesta','La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos'],
                'Manto' => ['Manto','El Cacao','El Guacamayo','La Cuesta','La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos'],
                'Patuca' => ['Patuca','El Cacao','El Guacamayo','La Cuesta','La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos'],
                'Salamá' => ['Salamá','El Cacao','El Guacamayo','La Cuesta','La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos'],
                'San Carlos' => ['San Carlos','El Cacao','El Guacamayo','La Cuesta','La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos'],
                'San Esteban' => ['San Esteban','El Cacao','El Guacamayo','La Cuesta','La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos','Santa Cruz'],
                'San Francisco de Becerra' => ['San Francisco de Becerra','El Cacao','El Guacamayo','La Cuesta','La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos'],
                'San Francisco de la Paz' => ['San Francisco de la Paz','El Cacao','El Guacamayo','La Cuesta','La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos','Santa Cruz'],
                'Santa María del Real' => ['Santa María del Real','El Cacao','El Guacamayo','La Cuesta','La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos'],
                'Silca' => ['Silca','El Cacao','El Guacamayo','La Cuesta','La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos'],
                'Yocón' => ['Yocón','El Cacao','El Guacamayo','La Cuesta','La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos'],
            ],

            // ══════════════════════════════════════════════
            'Santa Bárbara' => [
                'Arada' => ['Arada','El Cacao','El Guacamayo','La Cuesta','La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos'],
                'Atima' => ['Atima','El Cacao','El Guacamayo','La Cuesta','La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos'],
                'Azacualpa' => ['Azacualpa','El Cacao','El Guacamayo','La Cuesta','La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos'],
                'Ceguaca' => ['Ceguaca','El Cacao','El Guacamayo','La Cuesta','La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos'],
                'Chinda' => ['Chinda','El Cacao','El Guacamayo','La Cuesta','La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos'],
                'Concepción del Norte' => ['Concepción del Norte','El Cacao','El Guacamayo','La Cuesta','La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos'],
                'Concepción del Sur' => ['Concepción del Sur','El Cacao','El Guacamayo','La Cuesta','La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos'],
                'El Níspero' => ['El Níspero','El Cacao','El Guacamayo','La Cuesta','La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos'],
                'Gualala' => ['Gualala','El Cacao','El Guacamayo','La Cuesta','La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos'],
                'Ilama' => ['Ilama','El Cacao','El Guacamayo','La Cuesta','La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos'],
                'Las Vegas' => ['Las Vegas','El Cacao','El Guacamayo','La Cuesta','La Estancia','Los Planes','San Antonio','San Marcos','Santa Cruz'],
                'Macuelizo' => ['Macuelizo','El Cacao','El Guacamayo','La Cuesta','La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos'],
                'Naranjito' => ['Naranjito','El Cacao','El Guacamayo','La Cuesta','La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos'],
                'Nueva Frontera' => ['Nueva Frontera','El Cacao','El Guacamayo','La Cuesta','La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos'],
                'Petoa' => ['Petoa','El Cacao','El Guacamayo','La Cuesta','La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos'],
                'Protección' => ['Protección','El Cacao','El Guacamayo','La Cuesta','La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos','Santa Cruz'],
                'Quimistán' => ['Quimistán','El Cacao','El Guacamayo','La Cuesta','La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos','Santa Cruz'],
                'San Francisco de Ojuera' => ['San Francisco de Ojuera','El Cacao','El Guacamayo','La Cuesta','La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos'],
                'San José de Colinas' => ['San José de Colinas','El Cacao','El Guacamayo','La Cuesta','La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos','Santa Cruz'],
                'San Luis' => ['San Luis','El Cacao','El Guacamayo','La Cuesta','La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos'],
                'San Marcos' => ['San Marcos','El Cacao','El Guacamayo','La Cuesta','La Estancia','Las Vegas','Los Planes','San Antonio','Santa Cruz'],
                'San Nicolás' => ['San Nicolás','El Cacao','El Guacamayo','La Cuesta','La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos'],
                'San Pedro Zacapa' => ['San Pedro Zacapa','El Cacao','El Guacamayo','La Cuesta','La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos'],
                'San Vicente Centenario' => ['San Vicente Centenario','El Cacao','El Guacamayo','La Cuesta','La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos'],
                'Santa Bárbara' => [
                    'Santa Bárbara','Barrio El Centro','Colonia Kennedy','El Cacao',
                    'El Guacamayo','La Cuesta','La Estancia','Las Vegas',
                    'Los Planes','San Antonio','San Marcos','Santa Cruz',
                ],
                'Santa Rita' => ['Santa Rita','El Cacao','El Guacamayo','La Cuesta','La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos'],
                'Trinidad' => ['Trinidad','El Cacao','El Guacamayo','La Cuesta','La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos'],
                'Unión' => ['Unión','El Cacao','El Guacamayo','La Cuesta','La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos'],
            ],

            // ══════════════════════════════════════════════
            'Valle' => [
                'Alianza' => ['Alianza','El Cacao','El Guacamayo','La Cuesta','La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos'],
                'Amapala' => ['Amapala','El Cacao','El Guacamayo','Isla del Tigre','La Cuesta','Las Vegas','Los Planes','San Antonio','San Lorenzo'],
                'Aramecina' => ['Aramecina','El Cacao','El Guacamayo','La Cuesta','La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos'],
                'Caridad' => ['Caridad','El Cacao','El Guacamayo','La Cuesta','La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos'],
                'Goascorán' => ['Goascorán','El Cacao','El Guacamayo','La Cuesta','La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos'],
                'Langue' => ['Langue','El Cacao','El Guacamayo','La Cuesta','La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos','Santa Cruz'],
                'Nacaome' => [
                    'Nacaome','Barrio El Centro','El Cacao','El Guacamayo',
                    'La Cuesta','La Estancia','Las Vegas','Los Planes',
                    'San Antonio','San Marcos','Santa Cruz',
                ],
                'San Francisco de Coray' => ['San Francisco de Coray','El Cacao','El Guacamayo','La Cuesta','La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos'],
                'San Lorenzo' => [
                    'San Lorenzo','Barrio El Centro','Colonia El Paraíso','El Cacao',
                    'El Guacamayo','La Cuesta','La Estancia','Las Vegas',
                    'Los Planes','San Antonio','San Marcos','Santa Cruz',
                ],
            ],

            // ══════════════════════════════════════════════
            'Yoro' => [
                'Arenal' => ['Arenal','El Cacao','El Guacamayo','La Cuesta','La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos'],
                'El Negrito' => [
                    'El Negrito','El Cacao','El Guacamayo','La Cuesta','La Estancia',
                    'Las Vegas','Los Planes','San Antonio','San Marcos','Santa Cruz',
                ],
                'El Progreso' => [
                    'El Progreso','Barrio El Centro','Colonia El Paraíso','Colonia Kennedy',
                    'El Cacao','El Guacamayo','La Ceibita','La Cuesta',
                    'La Lima','Las Vegas','Los Planes','San Antonio','San Marcos','Santa Cruz',
                ],
                'Jocón' => ['Jocón','El Cacao','El Guacamayo','La Cuesta','La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos'],
                'Morazán' => [
                    'Morazán','El Cacao','El Guacamayo','La Cuesta','La Estancia',
                    'Las Vegas','Los Planes','San Antonio','San Marcos','Santa Cruz',
                ],
                'Olanchito' => [
                    'Olanchito','Barrio El Centro','El Cacao','El Guacamayo',
                    'La Cuesta','La Estancia','Las Vegas','Los Planes',
                    'San Antonio','San Marcos','Santa Cruz',
                ],
                'Sabanarredonda' => ['Sabanarredonda','El Cacao','El Guacamayo','La Cuesta','La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos'],
                'Sulaco' => ['Sulaco','El Cacao','El Guacamayo','La Cuesta','La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos'],
                'Victoria' => ['Victoria','El Cacao','El Guacamayo','La Cuesta','La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos'],
                'Yorito' => ['Yorito','El Cacao','El Guacamayo','La Cuesta','La Estancia','Las Vegas','Los Planes','San Antonio','San Marcos'],
                'Yoro' => [
                    'Yoro','Barrio El Centro','El Cacao','El Guacamayo',
                    'La Cuesta','La Estancia','Las Vegas','Los Planes',
                    'San Antonio','San Marcos','Santa Cruz',
                ],
            ],
        ];

        $inserted = 0;
        $skipped  = 0;

        foreach ($data as $depNombre => $municipios) {
            $dep = DB::table('departamentos')->where('nombre', $depNombre)->first();
            if (!$dep) {
                $this->command->warn("Departamento no encontrado: $depNombre");
                continue;
            }

            foreach ($municipios as $munNombre => $aldeas) {
                $mun = DB::table('municipios')
                    ->where('departamento_id', $dep->id)
                    ->where('nombre', $munNombre)
                    ->first();

                if (!$mun) {
                    $this->command->warn("  Municipio no encontrado: $munNombre ($depNombre)");
                    $skipped++;
                    continue;
                }

                foreach ($aldeas as $aldea) {
                    $exists = DB::table('localidades')
                        ->where('municipio_id', $mun->id)
                        ->where('nombre', $aldea)
                        ->exists();

                    if (!$exists) {
                        DB::table('localidades')->insert([
                            'municipio_id' => $mun->id,
                            'nombre'       => $aldea,
                            'tipo'         => 'aldea',
                            'created_at'   => $now,
                            'updated_at'   => $now,
                        ]);
                        $inserted++;
                    }
                }
            }
        }

        $this->command->info("Localidades insertadas: $inserted | Omitidas (ya existían): $skipped");
    }
}
