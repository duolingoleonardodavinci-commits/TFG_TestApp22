@extends('layouts.app')

@section('title', 'preguntas')

@section('content')
    <x-header />
    <x-errores />
   
    <form method="POST" action="{{ route('profesor.crearPregunta.crear', $modulo->id_modulo) }}">
        @csrf
        <div x-data="{ tipo_pregunta: '' }">
        
            <div>
                <label>¿Qué tipo de pregunta es?</label>
                <select x-model="tipo_pregunta" name="tipo">
                    <option value="">Selecciona el tipo de pregunta...</option>
                    <option value="texto">Pregunta Abierta</option>
                    <option value="multiple">Opción Múltiple</option>
                    <option value="booleana">Verdadero / Falso</option>
                </select>
            </div>

            <div x-show="tipo_pregunta !== ''">
                <label>Escribe tu pregunta:</label>
                <input type="text" name="enunciado" placeholder="Ej: ¿Qué pregunta pongo aquí?">
            </div>

            <div x-show="tipo_pregunta === 'texto'" x-cloak>
                <label>Respuesta correcta:</label>
                <input type="text" name="respuesta" placeholder="Ej: Respuesta correcta" :disabled="tipo_pregunta !== 'texto'">
            </div>

            <div x-show="tipo_pregunta === 'multiple'" x-cloak>
                <label>Opciones (Escribe las opciones separadas por comas):</label>
                <input type="text" name="opciones" placeholder="Ej: Opcion 1, Opcion 2, Opcion 3..." :disabled="tipo_pregunta !== 'multiple'">
                <label>Respuesta correcta:</label>
                <input type="text" name="respuesta" placeholder="Ej: Respuesta correcta" :disabled="tipo_pregunta !== 'multiple'">
            </div>

            <div x-show="tipo_pregunta === 'booleana'" x-cloak>
                <label>La respuesta correcta es:</label>
                <br>
                <label>
                    <input type="radio" name="respuesta" value="verdadero" :disabled="tipo_pregunta !== 'booleana'">
                    <span>Verdadero</span>
                </label>
                <br>
                <label>
                    <input type="radio" name="respuesta" value="falso" :disabled="tipo_pregunta !== 'booleana'">
                    <span>Falso</span>
                </label>
            </div>

            <button type="submit">Guardar Pregunta</button>
        </div>
    </form>
@endsection