<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminOnly
{
    public function handle(Request $request, Closure $next): Response
    {
        // Si no está autenticado → redirigir al login de admin
        if (! auth()->check()) {
            return redirect()->route('admin.login');
        }

        // Solo admin, superadmin y colaborador pueden entrar al panel
        if (! in_array(auth()->user()->role, ['admin', 'superadmin', 'colaborador'])) {
            return redirect('/mi-cuenta')->with('error', 'No tienes acceso al panel de administración.');
        }

        return $next($request);
    }
}
