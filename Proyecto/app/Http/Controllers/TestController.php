<?php

namespace App\Http\Controllers;

use App\Models\Modulo;
use App\Models\Test;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{
    // Mostrar páginas de tests
    public function index(Modulo $modulo) {
        $tests = $modulo->tests;
        return view('usuario.profesor.tests.tests', compact('modulo', 'tests'));
    }

    // ===============
    // ==== CREAR ====
    // ===============

    // Mostrar página de creación de tests
    public function create(Modulo $modulo) {
        if ($modulo->preguntas->isEmpty()) {
            return redirect()->route('profesor.preguntas.mostrar', $modulo->id_modulo)->withErrors(['error' => 'Debes crear preguntas antes de poder crear tests']);;
        }

        $preguntas = $modulo->preguntas;

        return view('usuario.profesor.tests.crearTest', compact('modulo', 'preguntas'));
    }

    // Crear test
    public function store(Request $request, Modulo $modulo) {
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

            return redirect()->route('profesor.tests.index', compact('modulo'));
        } catch(\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'No se ha podido crear el test, vuelve a intentarlo']);
        }

    }

    // ================
    // ==== EDITAR ====
    // ================

    // Mostrar página de edición de tests
    public function edit(Modulo $modulo, Test $test) {
        $preguntas = $modulo->preguntas;
        return view('usuario.profesor.tests.editarTest', compact('preguntas', 'test', 'modulo'));
    }

    // Editar test
    public function update(Request $request, Modulo $modulo, Test $test) {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string|max:255',
            'tipo' => 'required|in:practica,examen',
            'preguntas' => 'required|array|min:1',
            'preguntas.*' => 'exists:preguntas,id_pregunta',
        ]);

        try {
            DB::beginTransaction();

            $test->update([
                'nombre' => $validated['nombre'],
                'descripcion' => $validated['descripcion'],
                'tipo' => $validated['tipo'],
            ]);

            $test->preguntas()->sync($validated['preguntas']);

            DB::commit();

            return redirect()->route('profesor.tests.index', compact('modulo'));
        } catch(\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'No se ha podido editar el test, vuelve a intentarlo']);
        }
    }

    // ==================
    // ==== ELIMINAR ====
    // ==================

    // Eliminar test
    public function destroy(Test $test) {
        try {
            $test->delete();
            return redirect()->back();
        } catch(\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
