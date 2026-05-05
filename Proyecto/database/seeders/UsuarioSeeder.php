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
            ['nombre' => 'Carlos',  'apellidos' => 'García López',   'email' => 'carlos.garcia@instituto.es'],
            ['nombre' => 'Lucía',   'apellidos' => 'Martínez Ruiz',  'email' => 'lucia.martinez@instituto.es'],
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
            ['nombre' => 'Ana',      'apellidos' => 'Fernández Torres',  'email' => 'ana.fernandez@alumno.es'],
            ['nombre' => 'Marco',    'apellidos' => 'Rodríguez Vega',    'email' => 'marco.rodriguez@alumno.es'],
            ['nombre' => 'Sofía',    'apellidos' => 'López Sánchez',     'email' => 'sofia.lopez@alumno.es'],
            ['nombre' => 'Javier',   'apellidos' => 'Moreno Castillo',   'email' => 'javier.moreno@alumno.es'],
            ['nombre' => 'Paula',    'apellidos' => 'Jiménez Blanco',    'email' => 'paula.jimenez@alumno.es'],
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
