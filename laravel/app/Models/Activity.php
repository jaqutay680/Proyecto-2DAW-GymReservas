<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Activity extends Model
{
    protected $table = 'gym_activities';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'difficulty',
        'min_age',
        'image_url'
    ];

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class, 'activity_id', 'id');
    }
}