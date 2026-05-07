@php $disabled = $estado ? 'disabled' : ''; @endphp
<div style="display: flex; flex-direction: column; gap: 0.5rem;">
    @foreach ($opciones as $index => $opcion)
        @php
            $letra = chr(97 + $index); $class = ''; $checked = '';
            if ($estado) {
                if ($estado['usuario'] === $opcion) $checked = 'checked';
                if ($estado['correcta'] === $opcion) $class .= ' correct-bg';
                elseif ($estado['usuario'] === $opcion) $class .= ' incorrect-bg';
            }
        @endphp
        <label class="{{ $class }}">
            <input type="radio" name="respuestas[{{ $id }}]" value="{{ $opcion }}" {{ $checked }} {{ $disabled }}>
            <span><strong>{{ $letra }})</strong> {{ $opcion }}</span>
        </label>
    @endforeach
</div>