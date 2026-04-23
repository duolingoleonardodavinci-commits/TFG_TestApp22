@extends('layouts.app')

@section('title', 'tests')

@section('content')
    <x-header />
    <p><a href="{{ route('profesor.crearTest.mostrar', $modulo->id_modulo) }}">+ Crear test</a></p>

@endsection