<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Facility extends Model
{
    protected $fillable = [
        'room_name',
        'building',
        'capacity',
        'is_borrowable',
        'current_occupancy',
        'status',
        'status_overridden',
    ];

    protected $casts = [
        'status_overridden' => 'boolean',
    ];

    public function attendanceLogs(): HasMany
    {
        return $this->hasMany(AttendanceLog::class);
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }
}
