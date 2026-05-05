@extends('layouts.app')

@section('title', 'Crearmodulo')

@section('content')
    <x-header />
    <x-errores />
    
    <form method="POST" action="{{ route('profesor.modulos.store') }}">
        @include('usuario.profesor.modulo.partials.form')
    </form>
@endsection