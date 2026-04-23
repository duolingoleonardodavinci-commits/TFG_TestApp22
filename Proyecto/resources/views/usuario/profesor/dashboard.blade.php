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
        <x-modulo-nav :moduloActual="$moduloActual" />
    @endif
@endsection