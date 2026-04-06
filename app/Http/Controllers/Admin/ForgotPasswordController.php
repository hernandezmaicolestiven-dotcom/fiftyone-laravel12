<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    public function showForm()
    {
        return view('admin.auth.forgot-password');
    }

    public function sendLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        try {
            $status = Password::sendResetLink($request->only('email'));

            return $status === Password::RESET_LINK_SENT
                ? back()->with('success', 'Te enviamos el enlace de recuperación. Revisa tu correo.')
                : back()->withErrors(['email' => 'No encontramos una cuenta con ese email.']);
        } catch (\Exception $e) {
            Log::error('Error enviando reset link', ['error' => $e->getMessage()]);

            // En desarrollo con MAIL_MAILER=log, el link se guarda en storage/logs/laravel.log
            if (app()->isLocal()) {
                return back()->with('success', 'Enlace generado. En desarrollo revisa storage/logs/laravel.log para ver el link.');
            }

            return back()->withErrors(['email' => 'Error al enviar el email. Contacta al administrador.']);
        }
    }
}
