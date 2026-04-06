<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;

/**
 * MessageController — Chat interno entre administradores.
 * Mensajes simples con estado de lectura.
 */
class MessageController extends Controller
{
    public function index()
    {
        // Marcar todos como leídos al abrir el chat
        Message::where('is_read', false)
            ->where('user_id', '!=', auth()->id())
            ->update(['is_read' => true]);

        $messages = Message::with('user')->latest()->take(50)->get()->reverse()->values();

        return view('admin.messages.index', compact('messages'));
    }

    public function store(Request $request)
    {
        $request->validate(['body' => 'required|string|max:1000']);

        $message = Message::create([
            'user_id' => auth()->id(),
            'body' => $request->body,
        ]);

        $message->load('user');

        if ($request->expectsJson()) {
            return response()->json([
                'id' => $message->id,
                'body' => $message->body,
                'user' => $message->user->name,
                'avatar' => strtoupper(substr($message->user->name, 0, 1)),
                'created_at' => $message->created_at->format('H:i'),
                'mine' => true,
            ]);
        }

        return back();
    }

    /** Polling: retorna mensajes nuevos desde un ID dado */
    public function poll(Request $request)
    {
        $since = $request->integer('since', 0);
        $messages = Message::with('user')
            ->where('id', '>', $since)
            ->latest()
            ->take(20)
            ->get()
            ->reverse()
            ->values()
            ->map(fn ($m) => [
                'id' => $m->id,
                'body' => $m->body,
                'user' => $m->user->name,
                'avatar' => strtoupper(substr($m->user->name, 0, 1)),
                'created_at' => $m->created_at->format('H:i'),
                'mine' => $m->user_id === auth()->id(),
            ]);

        return response()->json($messages);
    }

    /** Conteo de mensajes no leídos para la campana */
    public static function unreadCount(): int
    {
        return Message::where('is_read', false)
            ->where('user_id', '!=', auth()->id())
            ->count();
    }
}
