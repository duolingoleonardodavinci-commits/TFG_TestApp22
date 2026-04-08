@extends('layouts.app')

@section('title', $modulo->ciclo . ' '. $modulo->modulo)

@section('content')
    <x-header />
    
    <h1>{{ $modulo->ciclo }}</h1>

    <img src="https://cdn.dribbble.com/userupload/24517939/file/original-1cb52f4e721cdc7b85654f501c99eb2f.png?resize=400x0">
   
@endsection