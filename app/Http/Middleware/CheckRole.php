<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        
        // Si no se especifican roles, permitir acceso
        if (empty($roles)) {
            return $next($request);
        }

        // Verificar si el usuario tiene uno de los roles permitidos
        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        // Si no tiene permisos, redirigir con mensaje de error
        return redirect()->route('dashboard')
            ->with('error', 'No tienes permisos para acceder a esta sección.');
    }
}
