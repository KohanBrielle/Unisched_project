<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Schema;

class BorrowedEquipment extends Model
{
    protected $table = 'borrowed_equipments';

    protected $fillable = [
        'user_id',
        'equipment_name',
        'borrowed_at',
        'return_date',
        'returned_at',
        'status',
        'is_approved',
        'return_requested',
    ];

    protected $casts = [
        'borrowed_at' => 'datetime',
        'return_date' => 'date',
        'returned_at' => 'datetime',
        'is_approved' => 'boolean',
        'return_requested' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if the equipment can be returned
     */
    public function canBeReturned(): bool
    {
        return $this->status === 'borrowed';
    }

    /**
     * Mark equipment as returned
     */
    public function markAsReturned(): bool
    {
        if (!$this->canBeReturned()) {
            return false;
        }

        $this->status = 'returned';

        if (Schema::hasColumn($this->getTable(), 'returned_at')) {
            $this->returned_at = now();
        }

        return $this->save();
    }

    /**
     * Check if equipment is overdue
     */
    public function isOverdue(): bool
    {
        return $this->status === 'borrowed' && $this->return_date->isPast();
    }

    /**
     * Scope for borrowed equipment
     */
    public function scopeBorrowed($query)
    {
        return $query->where('status', 'borrowed');
    }

    /**
     * Scope for overdue equipment
     */
    public function scopeOverdue($query)
    {
        return $query->where('status', 'borrowed')
                    ->where('return_date', '<', now());
    }
}
