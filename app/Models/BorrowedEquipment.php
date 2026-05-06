<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BorrowedEquipment extends Model
{
    protected $fillable = [
        'user_id',
        'equipment_name',
        'borrowed_at',
        'return_date',
        'status',
    ];

    protected $casts = [
        'borrowed_at' => 'datetime',
        'return_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
