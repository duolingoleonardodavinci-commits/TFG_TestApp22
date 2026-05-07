@extends('layouts.app')

@section('title', 'Historial')

@section('content')
    <x-errores />

    <h1>Historial de Puntuaciones</h1>

    <div x-data="{
            busqueda: '',
            get parsed() {
                let tokens = this.busqueda.trim().toLowerCase().split(/\s+/).filter(Boolean);
                let extraer = (prefijo) => (tokens.find(t => t.startsWith(prefijo + ':')) ?? '').slice(prefijo.length + 1);
                return {
                    texto:      tokens.filter(t => !t.includes(':')).join(' '),
                    nombre:     extraer('nombre'),
                    test:       extraer('test'),
                    tipo:       extraer('tipo'),
                    puntuacion: extraer('puntuacion'),
                    fecha:      extraer('fecha'),
                };
            },
            normalizar(texto) {
                return texto.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '').replace(/[^\w\s]/g, '').trim();
            },
            coincide(nombre_completo, test, tipo, puntuacion, fecha) {
                let p = this.parsed;
                let n = this.normalizar(nombre_completo);
                let t = this.normalizar(test);
                if (p.texto && !n.includes(this.normalizar(p.texto)) && !t.includes(this.normalizar(p.texto))) return false;
                if (p.nombre && !n.includes(this.normalizar(p.nombre))) return false;
                if (p.test && !t.includes(this.normalizar(p.test))) return false;
                if (p.tipo && !tipo.toLowerCase().includes(p.tipo)) return false;
                if (p.puntuacion && !String(puntuacion).startsWith(p.puntuacion)) return false;
                if (p.fecha && !fecha.includes(p.fecha)) return false;
                return true;
            }
        }">

        <div class="form-card" style="margin-bottom: 2rem;">
            <div class="form-group">
                <label class="form-label">Filtrar historial</label>
                <input type="search" x-model="busqueda" class="form-input" placeholder="Ej: nombre:carlos test:unidad1 puntuacion:8">
            </div>
        </div>

        <div class="table-container">
            <table class="main-table">
                <thead>
                    <tr>
                        <th>Alumno</th>
                        <th>Test</th>
                        <th>Tipo</th>
                        <th>Nota</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($puntuaciones as $p)
                        @php
                            $nc    = $p->alumno->usuario->nombre . ' ' . $p->alumno->usuario->apellidos;
                            $punt  = number_format((float) $p->puntuacion, 2);
                            $fecha = $p->fecha ? \Carbon\Carbon::parse($p->fecha)->format('Y-m-d H:i') : 'N/A';
                        @endphp
                        <tr x-show="coincide(
                                {{ Js::from($nc) }},
                                {{ Js::from($p->test->nombre) }},
                                {{ Js::from($p->test->tipo) }},
                                {{ Js::from($punt) }},
                                {{ Js::from($fecha) }}
                            )">
                            <td style="font-weight: 500;">{{ $nc }}</td>
                            <td>{{ $p->test->nombre }}</td>
                            <td><span style="font-size: 0.8rem; opacity: 0.7;">{{ strtoupper($p->test->tipo) }}</span></td>
                            <td style="font-family: var(--mono); font-weight: 700; color: var(--color-modulo);">{{ $punt }}</td>
                            <td style="font-size: 0.8rem; color: var(--tx-3);">{{ $fecha }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection