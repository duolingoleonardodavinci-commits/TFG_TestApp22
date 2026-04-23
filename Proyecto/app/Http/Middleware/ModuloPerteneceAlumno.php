<?php

namespace App\Http\Middleware;

use App\Models\Modulo;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ModuloPerteneceAlumno
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response {
        $modulo = $request->route('modulo');

        // Si no hay módulo, dejamos pasar (es opcional)

        if (!$modulo) {
            return $next($request);
        }

        $usuario = Auth::user();

        if (!$modulo instanceof Modulo) {
            $modulo = Modulo::findOrFail($modulo);
        }

        if (!$modulo->alumnos->contains('id_alumno', $usuario->id_usuario)) {
            return redirect()
                ->route('inicio.dashboardAlumno.mostrar')
                ->withErrors(['error' => 'No tienes acceso a este módulo']);
        }

        return $next($request);
    }
}
