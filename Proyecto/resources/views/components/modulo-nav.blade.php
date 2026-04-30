<nav>
    <select onchange="if(this.value) location = this.value;">
        <option value="">-- Selecciona un módulo --</option>

        @foreach (Auth::user()->profesor->modulos as $modulo)
            <option value="{{ route('inicio.dashboardProfesor.mostrar', $modulo->id_modulo) }}"
                {{ $moduloActual->id_modulo === $modulo->id_modulo ? 'selected' : '' }}
            >
                {{ $modulo->ciclo }} {{ $modulo->modulo }}
            </option>
        @endforeach

        <option value="{{ route('profesor.crearModulo.mostrar') }}">+ Crear nuevo módulo</option>
    </select> 

        <p>{{$moduloActual->ciclo}} {{$moduloActual->modulo}}</p>

        <p>
            <a href="{{ route('profesor.preguntas.index', $moduloActual->id_modulo) }}">Preguntas</a>
            <a href="{{ route('profesor.tests.index', $moduloActual->id_modulo) }}">Tests</a>
            <a href="{{ route('profesor.alumnos.mostrar', $moduloActual->id_modulo)}}">Alumnos</a>
        </p>
</nav>