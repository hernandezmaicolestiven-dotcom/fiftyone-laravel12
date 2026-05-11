<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WompiPayment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_id',
        'transaction_id',
        'payment_link_id',
        'payment_link_url',
        'reference',
        'amount',
        'amount_in_cents',
        'currency',
        'status',
        'status_message',
        'payment_method',
        'payment_method_type',
        'customer_email',
        'customer_data',
        'integrity_signature',
        'wompi_response',
        'webhook_received_at',
        'webhook_data',
        'metadata',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'amount_in_cents' => 'integer',
        'customer_data' => 'array',
        'wompi_response' => 'array',
        'webhook_data' => 'array',
        'metadata' => 'array',
        'webhook_received_at' => 'datetime',
    ];

    // Relaciones
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'PENDING');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'APPROVED');
    }

    public function scopeDeclined($query)
    {
        return $query->where('status', 'DECLINED');
    }

    // Helpers
    public function isPending(): bool
    {
        return $this->status === 'PENDING';
    }

    public function isApproved(): bool
    {
        return $this->status === 'APPROVED';
    }

    public function isDeclined(): bool
    {
        return $this->status === 'DECLINED';
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'PENDING' => 'Pendiente',
            'APPROVED' => 'Aprobado',
            'DECLINED' => 'Rechazado',
            'VOIDED' => 'Anulado',
            'ERROR' => 'Error',
            default => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'PENDING' => 'amber',
            'APPROVED' => 'emerald',
            'DECLINED' => 'red',
            'VOIDED' => 'gray',
            'ERROR' => 'red',
            default => 'gray',
        };
    }
}
