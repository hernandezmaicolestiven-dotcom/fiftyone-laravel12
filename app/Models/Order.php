<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_name', 'customer_email', 'customer_phone',
        'notes', 'total', 'status',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending'   => 'Pendiente',
            'confirmed' => 'Confirmado',
            'shipped'   => 'Enviado',
            'delivered' => 'Entregado',
            'cancelled' => 'Cancelado',
            default     => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending'   => 'amber',
            'confirmed' => 'blue',
            'shipped'   => 'indigo',
            'delivered' => 'emerald',
            'cancelled' => 'red',
            default     => 'gray',
        };
    }
}
