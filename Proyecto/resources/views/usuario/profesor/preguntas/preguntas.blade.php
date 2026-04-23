@extends('layouts.app')

@section('title', 'preguntas')

@section('content')
    <x-header />
    <x-errores />
    <p><a href="{{ route('profesor.crearPregunta.mostrar', $modulo->id_modulo) }}">+ Crear preguntas</a></p>

@endsection