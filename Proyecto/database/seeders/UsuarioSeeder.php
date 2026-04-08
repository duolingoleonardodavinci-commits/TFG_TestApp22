<?php

namespace Database\Seeders;

use App\Models\Alumno;
use App\Models\Profesor;
use App\Models\Usuario;
use Illuminate\Database\Seeder;

class UsuarioSeeder extends Seeder
{
    public function run(): void
    {
        // Profesores
        $profesores = [
            ['nombre' => 'Pepe',  'apellidos' => 'García',  'email' => 'profesor1@gmail.com',  'password' => 'profesor1'],
            ['nombre' => 'María',   'apellidos' => 'Martínez', 'email' => 'profesor2@gmail.com',   'password' => 'profesor2'],
        ];

        foreach ($profesores as $datos) {
            $usuario = Usuario::create([
                'nombre'    => $datos['nombre'],
                'apellidos' => $datos['apellidos'],
                'email'     => $datos['email'],
                'password'  => $datos['password'],
                'rol'       => 'profesor',
            ]);

            Profesor::create([
                'id_profesor' => $usuario->id_usuario,
            ]);
        }

        // Alumnos
        $alumnos = [
            ['nombre' => 'Pedro',   'apellidos' => 'Sánchez Gil',   'email' => 'alumno1@gmail.com',   'password' => 'alumno1'],
            ['nombre' => 'Laura',   'apellidos' => 'López Torres',  'email' => 'alumno2@gmail.com',   'password' => 'alumno2'],
        ];

        foreach ($alumnos as $datos) {
            $usuario = Usuario::create([
                'nombre'    => $datos['nombre'],
                'apellidos' => $datos['apellidos'],
                'email'     => $datos['email'],
                'password'  => $datos['password'],
                'rol'       => 'alumno',
            ]);

            Alumno::create([
                'id_alumno' => $usuario->id_usuario,
            ]);
        }
    }
}