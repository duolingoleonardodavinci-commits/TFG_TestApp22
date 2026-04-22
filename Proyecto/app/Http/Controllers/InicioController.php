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
                ? view('usuario.profesor.dashboard')
                : view('usuario.alumno.dashboard');
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

    public function dashboardProfesorMostrar(Modulo $modulo) {
        
        return view('usuario.profesor.dashboard');
    }

    public function dashboardAlumnoMostrar() {
        return view('usuario.alumno.dashboard');
    }
}
