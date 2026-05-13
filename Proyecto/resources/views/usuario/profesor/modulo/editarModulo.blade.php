@extends('layouts.app')

@section('title', 'Editar Módulo')

@section('content')
    <x-errores />
    
    <div style="max-width: 600px; margin: 0 auto;">
        <form method="POST" action="{{ route('profesor.modulos.update', $modulo->id_modulo) }}" class="form-card">
            @include('usuario.profesor.modulo.partials.form')
        </form>

        <div class="form-card" style="border-color: #FCA5A5; background-color: #FEF2F2; margin-top: 2rem;">
            <h3 style="color: var(--error); margin-bottom: 0.5rem; font-size: 1.2rem;">Zona Peligrosa</h3>
            <p style="color: var(--error); margin-bottom: 1.5rem; font-size: 0.95rem; opacity: 0.9;">
                Una vez que elimines este módulo, se perderán todos los tests, preguntas y datos de los alumnos asociados a él. Esta acción no se puede deshacer.
            </p>
            
            <form method="POST" action="{{ route('profesor.modulos.destroy', $modulo->id_modulo) }}" onsubmit="return confirm('¿Estás absolutamente seguro de que quieres eliminar este módulo por completo?');" style="margin: 0; padding: 0; background: transparent; border: none; box-shadow: none;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" style="width: 100%;">
                    Eliminar Módulo Definitivamente
                </button>
            </form>
        </div>
    </div>
@endsection