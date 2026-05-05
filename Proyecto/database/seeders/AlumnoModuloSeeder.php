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
            'ana.fernandez@alumno.es',
            'marco.rodriguez@alumno.es',
            'sofia.lopez@alumno.es',
            'javier.moreno@alumno.es',
            'paula.jimenez@alumno.es',
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
            'ana.fernandez@alumno.es'    => ['Programación', 'Bases de Datos', 'Desarrollo Web'],
            // Marco → DAW completo + SO
            'marco.rodriguez@alumno.es'  => ['Programación', 'Bases de Datos', 'Desarrollo Web', 'Sistemas Operativos'],
            // Sofía → DAM completo
            'sofia.lopez@alumno.es'      => ['Sistemas Operativos', 'Entornos de Desarrollo'],
            // Javier → DAM completo + programación
            'javier.moreno@alumno.es'    => ['Programación', 'Sistemas Operativos', 'Entornos de Desarrollo'],
            // Paula → Todo
            'paula.jimenez@alumno.es'    => ['Programación', 'Bases de Datos', 'Desarrollo Web', 'Sistemas Operativos', 'Entornos de Desarrollo'],
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
