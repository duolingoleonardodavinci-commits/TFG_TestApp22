@extends('layouts.app')

@section('title', 'dashboard profesor')

@section('content')
    <x-header />
    
    <p>Dashboard profesor {{ Auth::user()->nombre }}</p>

    @if (session('error'))
        <div>
            {{ session('error') }}
        </div>
    @endif

    @if (!$moduloActual)
        
        <p>¿Primera vez? Crea un módulo</p>
        <p><a href="{{ route('profesor.crearModulo.mostrar') }}">Crear nuevo modulo</a></p>

    @else
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
    @endif
@endsection