<?php

use App\Http\Controllers\AlumnoModuloController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\InicioController;
use App\Http\Controllers\ProfesorModuloController;
use App\Http\Controllers\PreguntaController;
use App\Http\Controllers\TestController;
use App\Models\Modulo;
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

    // ==================
    // ==== PROFESOR ====
    // ==================

    Route::middleware('profesor')->prefix('profesor')->group(function() {

        // Acceder Modulos

        Route::middleware('moduloProfesor')->controller(ProfesorModuloController::class)->group(function () {
            Route::get('/dashboard/{modulo?}', [InicioController::class, 'dashboardProfesorMostrar'])->name('inicio.dashboardProfesor.mostrar');
                
            // ===================
            // ==== PREGUNTAS ====
            // ===================

            Route::controller(PreguntaController::class)->group(function() {
                // Página de preguntas
                Route::get('/{modulo}/preguntas', 'preguntasMostrar')->name('profesor.preguntas.mostrar');

                // Mostrar formulario para crear preguntas nuevas
                Route::get('/{modulo}/preguntas/crear', 'crearPreguntasMostrar')->name('profesor.crearPregunta.mostrar');

                // Crear el modulo
                Route::post('/{modulo}/preguntas/crear', 'crearPreguntaCrear')->name('profesor.crearPregunta.crear');
            });

            // ===============
            // ==== TESTS ====
            // ===============

            Route::controller(TestController::class)->group(function() {
                // Página de tests
                Route::get('/{modulo}/tests', 'testsMostrar')->name('profesor.tests.mostrar');

                // Mostrar formulario para crear tests nuevos
                Route::get('/{modulo}/test/crear', 'crearTestMostrar')->name('profesor.crearTest.mostrar');
            });
        });

        Route::controller(ProfesorModuloController::class)->group(function() {
            // Mostrar formulario para crear modulos nuevos
            Route::get('/crearModulo', 'crearModuloMostrar')->name('profesor.crearModulo.mostrar');
            // Crear el modulo
            Route::post('/crearModulo', 'crearModuloCrear')->name('profesor.crearModulo.crear');
        });
    });

    // ================
    // ==== ALUMNO ====
    // ================
    Route::middleware('alumno')->prefix('alumno')->group(function() {

        // Acceder Modulos

        Route::middleware('moduloAlumno')->controller(AlumnoModuloController::class)->group(function() {

            // Dashboard de alumno
            Route::get('dashboard/{modulo?}', [InicioController::class, 'dashboardAlumnoMostrar'])->name('inicio.dashboardAlumno.mostrar');
        });

        Route::controller(AlumnoModuloController::class)->group(function() {
            // Unirse a módulos nuevos
            Route::get('/modulos/seleccionar', 'seleccionarModuloMostrar')->name('alumno.seleccionarModulo.mostrar');
            Route::get('/modulos/seleccionar/{modulo}', 'matricularseModuloMostrar')->name('alumnos.matricularseModulo.mostrar');
            Route::post('/modulos/seleccionar/{modulo}', 'matricularseModuloEntrar')->name('alumnos.matricularseModulo.entrar');
        });
    });

    // Cerrar sesión

    Route::get('/logout', 'logout')->name('auth.logout');
});