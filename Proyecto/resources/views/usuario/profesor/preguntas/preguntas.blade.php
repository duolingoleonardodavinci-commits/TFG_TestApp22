@extends('layouts.app')

@section('title', 'preguntas')

@section('content')
    <x-header />
    <x-errores />
    <h1>Listado de preguntas</h1>

    <div x-data="{ busqueda: '' }">
        <div>
            <label>Buscar pregunta:</label>
            <input type="search" 
                x-model="busqueda" 
                placeholder="Escribe una palabra...">
        </div>
        
        <br><hr><br>

        @foreach ($preguntas as $pregunta)
            
            <div x-data='{ 
                    abierta: false,
                    texto: @json(strtolower($pregunta->contenido["enunciado"] ?? "")) 
                }' 
                x-show="busqueda === '' || texto.includes(busqueda.toLowerCase())"> 

                <div @click="abierta = !abierta">        
                    <b>Pregunta:</b> {{ $pregunta->contenido['enunciado'] }}
                    <br><br>

                    Tipo: {{ $pregunta->tipo }}
                    <br>

                    Etiquetas: 
                    @forelse ($pregunta->listaEtiquetas as $etiqueta)
                        [{{ $etiqueta->nombre }}]
                    @empty
                        Ninguna
                    @endforelse
                </div>
                
                <div x-show="abierta" x-cloak>
                    <a href="{{ route('profesor.preguntas.edit', [$modulo->id_modulo, $pregunta->id_pregunta]) }}">
                        <button type="button">Editar</button>
                    </a>
                    <form action="{{ route('profesor.preguntas.destroy', [$modulo->id_modulo, $pregunta->id_pregunta]) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('¿Seguro que quieres eliminar esta pregunta?');">
                        @csrf
                        @method('DELETE') 
                        <button type="submit">Eliminar</button>
                    </form>
                </div>
                
                <br><hr><br>

            </div>     

        @endforeach
    </div>

    <p><a href="{{ route('profesor.preguntas.create', $modulo->id_modulo) }}">+ Crear preguntas</a></p>

@endsection