<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservation extends Model
{
    protected $fillable = [
        'user_id',
        'facility_id',
        'start_time',
        'end_time',
        'status',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function facility(): BelongsTo
    {
        return $this->belongsTo(Facility::class);
    }

    /**
     * Check if the reservation can be cancelled
     */
    public function canBeCancelled(): bool
    {
        // Can't cancel if already started (within 30 minutes of start time)
        if ($this->start_time->copy()->subMinutes(30)->isPast()) {
            return false;
        }

        // Can only cancel pending or approved reservations
        return in_array($this->status, ['pending', 'approved']);
    }

    /**
     * Cancel the reservation
     */
    public function cancel(): bool
    {
        if (!$this->canBeCancelled()) {
            return false;
        }

        return $this->delete();
    }

    /**
     * Scope for active reservations
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'approved')
                    ->where('end_time', '>', now());
    }

    /**
     * Scope for pending reservations
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}
