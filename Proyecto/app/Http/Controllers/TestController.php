<?php

namespace App\Http\Controllers;

use App\Models\Modulo;
use App\Models\Test;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    public function crearTestCrear(Request $request, Modulo $modulo) {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string|max:255',
            'tipo' => 'required|in:practica,examen',
            'preguntas' => 'required|array|min:1',
            'preguntas.*' => 'exists:preguntas,id_pregunta',
        ]);

        try {
            DB::beginTransaction();

            $test = Test::create([
                'nombre' => $validated['nombre'],
                'descripcion' => $validated['descripcion'],
                'tipo' => $validated['tipo'],
                'id_modulo' => $modulo->id_modulo,
            ]);

            $test->preguntas()->sync($validated['preguntas']);

            DB::commit();
        } catch(\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'No se ha podido crear el test, vuelve a intentarlo']);
        }
    }
}
