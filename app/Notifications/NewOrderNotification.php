<?php

namespace App\Notifications;

use App\Models\Order;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewOrderNotification extends Notification
{
    use Queueable;

    public function __construct(public Order $order) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $isAdmin = $notifiable instanceof User;

        if ($isAdmin) {
            return (new MailMessage)
                ->subject("🛍️ Nuevo pedido #{$this->order->id} — FiftyOne")
                ->greeting('¡Nuevo pedido recibido!')
                ->line("**{$this->order->customer_name}** acaba de realizar un pedido.")
                ->line("**Total:** \${$this->order->total}")
                ->line("**Items:** {$this->order->items->count()} producto(s)")
                ->action('Ver pedido', url("/admin/orders/{$this->order->id}"))
                ->line('Ingresa al panel para gestionar el pedido.');
        }

        // Email al cliente
        return (new MailMessage)
            ->subject("✅ Confirmación de tu pedido #{$this->order->id} — FiftyOne")
            ->greeting("¡Hola, {$this->order->customer_name}!")
            ->line('Hemos recibido tu pedido correctamente.')
            ->line("**Número de pedido:** #{$this->order->id}")
            ->line("**Total:** \${$this->order->total}")
            ->line('Te notificaremos cuando el estado de tu pedido cambie.')
            ->line('Gracias por tu compra en FiftyOne.');
    }
}
