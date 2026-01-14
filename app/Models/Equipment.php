<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Equipment extends Model
{
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
        // Manajemen aset perusahaan
        'price',
        'purchase_year',
        'vendor',
    ];

    protected $casts = [
        'stock' => 'integer',
    ];

    // Nonaktifkan updated_at karena hanya perlu created_at untuk log equipment
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
}
