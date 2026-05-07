@extends('layouts.app')

@section('title', 'Unirse a un Módulo')

@section('content')
    <x-errores />
    
    <div style="max-width: 600px; margin: 0 auto;">
        <h1 style="text-align: left; margin-bottom: 2rem;">Explorar Módulos</h1>

        <div style="display: grid; gap: 1rem;">
            @forelse ($modulos as $modulo)
                <a href="{{ route('alumno.matriculas.create', $modulo) }}" style="text-decoration: none; color: inherit;">
                    <div class="form-card" style="padding: 1.5rem; margin-bottom: 0; transition: transform 0.2s, box-shadow 0.2s;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='var(--shadow)'" onmouseout="this.style.transform='none'; this.style.boxShadow='var(--shadow-sm)'">
                        
                        <h3 style="color: var(--color-modulo); margin-bottom: 0.25rem;">{{ $modulo->ciclo }} - {{ $modulo->modulo }}</h3>
                        <p style="color: var(--tx-3); margin: 0; font-size: 0.95rem;">
                            👨‍🏫 Profesor: <strong>{{ $modulo->profesor->usuario->nombre }} {{ $modulo->profesor->usuario->apellidos }}</strong>
                        </p>
                        
                    </div>
                </a>
            @empty
                <div class="form-card" style="text-align: center; padding: 3rem 2rem;">
                    <p style="color: var(--tx-3); margin: 0;">No hay módulos creados en la plataforma todavía.</p>
                </div>
            @endforelse
        </div>
        
        <div style="margin-top: 2rem; text-align: center;">
            <a href="/" class="btn btn-secondary">Volver al Inicio</a>
        </div>
    </div>
@endsection