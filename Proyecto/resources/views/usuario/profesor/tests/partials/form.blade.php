        @csrf 

        @if(isset($test))
            @method('PUT')
            <h2>Editar Test</h2>
        @else
            <h2>Crear Test</h2>
        @endif

        

        <!-- Nombre -->

        <p>
            <label>
                <span>Nombre</span>
                <input type="text"
                        name="nombre"
                        value="{{ old('nombre', $test->nombre ?? '') }}"
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
                    {{ old('descripcion', $test->descripcion ?? '') }}
                </textarea>
            </label>
        </p>

        <!-- Tipo -->

        <p>
            Tipo:
            <label>
                <input type="radio" name="tipo" value="practica"
                {{ old('tipo', $test->tipo ?? '') === 'practica' ? 'checked' : '' }}>
                <span>Práctica</span>
            </label>

            <label>
                <input type="radio" name="tipo" value="examen"
                {{ old('tipo', $test->tipo ?? '') === 'examen' ? 'checked' : '' }}>
                <span>Examen</span>
            </label>
        </p>

        <!-- Asignar Preguntas al Test -->
        
        <h4>Asignar Preguntas al Test</h4>

        <div>
            @foreach ($preguntas as $pregunta)
                    <label for="pregunta-{{ $pregunta->id_pregunta}}">{{ $pregunta->contenido->enunciado}}</label>
                    <input
                        type="checkbox"
                        name="preguntas[]"
                        value="{{ $pregunta->id_pregunta}}"
                        id="pregunta-{{ $pregunta->id_pregunta }}"
                        {{ in_array($pregunta->id_pregunta, old('preguntas', isset($test) ? $test->preguntas->pluck('id_pregunta')->toArray() : [])) ? 'checked' : '' }}
                    >
            @endforeach
        </div>

        <button type="submit">{{ isset($test) ? 'Actualizar' : 'Crear' }}</button>
