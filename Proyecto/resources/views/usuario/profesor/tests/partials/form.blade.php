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

        <div x-data="{ tipo: '{{ old('tipo', $test->tipo ?? '') }}' }">
    
            <label>
                <input type="radio" name="tipo" value="practica"
                    x-on:change="tipo = 'practica'"
                    {{ old('tipo', $test->tipo ?? '') === 'practica' ? 'checked' : '' }}>
                <span>Práctica</span>
            </label>

            <label>
                <input type="radio" name="tipo" value="examen"
                    x-on:change="tipo = 'examen'"
                    {{ old('tipo', $test->tipo ?? '') === 'examen' ? 'checked' : '' }}>
                <span>Examen</span>
            </label>

            <div x-show="tipo === 'examen'">
                <label>
                    <span>Duración (minutos)</span>
                    <input type="number" name="duracion" value="{{ old('duracion', $test->examen->duracion ?? '') }}">
                </label>

                <label>
                    <span>Fecha de apertura</span>
                    <input type="datetime-local" name="fecha_apertura" value="{{ old('fecha_apertura', $test->examen->fecha_apertura ?? '') }}">
                </label>
            </div>

        </div>

        <!-- Asignar Preguntas al Test -->
        
        <h4>Asignar Preguntas al Test</h4>

        <div x-data="{
                busqueda: '',

                normalizar(texto) {
                    return texto
                        .toLowerCase()
                        .normalize('NFD')
                        .replace(/[\u0300-\u036f]/g, '')
                        .replace(/[^\w\s]/g, '')
                        .replace(/\s+/g, ' ')
                        .trim();
                },

                get parsed() {
                    let tokens = this.normalizar(this.busqueda).split(/\s+/).filter(Boolean);
                    let etiquetas = tokens.filter(t => t.startsWith(':')).map(t => t.slice(1));
                    let tipo      = (tokens.find(t => t.startsWith('tipo:')) ?? '').slice(5);
                    let texto     = tokens.filter(t => !t.startsWith(':') && !t.startsWith('tipo:')).join(' ');
                    return { etiquetas, tipo, texto };
                },

                coincide(enunciado, etiquetas_pregunta, tipo_pregunta) {
                    let { etiquetas, tipo, texto } = this.parsed;
                    if (texto && !enunciado.includes(texto)) return false;
                    if (tipo  && !tipo_pregunta.includes(tipo)) return false;
                    if (etiquetas.length && !etiquetas.every(e => etiquetas_pregunta.some(ep => ep.includes(e)))) return false;
                    return true;
                }
            }">

            <label>Buscar pregunta:</label>
            <input type="search"
                x-model="busqueda"
                placeholder="Texto, :etiqueta, tipo:multiple ...">
            <p>
                <i>:etiqueta</i> filtra por etiqueta,
                <i>tipo:multiple</i> / <i>tipo:booleana</i> / <i>tipo:texto</i> / <i>tipo:conecta</i> por tipo,
                texto libre por enunciado.
            </p>

            @foreach ($preguntas as $pregunta)
                <div x-show="coincide(
                        normalizar({{ Js::from(strtolower($pregunta->contenido['enunciado'] ?? '')) }}),
                        {{ Js::from($pregunta->listaEtiquetas->pluck('nombre')->map(fn($n) => strtolower($n))->toArray()) }}.map(e => normalizar(e)),
                        normalizar({{ Js::from(strtolower($pregunta->tipo)) }})
                    )">

                    <label for="pregunta-{{ $pregunta->id_pregunta }}">{{ $pregunta->contenido->enunciado }}</label>
                    <input
                        type="checkbox"
                        name="preguntas[]"
                        value="{{ $pregunta->id_pregunta }}"
                        id="pregunta-{{ $pregunta->id_pregunta }}"
                        {{ in_array($pregunta->id_pregunta, old('preguntas', isset($test) ? $test->preguntas->pluck('id_pregunta')->toArray() : [])) ? 'checked' : '' }}
                    >
                </div>
            @endforeach
        </div>

        <button type="submit">{{ isset($test) ? 'Actualizar' : 'Crear' }}</button>
