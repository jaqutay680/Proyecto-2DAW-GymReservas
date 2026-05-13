<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'gym_users';

    // 🔹 Campos asignables masivamente (CRÍTICO)
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'membership_status',
        'avatar_url',
        'plan_type',
        'wallet_balance',
        'dni',              // ✅ AÑADIDO
        'birth_date',       // ✅ AÑADIDO
        'free_trial_used',  // ✅ AÑADIDO
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'birth_date' => 'date',
            'wallet_balance' => 'decimal:2',
            'free_trial_used' => 'boolean',
        ];
    }

    // 🔹 Relaciones
    public function membership(): HasOne
    {
        return $this->hasOne(Membership::class, 'user_id', 'id');
    }
    public function trainer(): HasOne
    {
        return $this->hasOne(Trainer::class, 'user_id', 'id');
    }
    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'user_id', 'id');
    }

    // 🔹 Helpers de rol
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
    public function isTrainer(): bool
    {
        return $this->role === 'trainer';
    }
    public function isClient(): bool
    {
        return $this->role === 'cliente';
    }

    // 🔹 Helpers de plan y límites
    public function getWeeklyLimit(): int
    {
        return match ($this->plan_type ?? 'free') {
            'premium' => 999,  // Sin límite práctico
            'basico' => 5,
            default => 1,  // Free: solo 1 reserva total (día de prueba)
        };
    }

    public function hasUnlimitedReservations(): bool
    {
        return $this->plan_type === 'premium';
    }

    public function canUseFreeTrial(): bool
    {
        return ($this->plan_type ?? 'free') === 'free' && !$this->free_trial_used;
    }

    public function isOlderThan(int $minAge): bool
    {
        if (!$this->birth_date)
            return false;
        return $this->birth_date->age >= $minAge;
    }
}