<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'customer_name', 'customer_email', 'customer_phone',
        'shipping_address', 'city', 'notes', 'total', 'status',
        'payment_method', 'payment_status', 'payment_details',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return $this->getStatusLabel($this->status);
    }

    public function getStatusLabel(string $status): string
    {
        return match ($status) {
            'pending' => 'Pendiente',
            'confirmed' => 'Confirmado',
            'shipped' => 'Enviado',
            'delivered' => 'Entregado',
            'cancelled' => 'Cancelado',
            default => $status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'amber',
            'confirmed' => 'blue',
            'shipped' => 'indigo',
            'delivered' => 'emerald',
            'cancelled' => 'red',
            default => 'gray',
        };
    }

    public function getPaymentMethodLabelAttribute(): string
    {
        return match ($this->payment_method) {
            'nequi' => 'Nequi',
            'daviplata' => 'Daviplata',
            'pse' => 'PSE',
            'bancolombia' => 'Bancolombia',
            'efecty' => 'Efecty',
            'tarjeta' => 'Tarjeta',
            default => $this->payment_method ?? 'No especificado',
        };
    }

    public function getPaymentStatusLabelAttribute(): string
    {
        return match ($this->payment_status) {
            'pending' => 'Pendiente',
            'approved' => 'Aprobado',
            'rejected' => 'Rechazado',
            'cancelled' => 'Cancelado',
            default => $this->payment_status,
        };
    }
}
