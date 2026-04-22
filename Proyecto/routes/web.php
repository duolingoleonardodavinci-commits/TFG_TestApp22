<?php

use App\Http\Controllers\AlumnoModuloController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\InicioController;
use App\Http\Controllers\ProfesorModuloController;
use Illuminate\Support\Facades\Route;

Route::get('/', [InicioController::class, 'indexMostrar'])->name('inicio.index.mostrar');

// =================
// ===== GUEST =====
// =================

Route::middleware('guest')->controller(InicioController::class)->group(function(){

    // Mostrar el login
    Route::get('/login', 'loginMostrar')->name('inicio.login.mostrar');

    // Mostrar el register
    Route::get('/register', 'registerMostrar')->name('inicio.register.mostrar');

    Route::controller(AuthController::class)->group(function() {
        // Iniciar sesion
        Route::post('/login', 'login')->name('auth.login');

        // Registrarse
        Route::post('/register', 'register')->name('auth.register');
    });
});

// ================
// ===== AUTH =====
// ================

Route::middleware('auth')->controller(AuthController::class)->group(function() {

    // Dashboard (tanto de profesor como alumno)
    Route::get('/dashboard/{modulo?}', [InicioController::class, 'dashboardMostrar'])->name('inicio.dashboard.mostrar');

    // ==================
    // ==== PROFESOR ====
    // ==================

    Route::middleware('profesor')->prefix('profesor')->group(function() {

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
                return redirect()->route('inicio.dashboard.mostrar')
                    ->with('error', 'Este módulo no existe.');
            })
            ->group(function () {
                Route::get('/{modulo}', 'modulosMostrar')->name('profesor.modulos.mostrar');
                Route::get('/{modulo}/preguntas', 'preguntasMostrar')->name('profesor.preguntas.mostrar');
            });
    });

    // ================
    // ==== ALUMNO ====
    // ================

    Route::middleware('alumno')->prefix('alumno')->group(function() {

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