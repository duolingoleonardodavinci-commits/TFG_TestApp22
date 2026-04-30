<?php

namespace App\Http\Controllers;

use App\Models\Modulo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InicioController
{
   public function indexMostrar() {
        if (Auth::check()) {
            return Auth::user()->esProfesor()
                ? redirect()->route('inicio.dashboardProfesor.mostrar')
                : redirect()->route('inicio.dashboardAlumno.mostrar');
        }

        return view('index');
    }

    public function loginMostrar() {
        // Deshabilita el caché para que el usuario no pueda vovler a la página de login con las flechas de navegación
        return response()->view('auth.login')
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache');
    }

    public function registerMostrar() {
        return response()->view('auth.register')
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache');
    }

    // Dashboard de profesor

    public function dashboardProfesorMostrar(?Modulo $modulo = null) {
        $profesor = Auth::user();
        
        // Si el módulo es null busca el último módulo. Recibe null si no se ha visitado ninguno
        $moduloActual = $modulo ?? Modulo::find($profesor->id_ultimo_modulo_visitado);

        // Se guarda el último módulo visitado
        if ($moduloActual) {
            $profesor->id_ultimo_modulo_visitado = $moduloActual->id_modulo;
            $profesor->save();
        }

        return view('usuario.profesor.dashboard', compact('moduloActual'));
    }

    // Dashboard de alumno

    public function dashboardAlumnoMostrar(?Modulo $modulo = null) {

     /* !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
         HAY QUE MODIFICAR ESTA CONSULTA PARA QUE MUESTRE AQUELLOS MÓDULOS EN LOS QUE EL ALUMNO TIENE ACCESO
         SELECT m.id_modulo FROM m.modulos, ma.modulos_alumnos 
         WHERE (ma.id_alumno = alumno->id_alumno AND tiene_acceso = 1) AND ma.id_modulo = m.id_modulo;
         Es decir, el middleware funciona, solo que esta mostrandole el último módulo por pantalla al que ha accedido, y también lo muestra en la lista
        

        $modulos = $alumno->modulos()
                   ->wherePivot('tiene_acceso', true)
                   ->pluck('modulos.id_modulo');

        */

        $alumno = Auth::user();

        // Si el módulo es null busca el último módulo. Recibe null si no se ha visitado ninguno

         /*
            SELECT m.id_modulo 
            FROM modulos m, usuarios u, alumnos a, modulos_alumnos ma
            WHERE     m.id_modulo = ma.id_modulo 
                  AND ma.id_alumno = a.id_alumno 
                  AND a.id_alumno = u.id_usuario 
                  AND u.id_ultimo_modulo_visitado = m.id_modulo
                  AND ma.tiene_acceso = TRUE
                  AND a.id_alumno = $alumno->id_alumno;
        */
        $moduloActual = $modulo ?? DB::table('modulos')
                                        ->join('modulos_alumno', 'modulos.id_modulo', '=', 'modulos_alumno.id_modulo')
                                        ->join('alumnos', 'modulos_alumnos.id_alumno', '=', 'alumnos.id_alumno')
                                        ->join('usuarios', 'alumnos.id_alumno', '=', 'usuarios.id_usuario')
                                        ->join('modulos', 'usuarios.id_ultimo_modulo_visitado', '=', 'modulos.id_modulo')
                                        ->where('modulos_alumnos.tiene_acceso', '=', true)
                                        ->where('alumnos.id_alumno', '=', $alumno->id_usuario)
                                        ->pluck('id_modulo');

        // Se guarda el último módulo visitado
        if ($moduloActual) {
            $alumno->id_ultimo_modulo_visitado = $moduloActual->id_modulo;
            $alumno->save();
        }

        return view('usuario.alumno.dashboard', compact('moduloActual'));
    }
}
