<nav class="form-card" style="padding: 1.5rem; margin-top: 2rem;">
    <div class="form-group">
        <label class="form-label" style="font-size: 0.85rem;">Módulo Activo</label>
        <select onchange="if(this.value) location = this.value;" class="form-input" style="max-width: 400px; font-weight: 600;">
            <option value="">-- Selecciona un módulo --</option>

            @foreach (Auth::user()->alumno->modulos as $modulo)
                <option value="{{ route('inicio.dashboardAlumno.mostrar', $modulo->id_modulo) }}"
                    {{ $moduloActual?->id_modulo === $modulo->id_modulo ? 'selected' : '' }}>
                    {{ $modulo->ciclo }} - {{ $modulo->modulo }} (Prof. {{$modulo->profesor->usuario->nombre}} {{$modulo->profesor->usuario->apellidos}})
                </option>
            @endforeach

            <option value="{{ route('alumno.matriculas.index') }}">+ Unirse a un nuevo módulo</option>
        </select> 
    </div>

    @if ($moduloActual)
        <div style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid var(--border);">
            <h2 style="color: var(--color-modulo); margin-bottom: 1rem; font-size: 1.3rem;">
                {{ $moduloActual->ciclo }} - {{ $moduloActual->modulo }}
            </h2>
            <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                <a href="{{ route('alumno.tests.examen', $moduloActual->id_modulo) }}" class="btn btn-secondary">Exámenes</a>
                <a href="{{ route('alumno.tests.practica', $moduloActual->id_modulo) }}" class="btn btn-secondary">Ejercicios</a>
                <a href="{{ route('alumno.historial', $moduloActual->id_modulo) }}" class="btn btn-secondary">Historial</a>
                <a href="{{ route('alumno.ajustes', $moduloActual->id_modulo) }}" class="btn btn-secondary">Ajustes</a>
            </div>
        </div>
    @endif
</nav>