<?php

namespace App\Http\Controllers;

use App\Models\Modulo;
use Illuminate\Http\Request;

class TestController extends Controller
{
    
    public function testsMostrar(Modulo $modulo) {
        return view('usuario.profesor.tests.tests', compact('modulo'));
    }

    public function crearTestMostrar(Modulo $modulo) {
        if ($modulo->preguntas->isEmpty()) {
            return redirect()->route('profesor.preguntas.mostrar', $modulo->id_modulo)->withErrors(['error' => 'Debes crear preguntas antes de poder crear tests']);;
        }

        $preguntas = $modulo->preguntas;

        return view('usuario.profesor.tests.crearTest', compact('modulo', 'preguntas'));
    }
}
