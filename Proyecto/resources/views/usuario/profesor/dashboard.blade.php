@extends('layouts.app')

@section('title', 'dashboard profesor')

@section('content')
    <x-header />
    <x-errores />
    
    <p>Dashboard profesor {{ Auth::user()->nombre }}</p>


    @if (!$moduloActual)
        
        <p>¿Primera vez? Crea un módulo</p>
        <p><a href="{{ route('profesor.modulos.create') }}">Crear nuevo modulo</a></p>

    @else
        <x-modulo-nav :moduloActual="$moduloActual" />
    @endif
@endsection