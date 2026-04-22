@extends('layouts.app')

@section('title', 'dashboard profesor')

@section('content')
    <x-header />
    
    Dashboard profesor {{ Auth::user()->nombre }}

    @if (session('error'))
        <div>
            {{ session('error') }}
        </div>
    @endif

    <h1>Modulos</h1>

    <select onchange="location = this.options[this.selectedIndex].value;">

        @forelse (Auth::user()->profesor->modulos as $modulo)

            <option value="{{ route('inicio.dashboardProfesor.mostrar', $modulo->id_modulo) }}"></option>

        @empty
            <p>No tiene modulos</p>
        @endforelse

    </select>

    <p><a href="{{ route('profesor.crearModulo.mostrar') }}">Crear nuevo modulo</a></p>
@endsection