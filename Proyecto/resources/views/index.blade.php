@extends('layouts.app')

@section('title', 'inicio')

@section('content')

    <h1>Duolingo</h1>

    <p>Landing</p>

    <a href="{{ route('inicio.login.mostrar') }}"><button type="button">Iniciar sesión</button></a>
    <a href="{{ route('inicio.register.mostrar') }}"><button type="button">Registrarse</button></a>

@endsection