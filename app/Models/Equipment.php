<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Equipment extends Model
{
    use LogsActivity;

    protected $table = 'equipment';

    protected $fillable = [
        'code',
        'name',
        'category_id',
        'description',
        'stock',
        'location',
        'condition',
        'image',
        'price',
        'purchase_year',
        'vendor',
    ];

    protected $casts = [
        'stock' => 'integer',
    ];

    protected $updated_at = null;

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function borrowings(): HasMany
    {
        return $this->hasMany(Borrowing::class);
    }

    public function logs(): HasMany
    {
        return $this->hasMany(EquipmentLog::class);
    }

    public function isAvailable(): bool
    {
        return $this->stock > 0 && $this->condition === 'baik';
    }

    public function activeBorrowings(): HasMany
    {
        return $this->borrowings()->whereIn('status', ['approved', 'borrowed']);
    }

    public function pendingBorrowings(): HasMany
    {
        return $this->borrowings()->where('status', 'pending');
    }

    public function getPendingCountAttribute(): int
    {
        return $this->pendingBorrowings()->count();
    }
}
