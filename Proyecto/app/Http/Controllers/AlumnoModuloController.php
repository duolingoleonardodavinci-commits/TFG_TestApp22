<?php

namespace App\Http\Controllers;

use App\Models\Modulo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AlumnoModuloController extends Controller
{
    // Muestra todos los módulos a los que se ha unido el alumno

    public function modulosMostrar() {
        $modulos = Auth::user()->alumno->modulos;

        return view('usuario.alumno.modulo.matriculacion.modulos', compact('modulos'));
    }

    // Muestra todos los módulos creados por los profesores

    public function seleccionarModuloMostrar() {
        $alumno = Auth::user()->alumno;

        $modulos = Modulo::whereDoesntHave('alumnos', function ($query) use ($alumno) {
            $query->where('alumnos.id_alumno', $alumno->id_alumno);
        })->get();

        return view('usuario.alumno.modulo.matriculacion.seleccionarModulo', compact('modulos'));
    }

    // Muestra el formulario para introducir la clave de matriculación al módulo

    public function matricularseModuloMostrar(Modulo $modulo) {
        return view('usuario.alumno.modulo.matriculacion.matricularseModulo', compact('modulo'));
    }

    // Introduce al alumno en el módulo usando una tabla pivote

    public function matricularseModuloEntrar(Request $request, Modulo $modulo)
{
        $alumno = Auth::user()->alumno;

        // Comprobamos si el alumno ya pertenece al módulo

        if ($modulo->alumnos()->where('alumnos.id_alumno', $alumno->id_alumno)->exists()) {
            return redirect()->route('alumno.moduloDashboard.mostrar', compact('modulo'));
        }

        // Comprobamos si la clave de matriculación es correcta

        if ($modulo->clave_matriculacion !== $request->clave_matriculacion) {
            return back()->withErrors(['clave_matriculacion' => 'Clave incorrecta']);
        }

        // Matriculamos al alumno

        $alumno->modulos()->attach($modulo->id_modulo);

        return redirect()->route('alumno.moduloDashboard.mostrar', compact('modulo'));
    }

    // --- DASHBOARD MÓDULOS DE ALUMNOS ---

    public function moduloDashboardMostrar(Modulo $modulo) {
        return view('usuario.alumno.modulo.dashboard.dashboard', compact('modulo'));
    }
}
