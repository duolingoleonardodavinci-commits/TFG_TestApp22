@extends('layouts.app')

@section('title', 'inicio')

@section('content')

    <h1>Duolingo</h1>

    <p>Landing</p>

    <a href="{{ route('inicio.mostrarLogin') }}">Iniciar sesión</a>
    <a href="{{ route('inicio.mostrarRegister') }}">Registrarse</a>

@endsection