<?php

namespace App\Http\Controllers;

use App\Models\Pregunta;
use App\Models\Modulo;
use Illuminate\Http\Request;

class PreguntaController extends Controller
{
    public function preguntasMostrar(Modulo $modulo) {
        return view('usuario.profesor.modulo.preguntas.preguntas', compact('modulo'));
    }

    public function crearPreguntasMostrar(Modulo $modulo) {
        return view('usuario.profesor.modulo.preguntas.crearPregunta', compact('modulo'));
    }

    public function crearPreguntaCrear(Request $request, Modulo $modulo) {
        $validated = $request->validate([
            'tipo' => 'required|string|max:255',
            'enunciado' => 'required|string|max:255',
            'opciones' => 'required_if:tipo,multiple|string|max:255',
            'respuesta' => 'required|string|max:255'
        ]);

        try {
            if ($validated['tipo'] == 'multiple') {
                $contenido = [
                    'enunciado' => $validated['enunciado'],
                    'opciones' => $validated['opciones'],
                    'respuesta' => $validated['respuesta']];
            } else {
                $contenido = [
                    'enunciado' => $validated['enunciado'],
                    'respuesta' => $validated['respuesta']];
            }

            $pregunta = Pregunta::create([
                'tipo' => $validated['tipo'],
                'contenido' => $contenido,
                'id_modulo' => $modulo->id_modulo
            ]);

            return redirect()->route('profesor.preguntas.mostrar', compact('modulo'));
        } catch(\Exception $e) {
           return back()->withErrors(['error' => 'Error al crear la pregunta, inténtalo de nuevo.']);
        }        
    }
}
