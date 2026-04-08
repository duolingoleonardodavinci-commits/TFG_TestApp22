@extends('layouts.app')

@section('title', 'dashboard alumno')

@section('content')
    <x-header />
    
    Alumno

    <p><a href="{{ route('alumno.mostrarModulos') }}">Modulos</a></p>
@endsection