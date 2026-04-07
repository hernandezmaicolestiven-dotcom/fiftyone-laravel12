<?php

use App\Http\Middleware\AdminOnly;
use App\Http\Middleware\SanitizeInput;
use App\Http\Middleware\SecurityHeaders;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Guests en rutas admin → login admin; guests en rutas públicas → login cliente
        $middleware->redirectGuestsTo(function ($request) {
            if ($request->is('admin/*') || $request->is('admin')) {
                return route('admin.login');
            }
            return route('customer.login');
        });

        // Usuarios autenticados que visitan rutas guest redirigen según su rol
        $middleware->redirectUsersTo(function ($request) {
            $user = auth()->user();
            if ($user && $user->role === 'admin') {
                // Solo redirigir al dashboard si viene de rutas admin
                if ($request->is('admin/*') || $request->is('login') || $request->is('registro')) {
                    return route('admin.dashboard');
                }
                return '/';
            }
            return route('customer.account');
        });

        // Security headers en todas las respuestas web
        $middleware->web(append: [
            SecurityHeaders::class,
            SanitizeInput::class,
        ]);

        // Alias para verificar que solo admins accedan al panel
        $middleware->alias(['admin.only' => AdminOnly::class]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
