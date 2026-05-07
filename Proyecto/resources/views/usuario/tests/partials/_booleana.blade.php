@php $disabled = $estado ? 'disabled' : ''; @endphp
<div style="display: flex; flex-direction: column; gap: 0.5rem;">
    @foreach (['verdadero', 'falso'] as $valor)
        @php
            $class = ''; $checked = '';
            if ($estado) {
                if ($estado['usuario'] === $valor) $checked = 'checked';
                if ($estado['correcta'] === $valor) $class .= ' correct-bg';
                elseif ($estado['usuario'] === $valor) $class .= ' incorrect-bg';
            }
        @endphp
        <label class="{{ $class }}">
            <input type="radio" name="respuestas[{{ $id }}]" value="{{ $valor }}" {{ $checked }} {{ $disabled }}>
            <span>{{ __('pregunta.' . $valor) }}</span>
        </label>
    @endforeach
</div>