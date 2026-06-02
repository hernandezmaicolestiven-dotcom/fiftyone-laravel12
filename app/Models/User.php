<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\ResetPasswordNotification;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'avatar',
        'password',
        'role',
        'phone',
        'default_address',
        'default_city',
        'last_login_at',
        'last_login_ip',
        'failed_login_attempts',
        'locked_until',
        'password_changed_at',
        'force_password_change',
    ];

    /** Verifica si el usuario es administrador */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /** Pedidos del cliente */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /** Sesiones activas del usuario */
    public function sessions()
    {
        return $this->hasMany(UserSession::class);
    }

    /** Verificar si la cuenta está bloqueada */
    public function isLocked(): bool
    {
        return $this->locked_until && $this->locked_until->isFuture();
    }

    /** Bloquear cuenta por intentos fallidos */
    public function lockAccount(int $minutes = 30): void
    {
        $this->update([
            'locked_until' => now()->addMinutes($minutes),
            'failed_login_attempts' => 0,
        ]);
    }

    /** Resetear intentos fallidos */
    public function resetFailedAttempts(): void
    {
        $this->update(['failed_login_attempts' => 0]);
    }

    /** Incrementar intentos fallidos */
    public function incrementFailedAttempts(): void
    {
        $this->increment('failed_login_attempts');
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_login_at' => 'datetime',
            'locked_until' => 'datetime',
            'password_changed_at' => 'datetime',
            'force_password_change' => 'boolean',
        ];
    }

    /**
     * Enviar notificación de restablecimiento de contraseña usando colas
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }
}
