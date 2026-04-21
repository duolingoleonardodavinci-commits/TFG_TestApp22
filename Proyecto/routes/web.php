<?php

use App\Http\Controllers\AlumnoModuloController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\InicioController;
use App\Http\Controllers\ProfesorModuloController;
use App\Models\Modulo;
use Illuminate\Support\Facades\Route;

Route::get('/', [InicioController::class, 'indexMostrar'])->name('inicio.index.mostrar');

Route::middleware('guest')->controller(InicioController::class)->group(function(){
    Route::get('/login', 'loginMostrar')->name('inicio.login.mostrar');
    Route::get('/register', 'registerMostrar')->name('inicio.register.mostrar');

    Route::controller(AuthController::class)->group(function() {
        Route::post('/login', 'login')->name('auth.login');
        Route::post('/register', 'register')->name('auth.register');
    });
});

Route::middleware('auth')->controller(AuthController::class)->group(function() {

    // Dashboards
    
    Route::middleware('profesor')->prefix('profesor')->group(function() {
        // Mostrar dashboard de profesor

        Route::get('/', [InicioController::class, 'dashboardProfesorMostrar'])->name('inicio.dashboardProfesor.mostrar');

        // Crear modulos

        Route::controller(ProfesorModuloController::class)->group(function() {
            // Mostrar formulario para crear modulos nuevos
            Route::get('/crearModulo', 'crearModuloMostrar')->name('profesor.crearModulo.mostrar');

            // Crear el modulo
            Route::post('/crearModulo', 'crearModuloCrear')->name('profesor.crearModulo.crear');
        });

        // Acceder Modulos
        Route::middleware('moduloProfesor')->controller(ProfesorModuloController::class)
            ->missing(function () { // En caso de que no exista el modulo
                return redirect()->route('inicio.dashboardProfesor.mostrar')
                    ->with('error', 'Este módulo no existe.');
            })
            ->group(function () {
                Route::get('/{modulo}', 'modulosMostrar')->name('profesor.modulos.mostrar');
                Route::get('/{modulo}/preguntas', 'preguntasMostrar')->name('profesor.preguntas.mostrar');
            });
    });

    Route::middleware('alumno')->prefix('alumno')->group(function() {
        Route::get('/', [InicioController::class, 'dashboardAlumnoMostrar'])->name('inicio.dashboardAlumno.mostrar');

        Route::controller(AlumnoModuloController::class)->group(function() {
            Route::get('/modulos', 'modulosMostrar')->name('alumno.modulos.mostrar');
            Route::get('/modulos/seleccionar', 'seleccionarModuloMostrar')->name('alumno.seleccionarModulo.mostrar');
            Route::get('/modulos/seleccionar/{modulo}', 'matricularseModuloMostrar')->name('alumnos.matricularseModulo.mostrar');
            Route::post('/modulos/seleccionar/{modulo}', 'matricularseModuloEntrar')->name('alumnos.matricularseModulo.entrar');

            // Dentro del modulo

            Route::middleware('moduloAlumno')->group(function() {
                Route::get('/modulo/{modulo}', 'moduloDashboardMostrar')->name('alumno.moduloDashboard.mostrar');
            });
        });

    });

    // Cerrar sesión

    Route::get('/logout', 'logout')->name('auth.logout');
});