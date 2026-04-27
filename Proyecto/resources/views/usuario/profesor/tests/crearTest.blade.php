@extends('layouts.app')

@section('title', 'tests')

@section('content')
    <x-header />
    <x-errores />
    
    <form method="POST" action="{{ route('profesor.crearTest.crear', $modulo->id_modulo) }}">
        @csrf 

        <h3>Creando Nuevo Test</h3>

        <!-- Nombre -->

        <p>
            <label>
                <span>Nombre</span>
                <input type="text"
                        name="nombre"
                        required
                        autofocus>
            </label>
        </p>

        <!-- Descripción -->

        <p>
            <label>
                <span>Descripción</span>
                <textarea name="descripcion" 
                            required>
                </textarea>
            </label>
        </p>

        <!-- Tipo -->

        <p>
            Tipo:
            <label>
                <input type="radio" name="tipo" value="practica">
                <span>Práctica</span>
            </label>

            <label>
                <input type="radio" name="tipo" value="examen">
                <span>Examen</span>
            </label>
        </p>

        <!-- Asignar Preguntas al Test -->
        
        <h4>Asignar Preguntas al Test</h4>

        <div>
            @forelse ($preguntas as $pregunta)
                    <label for="pregunta-{{ $pregunta->id_pregunta}}">{{ $pregunta->contenido->enunciado}}</label>
                    <input
                        type="checkbox"
                        name="preguntas[]"
                        value="{{ $pregunta->id_pregunta}}"
                        id="pregunta-{{ $pregunta->id_pregunta }}"
                    >
            @empty
                <p>No tienes preguntas >:(</p>
                <p><a href="{{ route('profesor.crearPregunta.mostrar', $modulo->id_modulo) }}">+ Crear preguntas</a></p>
            @endforelse
        </div>

        <button type="submit">Crear Test</button>
    </form>
@endsection