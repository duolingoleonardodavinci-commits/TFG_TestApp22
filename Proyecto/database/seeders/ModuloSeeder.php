<?php

namespace Database\Seeders;

use App\Models\Modulo;
use App\Models\Profesor;
use Illuminate\Database\Seeder;

class ModuloSeeder extends Seeder
{
    public function run(): void
    {
        // Obtenemos los profesores por email para no depender del ID
        $profesores = Profesor::with('usuario')->get();
        $profCarlos = $profesores->first(fn($p) => $p->usuario->email === 'profesor1@gmail.com');
        $profLucia  = $profesores->first(fn($p) => $p->usuario->email === 'profesor2@gmail.com');

        $modulos = [
            [
                'ciclo'               => 'DAW',
                'modulo'              => 'Programación',
                'color'               => '#4F46E5',
                'idioma'              => 'es',
                'clave_matriculacion' => '1234',
                'id_profesor'         => $profCarlos->id_profesor,
            ],
            [
                'ciclo'               => 'DAW',
                'modulo'              => 'Bases de Datos',
                'color'               => '#0891B2',
                'idioma'              => 'es',
                'clave_matriculacion' => '1234',
                'id_profesor'         => $profCarlos->id_profesor,
            ],
            [
                'ciclo'               => 'DAW',
                'modulo'              => 'Desarrollo Web',
                'color'               => '#16A34A',
                'idioma'              => 'es',
                'clave_matriculacion' => '1234',
                'id_profesor'         => $profCarlos->id_profesor,
            ],
            [
                'ciclo'               => 'DAM',
                'modulo'              => 'Sistemas Operativos',
                'color'               => '#DC2626',
                'idioma'              => 'es',
                'clave_matriculacion' => '1234',
                'id_profesor'         => $profLucia->id_profesor,
            ],
            [
                'ciclo'               => 'DAM',
                'modulo'              => 'Entornos de Desarrollo',
                'color'               => '#D97706',
                'idioma'              => 'es',
                'clave_matriculacion' => '1234',
                'id_profesor'         => $profLucia->id_profesor,
            ],
        ];

        foreach ($modulos as $data) {
            Modulo::create($data);
        }
    }
}
