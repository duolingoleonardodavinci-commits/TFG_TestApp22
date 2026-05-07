@extends('layouts.app')

@section('title', 'Tests')

@section('content')
    <x-errores />

    <h1>Listado de Tests</h1>

    <div style="text-align: right; margin-bottom: 2rem;">
        <a href="{{ route('profesor.tests.create', $modulo->id_modulo) }}">
            <button type="button" class="btn btn-primary">+ Crear nuevo test</button>
        </a>
    </div>

    <div x-data="{
            busqueda: '',
            normalizar(texto) {
                return texto.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '').replace(/[^\w\s]/g, '').trim();
            },
            coincide(nombre, tipo) {
                let b = this.normalizar(this.busqueda);
                return b === '' || this.normalizar(nombre).includes(b) || this.normalizar(tipo).includes(b);
            }
        }">

        <div class="form-group" style="margin-bottom: 2rem;">
            <input type="search" x-model="busqueda" class="form-input" placeholder="Buscar por nombre o tipo de test...">
        </div>

        <div class="table-container">
            <table class="main-table">
                <thead>
                    <tr>
                        <th>Nombre del Test</th>
                        <th>Tipo</th>
                        <th style="text-align: right;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tests as $test)
                        <tr x-show="coincide({{ Js::from($test->nombre) }}, {{ Js::from($test->tipo) }})">
                            <td style="font-weight: 600;">{{ $test->nombre }}</td>
                            <td>
                                <span style="text-transform: capitalize; padding: 0.25rem 0.75rem; border-radius: 6px; font-size: 0.8rem; {{ $test->tipo == 'examen' ? 'background: #fee2e2; color: #dc2626;' : 'background: #d1fae5; color: #059669;' }}">
                                    {{ $test->tipo }}
                                </span>
                            </td>
                            <td style="text-align: right;">
                                <div style="display: flex; gap: 0.5rem; justify-content: flex-end;">
                                    <a href="{{ route('profesor.tests.iniciar', [$modulo->id_modulo, $test->id_test]) }}" class="btn btn-secondary">Probar</a>
                                    <a href="{{ route('profesor.tests.edit', [$modulo->id_modulo, $test->id_test]) }}" class="btn btn-secondary">Editar</a>
                                    <form method="POST" action="{{ route('profesor.tests.destroy', [$modulo->id_modulo, $test->id_test]) }}" style="margin:0;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('¿Eliminar test?')">Eliminar</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" style="text-align: center; padding: 3rem; color: var(--tx-4);">
                                No tienes tests creados para este módulo.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection