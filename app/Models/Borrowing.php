<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Helpers\FineCalculator;

class Borrowing extends Model
{
    use LogsActivity;
    protected $fillable = [
        'user_id',
        'equipment_id',
        'borrow_date',
        'planned_return_date',
        'actual_return_date',
        'return_condition',
        'status',
        'purpose',
        'notes',
        'rejection_reason',
        'verified_by',
        'verified_at',
    ];

    protected $casts = [
        'borrow_date' => 'date',
        'planned_return_date' => 'date',
        'actual_return_date' => 'date',
        'verified_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function equipment(): BelongsTo
    {
        return $this->belongsTo(Equipment::class);
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function fine(): HasOne
    {
        return $this->hasOne(Fine::class);
    }

    public function damagedEquipment(): HasOne
    {
        return $this->hasOne(DamagedEquipment::class);
    }

    public function isLate(): bool
    {
        if (!$this->actual_return_date) {
            return now()->gt($this->planned_return_date);
        }

        return $this->actual_return_date->gt($this->planned_return_date);
    }

    public function calculateFine(float $ratePerDay = 5000): array
    {
        if (!$this->actual_return_date) {
            return FineCalculator::getDetailedInfo(
                $this->planned_return_date->toDateString(),
                now()->toDateString(),
                $ratePerDay
            );
        }

        return FineCalculator::calculate(
            $this->planned_return_date->toDateString(),
            $this->actual_return_date->toDateString(),
            $ratePerDay
        );
    }

    public function getDaysLateAttribute(): int
    {
        $returnDate = $this->actual_return_date ?? now();
        return $this->planned_return_date->diffInDays($returnDate, false);
    }
}
