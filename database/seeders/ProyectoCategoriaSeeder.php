<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProyectoCategoria;

class ProyectoCategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categorias = [
            'Tecnología',
            'Arte',
            'Música',
            'Cine y Video',
            'Juegos (Videojuegos y Juegos de mesa)',
            'Emprendimiento / Startups',
            'Educación',
            'Salud y Bienestar',
            'Medio Ambiente',
            'Ciencias',
            'Comunidad / Impacto social',
            'Deportes',
            'Diseño',
            'Literatura',
            'Fotografía',
            'Moda',
            'Gastronomía',
            'ONG / Caridad',
            'Animales',
            'Arquitectura',
        ];

        foreach ($categorias as $nombre) {
            ProyectoCategoria::firstOrCreate(['nombre' => $nombre]);
        }
    }
}
