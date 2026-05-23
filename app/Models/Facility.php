<?php

namespace App\Models;

use Carbon\Carbon;
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
        'opening_time',
        'closing_time',
        'lunch_start',
        'lunch_end',
        'lunch_mode',
        'operating_days',
    ];

    protected $casts = [
        'status_overridden' => 'boolean',
        'is_borrowable' => 'boolean',
        'operating_days' => 'array',
    ];

    public function attendanceLogs(): HasMany
    {
        return $this->hasMany(AttendanceLog::class);
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    public function getComputedStatusAttribute(): string
    {
        if ($this->status_overridden) {
            return $this->status ?: 'closed';
        }

        $now = now();

        $opening = $this->parseTime($this->opening_time);
        $closing = $this->parseTime($this->closing_time);

        if ($opening && $now->lt($opening)) {
            return 'closed';
        }

        if ($closing && $now->gte($closing)) {
            return 'closed';
        }

        $lunchStart = $this->parseTime($this->lunch_start);
        $lunchEnd = $this->parseTime($this->lunch_end);

        if (
            $this->lunch_mode !== 'disabled'
            && $lunchStart
            && $lunchEnd
            && $now->between($lunchStart, $lunchEnd->copy()->subSecond())
        ) {
            return 'lunch_break';
        }

        if ($this->hasActiveApprovedReservation()) {
            return 'in_use';
        }

        if ($this->hasUpcomingApprovedReservation()) {
            return 'reserved';
        }

        return 'open';
    }

    public function getStatusMessageAttribute(): string
    {
        return match ($this->computed_status) {
            'open' => 'Open now and ready for use.',
            'lunch_break' => 'Lunch break is in effect until ' . ($this->parseTime($this->lunch_end)?->format('h:i A') ?? '1:00 PM') . '. Request assistance if you need a key or help.',
            'closed' => 'Closed for the day. Reopens at ' . ($this->parseTime($this->opening_time)?->format('h:i A') ?? '7:00 AM') . '. Request assistance if the facility is locked.',
            'in_use' => 'This facility is currently in use. Please use the reservation or assistance options for help.',
            'reserved' => 'This facility is reserved for the next approved booking.',
            default => 'Status is currently unavailable.',
        };
    }

    public function getAssistanceRequiredAttribute(): bool
    {
        return in_array($this->computed_status, ['closed', 'lunch_break'], true);
    }

    public function getStatusLabelAttribute(): string
    {
        return ucwords(str_replace('_', ' ', $this->computed_status));
    }

    protected function parseTime(?string $value): ?Carbon
    {
        if (! $value) {
            return null;
        }

        try {
            return Carbon::today()->setTimeFromTimeString($value);
        } catch (\Throwable) {
            return null;
        }
    }

    protected function hasActiveApprovedReservation(): bool
    {
        return $this->reservations()
            ->where('status', 'approved')
            ->where('start_time', '<=', now())
            ->where('end_time', '>', now())
            ->exists();
    }

    protected function hasUpcomingApprovedReservation(): bool
    {
        return $this->reservations()
            ->where('status', 'approved')
            ->where('start_time', '>', now())
            ->exists();
    }
}
