@extends('layouts.app')

@section('title', 'Unirse modulo')

@section('content')
    <x-header />
    <x-errores />
    
    <h1>Unirse a un modulo</h1>

    @foreach ($modulos as $modulo)
        <p><a href="{{ route('alumno.matriculas.create', $modulo) }}">{{$modulo->ciclo}} {{$modulo->modulo}} {{$modulo->profesor->usuario->nombre}} {{$modulo->profesor->usuario->apellidos}}</a></p>
    @endforeach

@endsection