<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EquipmentLog extends Model
{
    protected $fillable = [
        'equipment_id',
        'action',
        'borrowing_id',
        'stock_before',
        'stock_after',
        'quantity',
        'performed_by',
        'notes',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    // Nonaktifkan updated_at karena hanya perlu created_at untuk log
    const UPDATED_AT = null;

    public function equipment(): BelongsTo
    {
        return $this->belongsTo(Equipment::class);
    }

    public function borrowing(): BelongsTo
    {
        return $this->belongsTo(Borrowing::class);
    }

    public function performedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'performed_by');
    }

    public function getActionLabelAttribute(): string
    {
        return match($this->action) {
            'borrow' => 'Peminjaman',
            'return' => 'Pengembalian',
            'add_stock' => 'Penambahan Stok',
            'reduce_stock' => 'Pengurangan Stok',
            'maintenance' => 'Maintenance',
            default => $this->action,
        };
    }
}
