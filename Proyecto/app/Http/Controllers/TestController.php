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
        return view('usuario.profesor.tests.crearTest', compact('modulo'));
    }
}
