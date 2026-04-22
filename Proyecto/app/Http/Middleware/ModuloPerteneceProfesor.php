<?php

namespace App\Http\Middleware;

use App\Models\Modulo;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ModuloPerteneceProfesor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $usuario = Auth::user();

        // Obtener el módulo desde la ruta (route model binding)
        $modulo = $request->route('modulo');

        if (!$modulo instanceof Modulo) {
            $modulo = Modulo::findOrFail($modulo);
        }

        if ($modulo->id_profesor !== $usuario->id_usuario) {
            return redirect()
                ->route('inicio.dashboard.mostrar')
                ->with('error', 'No tienes acceso a este módulo.'); // para algún futuro mensaje de error en pantalla
        }

        return $next($request);
    }
}
