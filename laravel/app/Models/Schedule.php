<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Schedule extends Model
{
    protected $table = 'gym_schedules';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'activity_id',
        'trainer_id',
        'day_of_week',
        'start_time',
        'end_time',
        'room',
        'capacity'
    ];

    protected $casts = [
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
    ];

    public function activity(): BelongsTo
    {
        return $this->belongsTo(Activity::class, 'activity_id', 'id');
    }

    public function trainer(): BelongsTo
    {
        return $this->belongsTo(Trainer::class, 'trainer_id', 'id');
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'schedule_id', 'id');
    }

    public function getAvailableSpotsAttribute(): int
    {
        return $this->capacity - $this->reservations->count();
    }
}