<?php

namespace App\Http\Controllers;

use App\Models\Pregunta;
use App\Models\Modulo;
use App\Models\Etiqueta;
use Illuminate\Http\Request;

class PreguntaController extends Controller
{
    public function index(Modulo $modulo) {
        $preguntas = $modulo->preguntas()->with('listaEtiquetas')->get();
        return view('usuario.profesor.preguntas.preguntas', compact('preguntas', 'modulo'));
    }

    public function create(Modulo $modulo) {
        $etiquetas_bd = Etiqueta::all();
        return view('usuario.profesor.preguntas.crearPregunta', compact('modulo', 'etiquetas_bd'));
    }

    public function store(Request $request, Modulo $modulo) {
        [$validated, $contenido, $etiquetas] = $this->comprobarPregunta($request);
        
        try {
            $pregunta = Pregunta::create([
                'tipo' => $validated['tipo'],
                'contenido' => $contenido,
                'id_modulo' => $modulo->id_modulo
            ]);

            if (!empty($etiquetas)) {
                $pregunta->listaEtiquetas()->sync($etiquetas);
            }

            return redirect()->route('profesor.preguntas.index', compact('modulo'));
            
        } catch(\Exception $e) {
           return back()->withErrors(['error' => 'Error al crear la pregunta, inténtalo de nuevo.']);
        }        
    }

    public function edit(Modulo $modulo, Pregunta $pregunta) {
        $etiquetas_bd = Etiqueta::all();
        $pregunta->load('listaEtiquetas');
        return view('usuario.profesor.preguntas.crearPregunta', compact('modulo', 'etiquetas_bd', 'pregunta'));
    }

    public function update(Request $request, Modulo $modulo, Pregunta $pregunta) {
        [$validated, $contenido, $etiquetas] = $this->comprobarPregunta($request);

        try {
            $pregunta->update([
                'tipo' => $validated['tipo'],
                'contenido' => $contenido,
            ]);

            if (!empty($etiquetas)) {
                $pregunta->listaEtiquetas()->sync($etiquetas);
            }

            return redirect()->route('profesor.preguntas.index', compact('modulo'));
            
        } catch(\Exception $e) {
           return back()->withErrors(['error' => 'Error al actualizar la pregunta, inténtalo de nuevo.']);
        } 
    }

    public function destroy(Modulo $modulo, Pregunta $pregunta) {
        try {
            $pregunta->delete();

            return redirect()->route('profesor.preguntas.index', $modulo->id_modulo);

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al borrar la pregunta, inténtalo de nuevo.']);
        }
    }

    private function comprobarPregunta($request) {
        $validated = $request->validate([
            'tipo' => 'required|string|max:255',
            'enunciado' => 'required|string|max:255',

            'opciones' => 'nullable|required_if:tipo,multiple|array|min:3',
            'opciones.*' => 'nullable|required_if:tipo,multiple|string|max:255',

            'columna_a' => 'nullable|required_if:tipo,conecta|array|min:2',
            'columna_a.*' => 'nullable|required_if:tipo,conecta|string|max:255',
            'columna_b' => 'nullable|required_if:tipo,conecta|array|min:2',
            'columna_b.*' => 'nullable|required_if:tipo,conecta|string|max:255',

            'respuesta' => 'required_unless:tipo,conecta|string|max:255',

            'etiquetas_existentes'   => 'nullable|array',
            'etiquetas_existentes.*' => 'integer|exists:etiquetas,id_etiqueta',
            
            'etiquetas_nuevas'       => 'nullable|array',
            'etiquetas_nuevas.*'     => 'string|max:255',
        ]);

        try {
            if ($validated['tipo'] == 'multiple') {
                $contenido = [
                    'enunciado' => $validated['enunciado'],
                    'opciones' => $validated['opciones'],
                    'respuesta' => $validated['respuesta']];

            } else if ($validated['tipo'] == 'conecta') {
                $div1 = [];
                $div2 = [];
                $respuestas = [];

                foreach ($validated['columna_a'] as $index => $valorA) {
                    $numero = $index + 1;
                    $letra = chr(97 + $index); 

                    $div1[(string)$numero] = $valorA; 
                    $div2[$letra] = $validated['columna_b'][$index]; 
                    
                    $respuestas[(string)$numero] = $letra; 
                }

                $contenido = [
                    'enunciado' => $validated['enunciado'],
                    'div-1' => $div1,
                    'div-2' => $div2,
                    'respuesta' => $respuestas
                ];
                
            } else {
                $contenido = [
                    'enunciado' => $validated['enunciado'],
                    'respuesta' => $validated['respuesta']];
            }

            // ETIQUETAS ----------------
            $etiquetas = [];

            if ($request->has('etiquetas_existentes')) {
                $etiquetas = $request->etiquetas_existentes;
            }

            if ($request->has('etiquetas_nuevas')) {
                foreach ($request->etiquetas_nuevas as $nombreEtiqueta) {
                    $etiqueta = Etiqueta::firstOrCreate([  // Con esto no  deberia haber etiquetas repetidas en la bd
                        'nombre' => strtolower(trim($nombreEtiqueta))
                    ]);
                    
                    $etiquetas[] = $etiqueta->id_etiqueta;
                }
            }
            return [$validated, $contenido, $etiquetas];
        
        } catch (\Exception $e) {
            throw $e;
        }
    }
}

