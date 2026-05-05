@extends('layouts.app')

@section('title', 'dashboard alumno')

@section('content')
    <x-header />
    <x-errores />
    
    <p>Dashboard alumno {{ Auth::user()->nombre }}</p>

    @if (Auth::user()->alumno->modulos->isEmpty())
        
        <p>¿Primera vez? Únete a un módulo</p>
        <p><a href="{{ route('alumno.matriculas.index') }}">Unirse a un nuevo modulo</a></p>

    @else
        @if (!$moduloActual)
            <p>Selecciona un módulo</p>
        @endif

        <x-modulo-nav-alumno :moduloActual="$moduloActual" />
    @endif
@endsection