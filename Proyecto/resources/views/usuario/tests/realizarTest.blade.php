<div>
    <h1>{{ $test->nombre }}</h1>
    <p>{{ $test->descripcion }}</p>
    <hr>
    @if(isset($nota))
        <div>
            <h2>
                Tu nota final es: <strong>{{ $nota }} / 10</strong>
            </h2>
        </div>
    @endif
    <form action="{{ Auth::user()->rol === 'profesor' 
        ? route('profesor.tests.corregir', [$modulo->id_modulo, $test->id_test])
        : route('alumno.tests.corregir', [$modulo->id_modulo, $test->id_test]) 
    }}" method="POST">  
        @csrf

        @foreach($test->preguntas as $index => $pregunta)
            @php
                $contenido = $pregunta->contenido;
                $numPregunta = $index + 1; 
            @endphp

            <div>
                <h3>{{ $numPregunta }}. {{ $contenido['enunciado'] }}</h3>

                @switch($pregunta->tipo)

                    @case('multiple')
                        @include('usuario.tests.partials._multiple', [
                            'id'      => $pregunta->id_pregunta,
                            'opciones'=> $contenido['opciones'],
                            'estado'  => $estado[$pregunta->id_pregunta] ?? null,
                        ])
                    @break

                    @case('booleana')
                        @include('usuario.tests.partials._booleana', [
                            'id'    => $pregunta->id_pregunta,
                            'estado'=> $estado[$pregunta->id_pregunta] ?? null,
                        ])
                    @break

                    @case('conecta')
                        @include('usuario.tests.partials._conecta', [
                            'id'      => $pregunta->id_pregunta,
                            'parejas' => $contenido['parejas'],
                            'mezclada'=> $contenido['columna_b_mezclada']
                                            ?? collect($contenido['parejas'])->pluck('b')->toArray(),
                            'estado'  => $estado[$pregunta->id_pregunta] ?? null,
                        ])
                    @break

                    @case('texto')
                        @include('usuario.tests.partials._texto', [
                            'id'    => $pregunta->id_pregunta,
                            'estado'=> $estado[$pregunta->id_pregunta] ?? null,
                        ])
                    @break

                    @default
                        <p>Tipo de pregunta desconocido: {{ $pregunta->tipo }}</p>

                @endswitch
            </div>
            
        @endforeach

        @if(!isset($estado))
            <button type="submit">Enviar Respuestas</button>
        @else
            @if(auth()->user()->rol === 'profesor') 
                <a href="{{ route('profesor.tests.index', [$modulo->id_modulo]) }}"><button type="button">Volver</button></a>
            @else
                {{-- dashboard del alumno --}}
                <a href="{{ route('inicio.dashboardAlumno.mostrar', [$modulo->id_modulo]) }}"><button type="button">Volver</button></a>
            @endif
        @endif
    </form>
</div>

<style>  /* provicional para asegurar que funciona */
    /* 🟢 Clase para resaltar las respuestas correctas */
    .correct-bg {
        background-color: #d4edda !important;
        border: 2px solid #28a745 !important;
        color: #155724 !important;
        border-radius: 5px;
    }

    /* 🔴 Clase para resaltar los fallos del usuario */
    .incorrect-bg {
        background-color: #f8d7da !important;
        border: 2px solid #dc3545 !important;
        color: #721c24 !important;
        border-radius: 5px;
    }

    /* 🟢 Texto extra para chivarle la respuesta correcta si falló (en texto o conecta) */
    .correct-text {
        color: #28a745;
        font-weight: bold;
        margin-top: 5px;
        font-size: 0.9rem;
        display: block;
    }
</style>