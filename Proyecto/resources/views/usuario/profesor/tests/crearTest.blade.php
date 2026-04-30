@extends('layouts.app')

@section('title', 'tests')

@section('content')
    <x-header />
    <x-errores />
    
    <form method="POST" action="{{ route('profesor.tests.store', $modulo->id_modulo) }}">
        @include('usuario.profesor.tests.partials.form')
    </form>
@endsection