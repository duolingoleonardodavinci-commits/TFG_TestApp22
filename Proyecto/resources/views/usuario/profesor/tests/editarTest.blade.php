@extends('layouts.app')

@section('title', 'tests')

@section('content')
    <x-header />
    <x-errores />
    
    <form method="POST" action="{{ route('profesor.tests.update', [$modulo->id_modulo, $test->id_test]) }}">
        @include('usuario.profesor.tests.partials.form', ['test' => $test])
    </form>
@endsection