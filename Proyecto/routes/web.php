<?php

use App\Http\Controllers\AlumnoModuloController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\InicioController;
use App\Http\Controllers\ProfesorModuloController;
use Illuminate\Support\Facades\Route;

Route::get('/', [InicioController::class, 'mostrarIndex'])->name('inicio.mostrarIndex');

Route::middleware('guest')->controller(InicioController::class)->group(function(){
    Route::get('/login', 'mostrarLogin')->name('inicio.mostrarLogin');
    Route::get('/register', 'mostrarRegister')->name('inicio.mostrarRegister');

    Route::controller(AuthController::class)->group(function() {
        Route::post('/login', 'login')->name('auth.login');
        Route::post('/register', 'register')->name('auth.register');
    });
});

Route::middleware('auth')->controller(AuthController::class)->group(function() {

    // Dashboards
    
    Route::middleware('profesor')->group(function() {
        // Mostrar dashboard de profesor

        Route::get('/profesor', [InicioController::class, 'mostrarDashboardProfesor'])->name('inicio.mostrarDashboardProfesor');

        // Crear modulos

        Route::controller(ProfesorModuloController::class)->group(function() {
            // Mostrar formulario para crear modulos nuevos
            Route::get('/profesor/crearModulo', 'crearModuloMostrar')->name('profesor.crearModuloMostrar');

            // Crear el modulo
            Route::post('/profesor/crearModulo', 'crearModuloCrear')->name('profesor.crearModuloCrear');
        });

        // Acceder Modulos
        Route::middleware('moduloProfesor')->controller(ProfesorModuloController::class)
            ->missing(function () { // En caso de que no exista el modulo
                return redirect()->route('inicio.mostrarDashboardProfesor')
                    ->with('error', 'Este módulo no existe.');
            })
            ->group(function () {
                Route::get('/profesor/{modulo}', 'mostrarModulo')->name('profesor.mostrarModulo');
            });
    });

    Route::middleware('alumno')->group(function() {
        Route::get('/alumno', [InicioController::class, 'mostrarDashboardAlumno'])->name('inicio.mostrarDashboardAlumno');

        Route::controller(AlumnoModuloController::class)->group(function() {
            Route::get('alumno/modulos', 'mostrarModulos')->name('alumno.mostrarModulos');
            Route::get('alumno/modulos/unirse', 'unirseModuloMostrar')->name('alumno.unirseModuloMostrar');
            Route::get('alumno/modulos/unirse/{modulo}', 'matricularseModuloMostrar')->name('alumnos.matricularseModuloMostrar');
            Route::post('alumno/modulos/unirse/{modulo}', 'matricularseModuloEntrar')->name('alumnos.matricularseModulo.Entrar');

            // Dentro del modulo

            Route::middleware('moduloAlumno')->group(function() {
                Route::get('alumno/modulo/{modulo}', 'moduloDashboardMostrar')->name('alumno.moduloDashboard');
            });
        });

    });

    // Cerrar sesión

    Route::get('/logout', 'logout')->name('auth.logout');
});