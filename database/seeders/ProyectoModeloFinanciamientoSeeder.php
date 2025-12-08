<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProyectoModeloFinanciamiento;

class ProyectoModeloFinanciamientoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $modelos = [
            'Flexible',
            'Todo o nada',
            'Continuo',
        ];

        foreach ($modelos as $nombre) {
            ProyectoModeloFinanciamiento::firstOrCreate(['nombre' => $nombre]);
        }
    }
}
