@csrf

@if(isset($modulo))
    @method('PUT')
    <h2>Modificar Módulo</h2>
@else
    <h2>Crear Módulo</h2>
@endif
<!-- Ciclo -->

<p>
    <label>
        <input type="text"
                name="ciclo"
                placeholder="1DAW"
                value="{{ old('ciclo', $modulo->ciclo ?? '') }}"
                class="input input-bordered @error('ciclo') input-error @enderror"
                required
                autofocus>
        <span>Ciclo</span>
    </label>
    @error('ciclo')
        <span>{{ $message }}</span>
    @enderror
</p>

<!-- Modulo -->

<p>
    <label>
        <input type="text"
                name="modulo"
                placeholder="Programación"
                value="{{ old('modulo', $modulo->modulo ?? '') }}"
                class="input input-bordered @error('modulo') input-error @enderror"
                required>
        <span>Módulo</span>
    </label>
    @error('modulo')
        <span>{{ $message }}</span>
    @enderror
</p>

<!-- Color del módulo -->

<p>
    <label>
        <input type="color" 
                name="color"
                value="{{ old('color', $modulo->color ?? '#000000')}}"
                class="input input-bordered @error('color') input-error @enderror"
                required>
        <span>Color del módulo</span>
    </label>
</p>

<!-- Idioma del módulo -->

<p>
    <label>
        <span>Idioma</span>
        <select name="idioma">
            <option value="es" {{ old('idioma', $modulo->idioma ?? 'es') === 'es' ? 'selected' : '' }}>Español</option>
            <option value="en" {{ old('idioma', $modulo->idioma ?? 'es') === 'en' ? 'selected' : '' }}>English</option>
        </select>
    </label>
</p>

<!-- Clave de matriculación del alumnado -->

<p>
    <label>
        <input type="text"
                name="clave_matriculacion"
                placeholder="****"
                value="{{ old('clave_matriculacion', $modulo->clave_matriculacion ?? '') }}"
                class="input input-bordered @error('clave_matriculacion') input-error @enderror"
                required>
        <span>Clave de matriculación del alumnado</span>
    </label>
</p>

<!-- Botón de submit -->

<button type="submit">
    {{ isset($modulo) ? 'Actualizar' : 'Crear' }}
</button>