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
                        <a href="{{ route('profesor.tests.edit', [$modulo->id_modulo, $test->id_test]) }}"><button type="button">Editar</button></a>
                        <form method="POST" action="{{ route('profesor.tests.destroy', [$modulo->id_modulo, $test->id_test]) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Eliminar</button>
                        </form>
                        <a href="{{ route('tests.iniciar', [$modulo->id_modulo, $test->id_test]) }}"><button>Probar</button></a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td>No tienes tests...</td>
                </tr>
            @endforelse
        </tbody>
    </table>

<p><a href="{{ route('profesor.tests.create', $modulo->id_modulo) }}"><button type="button">+ Crear test</button></a></p>

@endsection