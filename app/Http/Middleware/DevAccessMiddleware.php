<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class DevAccessMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Excluir rutas de login de desarrollo
        if ($request->routeIs('dev-login') || $request->routeIs('dev-login.store')) {
            return $next($request);
        }

        // Si el usuario ya está autenticado, permitir acceso
        if (Auth::check()) {
            return $next($request);
        }

        // Si no está autenticado, redirigir al login de desarrollo
        return redirect()->route('dev-login');
    }
}
