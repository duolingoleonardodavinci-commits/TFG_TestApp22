<?php

namespace App\Http\Controllers;

use App\Models\Modulo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class InicioController
{
   public function indexMostrar() {
        if (Auth::check()) {
            return redirect()->route('inicio.dashboard.mostrar');
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

    public function dashboardMostrar(?Modulo $modulo = null) {

        $usuario = Auth::user();

        // Si el $modulo es null se busca el último módulo con el que el usuario ha interactuado.

        $moduloActual = $modulo ?? Modulo::find($usuario->id_ultimo_modulo_visitado);

        // Actualiza el id del último módulo visitado

        if($moduloActual) {
            $usuario->id_ultimo_modulo_visitado = $moduloActual->id_modulo;
            $usuario->save();
        }

        // Redirigir al dashboard correspondiente

        if ($usuario->rol === 'profesor') {
            return view('usuario.profesor.dashboard', compact('moduloActual'));
        } elseif($usuario->rol === 'alumno') {
            return view('usuario.alumno.dashboard', compact('moduloActual'));
        }
    }
}
