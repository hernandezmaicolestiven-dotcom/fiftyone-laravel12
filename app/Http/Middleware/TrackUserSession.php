<?php

namespace App\Http\Middleware;

use App\Models\UserSession;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TrackUserSession
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            $sessionId = session()->getId();
            $userAgent = $request->userAgent() ?? '';

            // Buscar o crear la sesión
            $userSession = UserSession::updateOrCreate(
                ['session_id' => $sessionId],
                [
                    'user_id' => $user->id,
                    'ip_address' => $request->ip(),
                    'user_agent' => $userAgent,
                    'device_type' => UserSession::detectDevice($userAgent),
                    'browser' => UserSession::detectBrowser($userAgent),
                    'platform' => UserSession::detectPlatform($userAgent),
                    'last_activity' => now(),
                    'is_current' => true,
                ]
            );

            // Marcar todas las demás sesiones como no actuales
            UserSession::where('user_id', $user->id)
                ->where('session_id', '!=', $sessionId)
                ->update(['is_current' => false]);

            // Limpiar sesiones antiguas (más de 30 días)
            UserSession::where('user_id', $user->id)
                ->where('last_activity', '<', now()->subDays(30))
                ->delete();
        }

        return $next($request);
    }
}
