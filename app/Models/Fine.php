<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Fine extends Model
{
    protected $fillable = [
        'borrowing_id',
        'amount',
        'days_late',
        'rate_per_day',
        'is_paid',
        'paid_at',
        'received_by',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'rate_per_day' => 'decimal:2',
        'is_paid' => 'boolean',
        'paid_at' => 'datetime',
    ];

    public function borrowing(): BelongsTo
    {
        return $this->belongsTo(Borrowing::class);
    }

    public function receivedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    public function markAsPaid(int $receivedById): void
    {
        $this->update([
            'is_paid' => true,
            'paid_at' => now(),
            'received_by' => $receivedById,
        ]);
    }

    public function getFormattedAmountAttribute(): string
    {
        return 'Rp ' . number_format($this->amount, 0, ',', '.');
    }
}
