@extends('layouts.app')

@section('title', 'Crearmodulo')

@section('content')
    <x-header />
    <x-errores />
    
    <form method="POST" action="{{ route('profesor.modulos.update', $modulo->id_modulo) }}">
        @include('usuario.profesor.modulo.partials.form')
    </form>

    <form method="POST" action="{{ route('profesor.modulos.destroy', $modulo->id_modulo) }}">
        @csrf
        @method('DELETE')
        <button type="submit">Eliminar</button>
    </form>
@endsection