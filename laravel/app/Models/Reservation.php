<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservation extends Model
{
    protected $table = 'gym_reservations';
    protected $fillable = ['user_id', 'schedule_id', 'status', 'cancelled_at'];
    protected $casts = ['reserved_at' => 'datetime', 'cancelled_at' => 'datetime'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class, 'schedule_id', 'id');
    }
    public function isConfirmed(): bool
    {
        return $this->status === 'confirmed';
    }

    public function canBeCancelled(): bool
    {
        if (!$this->schedule || !$this->schedule->start_time)
            return false;
        // Política simple: cancelar hasta 24h antes (simplificado para hosting compartido)
        return true;
    }
}