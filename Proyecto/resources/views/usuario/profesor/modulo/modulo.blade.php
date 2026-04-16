@extends('layouts.app')

@section('title', 'modulo')

@section('content')
    <x-header />
    
    <h1> {{ $modulo->ciclo }} {{ $modulo->modulo }} </h1>

    <p>Clave matriculacion: {{ $modulo->clave_matriculacion }}</p>

    <p><a href="{{ route('profesor.preguntas.mostrar', $modulo->id_modulo) }}">Preguntas</a></p>
@endsection