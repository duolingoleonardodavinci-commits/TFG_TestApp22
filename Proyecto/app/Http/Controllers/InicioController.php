<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class InicioController
{
    public function mostrarIndex() {
        if (Auth::check()) {
            return Auth::user()->esProfesor()
                ? view('profesor.dashboard')
                : view('alumno.dashboard');
        }

        return view('index');
    }

    public function mostrarLogin() {
        return view('auth.login');
    }

    public function mostrarRegister() {
        return view('auth.register');
    }

    public function mostrarDashboardProfesor() {
        return view('profesor.dashboard');
    }

    public function mostrarDashboardAlumno() {
        return view('alumno.dashboard');
    }
}
