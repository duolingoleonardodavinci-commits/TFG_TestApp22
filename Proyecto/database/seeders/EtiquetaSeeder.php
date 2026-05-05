<?php

namespace Database\Seeders;

use App\Models\Etiqueta;
use Illuminate\Database\Seeder;

class EtiquetaSeeder extends Seeder
{
    public function run(): void
    {
        $etiquetas = [
            // Programación
            'variables',
            'funciones',
            'poo',
            'arrays',
            'bucles',
            // Bases de datos
            'sql',
            'joins',
            'normalizacion',
            // Web
            'html',
            'css',
            'javascript',
            // Sistemas
            'linux',
            'comandos',
            // Entornos
            'git',
            'ide',
        ];

        foreach ($etiquetas as $nombre) {
            Etiqueta::create(['nombre' => $nombre]);
        }
    }
}
