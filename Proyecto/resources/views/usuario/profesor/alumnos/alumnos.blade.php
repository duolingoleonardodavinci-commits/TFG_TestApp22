@extends('layouts.app')

@section('title', 'Alumnos')

@section('content')

    <x-header />
    <x-errores />

    <h1>Alumnos</h1>

    <!-- FORMS -->

    <form id="form-accesos" action="{{ route('profesor.alumnos.editar', $modulo->id_modulo) }}" method="POST">
        @csrf
        @method('PUT')
    </form>

    @foreach ($usuarios as $usuario)
        <form id="form-eliminar-{{ $usuario->id_usuario }}" action="{{ route('profesor.alumno.eliminar', [$modulo->id_modulo, $usuario->id_usuario]) }}" method="POST">
            @csrf
            @method('DELETE')
        </form>
    @endforeach

    <!-- TABLE Y BUTTONS -->

    @if (!$usuarios->isEmpty())
        
    <table>

        <thead>
            <tr>
                <th>Nombre</th>
                <th>Apellidos</th>
                <th>Acceso al Módulo</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($usuarios as $usuario)
                <tr>
                    <td>{{ $usuario->nombre }}</td>
                    <td>{{ $usuario->apellidos }}</td>

                    <td>
                        <input
                            type="checkbox"
                            name="alumnos_acceso[]"
                            value="{{ $usuario->id_usuario }}"
                            id="usuario-{{ $usuario->id_usuario }}"
                            form="form-accesos"
                            {{ in_array($usuario->id_usuario, old('alumnos_acceso', $alumnosConAcceso)) ? 'checked' : '' }}
                        >
                    </td>

                    <td>
                        <button type="submit" form="form-eliminar-{{ $usuario->id_usuario }}">
                            Eliminar
                        </button>
                        <a href="#">Historial</a>
                    </td>
                </tr>
            @endforeach
        </tbody>

        <button type="submit" form="form-accesos">
            Guardar Accesos
        </button>

    </table>

    @else
        <p>Tus alumnos no tienen ganas de aprender :(</p>
        <p>Te dejamos una guía sobre como hacer que se entusiasmen por el aprendizaje <a href="https://youtu.be/dQw4w9WgXcQ?si=p68uEu3Mc2_X7HDs">Link</a></p>
        <img src="https://media.tenor.com/qWMqAsnk2h8AAAAM/cat-explosion.gif">
    @endif

@endsection