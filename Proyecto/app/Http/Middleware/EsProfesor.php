<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EsProfesor
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->esProfesor()) {
            return $next($request);
        }

        return redirect()->route('auth.login');
    }
}