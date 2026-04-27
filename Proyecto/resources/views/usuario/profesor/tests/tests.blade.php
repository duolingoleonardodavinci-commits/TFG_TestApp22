@extends('layouts.app')

@section('title', 'tests')

@section('content')
    <x-header />
    <x-errores />

    <h1>Tests</h1>

    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($tests as $test)
                <tr>
                    <td>{{ $test->nombre }}</td>
                    <td>
                        <a href="{{ route('profesor.editarTest.mostrar', [$modulo->id_modulo, $test->id_test]) }}">Editar</a>
                        <form method="POST" action="{{ route('profesor.testEliminar.eliminar', $test->id_test) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td>No tienes tests...</td>
                </tr>
            @endforelse
        </tbody>
    </table>

<p><a href="{{ route('profesor.crearTest.mostrar', $modulo->id_modulo) }}">+ Crear test</a></p>

@endsection