@extends('layouts.app')

@section('title', 'Crear Módulo')

@section('content')
    <x-errores />
    
    <div style="max-width: 600px; margin: 0 auto;">
        <form method="POST" action="{{ route('profesor.modulos.store') }}" class="form-card">
            @include('usuario.profesor.modulo.partials.form')
        </form>
    </div>
@endsection