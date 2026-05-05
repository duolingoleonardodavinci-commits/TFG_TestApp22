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

    <!---------------------------------------------------------------------------------------------------------------------------------------------------------------->
    <!-- test -->
    <div>
        <p><a href="{{ route('alumno.tests.examen', $moduloActual->id_modulo) }}">Examenes</a></p>
        <p><a href="{{ route('alumno.tests.practica', $moduloActual->id_modulo) }}">Ejercicios</a></p>
    </div>


@endsection