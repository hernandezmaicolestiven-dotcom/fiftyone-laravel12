<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderStatusChangedNotification extends Notification
{
    use Queueable;

    public function __construct(public Order $order, public string $oldStatus) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $icons = [
            'confirmed' => '✅',
            'shipped' => '🚚',
            'delivered' => '📦',
            'cancelled' => '❌',
            'pending' => '⏳',
        ];
        $icon = $icons[$this->order->status] ?? '🔔';

        return (new MailMessage)
            ->subject("{$icon} Tu pedido #{$this->order->id} fue actualizado — FiftyOne")
            ->greeting("¡Hola, {$this->order->customer_name}!")
            ->line("El estado de tu pedido **#{$this->order->id}** ha cambiado.")
            ->line("**Estado anterior:** {$this->order->getStatusLabel($this->oldStatus)}")
            ->line("**Estado actual:** {$this->order->status_label}")
            ->when($this->order->status === 'shipped', fn ($m) => $m->line('Tu pedido está en camino. ¡Pronto lo recibirás!'))
            ->when($this->order->status === 'delivered', fn ($m) => $m->line('Tu pedido fue entregado. ¡Esperamos que lo disfrutes!'))
            ->when($this->order->status === 'cancelled', fn ($m) => $m->line('Si tienes dudas sobre la cancelación, contáctanos.'))
            ->line('Gracias por comprar en FiftyOne.');
    }
}
