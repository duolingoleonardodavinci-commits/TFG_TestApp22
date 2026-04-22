<?php

namespace App\Http\Controllers;

use App\Models\Modulo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

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
        $alumno = Auth::user();

        // Si el módulo es null busca el último módulo. Recibe null si no se ha visitado ninguno
        $moduloActual = $modulo ?? Modulo::find($alumno->id_ultimo_modulo_visitado);

        // Se guarda el último módulo visitado
        if ($moduloActual) {
            $alumno->id_ultimo_modulo_visitado = $moduloActual->id_modulo;
            $alumno->save();
        }

        return view('usuario.alumno.dashboard', compact('moduloActual'));
    }
}
