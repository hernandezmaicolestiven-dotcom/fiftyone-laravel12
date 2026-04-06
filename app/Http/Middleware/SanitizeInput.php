<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SanitizeInput
{
    /** Campos que NO deben sanitizarse (contraseñas, tokens, emails de auth) */
    protected array $except = ['password', 'password_confirmation', '_token', 'email'];

    public function handle(Request $request, Closure $next): Response
    {
        $input = $request->except($this->except);
        array_walk_recursive($input, function (&$value) {
            if (is_string($value)) {
                // Strip tags y caracteres de control peligrosos
                $value = strip_tags(trim($value));
                // Eliminar null bytes
                $value = str_replace("\0", '', $value);
            }
        });
        $request->merge($input);

        return $next($request);
    }
}
