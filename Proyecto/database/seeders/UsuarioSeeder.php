<?php

namespace Database\Seeders;

use App\Models\Alumno;
use App\Models\Profesor;
use App\Models\Usuario;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsuarioSeeder extends Seeder
{
    public function run(): void
    {
        // ── PROFESORES ───────────────────────────────────────────────────────
        $profesoresData = [
            ['nombre' => 'Carlos',  'apellidos' => 'García López',   'email' => 'profesor1@gmail.com'],
            ['nombre' => 'Lucía',   'apellidos' => 'Martínez Ruiz',  'email' => 'profesor2@gmail.com'],
        ];

        $profesores = [];
        foreach ($profesoresData as $data) {
            $usuario = Usuario::create([
                'nombre'    => $data['nombre'],
                'apellidos' => $data['apellidos'],
                'email'     => $data['email'],
                'password'  => Hash::make('1234'),
                'rol'       => 'profesor',
            ]);

            $profesor = Profesor::create([
                'id_profesor' => $usuario->id_usuario,
            ]);

            $profesores[] = $profesor;
        }

        // ── ALUMNOS ──────────────────────────────────────────────────────────
        $alumnosData = [
            ['nombre' => 'Ana',      'apellidos' => 'Fernández Torres',  'email' => 'alumno1@gmail.com'],
            ['nombre' => 'Marco',    'apellidos' => 'Rodríguez Vega',    'email' => 'alumno2@gmail.com'],
            ['nombre' => 'Sofía',    'apellidos' => 'López Sánchez',     'email' => 'alumno3@gmail.com'],
            ['nombre' => 'Javier',   'apellidos' => 'Moreno Castillo',   'email' => 'alumno4@gmail.com'],
            ['nombre' => 'Paula',    'apellidos' => 'Jiménez Blanco',    'email' => 'alumno5@gmail.com'],
        ];

        foreach ($alumnosData as $data) {
            $usuario = Usuario::create([
                'nombre'    => $data['nombre'],
                'apellidos' => $data['apellidos'],
                'email'     => $data['email'],
                'password'  => Hash::make('1234'),
                'rol'       => 'alumno',
            ]);

            Alumno::create([
                'id_alumno' => $usuario->id_usuario,
            ]);
        }
    }
}
