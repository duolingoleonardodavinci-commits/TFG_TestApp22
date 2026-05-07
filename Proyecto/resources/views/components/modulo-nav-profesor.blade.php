<nav class="form-card" style="padding: 1.5rem; margin-top: 2rem;">
    <div class="form-group">
        <label class="form-label" style="font-size: 0.85rem;">Módulo Activo</label>
        <select onchange="if(this.value) location = this.value;" class="form-input" style="max-width: 400px; font-weight: 600;">
            <option value="">-- Selecciona un módulo --</option>
            @foreach (Auth::user()->profesor->modulos as $modulo)
                <option value="{{ route('inicio.dashboardProfesor.mostrar', $modulo->id_modulo) }}"
                    {{ $moduloActual?->id_modulo === $modulo->id_modulo ? 'selected' : '' }}>
                    {{ $modulo->ciclo }} - {{ $modulo->modulo }}
                </option>
            @endforeach
            <option value="{{ route('profesor.modulos.create') }}">+ Crear nuevo módulo</option>
        </select>
    </div>

    @if ($moduloActual)
        <div style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid var(--border);">
            <h2 style="color: var(--color-modulo); margin-bottom: 1rem; font-size: 1.3rem;">
                Gestionando: {{ $moduloActual->ciclo }} - {{ $moduloActual->modulo }}
            </h2>
            
            <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                <a href="{{ route('profesor.preguntas.index', $moduloActual->id_modulo) }}" class="btn btn-secondary">Preguntas</a>
                <a href="{{ route('profesor.tests.index', $moduloActual->id_modulo) }}" class="btn btn-secondary">Tests</a>
                <a href="{{ route('profesor.alumnos.index', $moduloActual->id_modulo) }}" class="btn btn-secondary">Alumnos</a>
                <a href="{{ route('profesor.historial', $moduloActual->id_modulo) }}" class="btn btn-secondary">Historial</a>
                <a href="{{ route('profesor.modulos.edit', $moduloActual->id_modulo) }}" class="btn btn-secondary">Ajustes</a>
            </div>
        </div>
    @endif
</nav>