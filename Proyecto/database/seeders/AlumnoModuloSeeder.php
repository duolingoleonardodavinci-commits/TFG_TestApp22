<?php

namespace Database\Seeders;

use App\Models\Alumno;
use App\Models\Modulo;
use App\Models\Usuario;
use Illuminate\Database\Seeder;

class AlumnoModuloSeeder extends Seeder
{
    public function run(): void
    {
        $modulos = Modulo::all()->keyBy('modulo');

        // Obtenemos alumnos por email para no depender del orden de inserción
        $emails = [
            'alumno1@gmail.com',
            'alumno2@gmail.com',
            'alumno3@gmail.com',
            'alumno4@gmail.com',
            'alumno5@gmail.com',
        ];

        $alumnos = Usuario::whereIn('email', $emails)
            ->get()
            ->map(fn($u) => $u->alumno)
            ->filter();

        // Cada alumno se matricula en módulos distintos.
        // La columna `tiene_acceso` la gestiona el profesor,
        // así que la ponemos a true para que puedan acceder en el seed.

        $matriculas = [
            // Ana → DAW completo
            'alumno1@gmail.com'    => ['Programación', 'Bases de Datos', 'Desarrollo Web'],
            // Marco → DAW completo + SO
            'alumno2@gmail.com'  => ['Programación', 'Bases de Datos', 'Desarrollo Web', 'Sistemas Operativos'],
            // Sofía → DAM completo
            'alumno3@gmail.com'      => ['Sistemas Operativos', 'Entornos de Desarrollo'],
            // Javier → DAM completo + programación
            'alumno4@gmail.com'    => ['Programación', 'Sistemas Operativos', 'Entornos de Desarrollo'],
            // Paula → Todo
            'alumno5@gmail.com'    => ['Programación', 'Bases de Datos', 'Desarrollo Web', 'Sistemas Operativos', 'Entornos de Desarrollo'],
        ];

        $usuariosPorEmail = Usuario::whereIn('email', array_keys($matriculas))->get()->keyBy('email');

        foreach ($matriculas as $email => $nombreModulos) {
            $usuario = $usuariosPorEmail[$email] ?? null;
            if (!$usuario) continue;

            $alumno = $usuario->alumno;
            if (!$alumno) continue;

            foreach ($nombreModulos as $nombreModulo) {
                $modulo = $modulos[$nombreModulo] ?? null;
                if (!$modulo) continue;

                // attach con la columna extra de la pivot
                if (!$modulo->alumnos()->where('alumnos.id_alumno', $alumno->id_alumno)->exists()) {
                    $alumno->modulos()->attach($modulo->id_modulo, ['tiene_acceso' => true]);
                }
            }
        }
    }
}
