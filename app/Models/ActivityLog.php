<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'model_type',
        'model_id',
        'model_label',
        'old_values',
        'new_values',
        'description',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function subject(): MorphTo
    {
        return $this->morphTo('model');
    }

    public function getActionLabelAttribute(): string
    {
        return match($this->action) {
            'created' => 'Membuat',
            'updated' => 'Mengubah',
            'deleted' => 'Menghapus',
            'approved' => 'Menyetujui',
            'rejected' => 'Menolak',
            'returned' => 'Mengembalikan',
            'borrowed' => 'Meminjam',
            'login' => 'Login',
            'logout' => 'Logout',
            default => ucfirst($this->action),
        };
    }

    public function getActionColorAttribute(): string
    {
        return match($this->action) {
            'created' => 'success',
            'updated' => 'warning',
            'deleted' => 'danger',
            'approved' => 'success',
            'rejected' => 'danger',
            'returned' => 'info',
            'borrowed' => 'primary',
            'login' => 'primary',
            'logout' => 'secondary',
            default => 'secondary',
        };
    }

    public static function log(
        string $action,
        ?Model $model = null,
        ?array $oldValues = null,
        ?array $newValues = null,
        ?string $description = null
    ): self {
        return self::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'model_type' => $model ? class_basename($model) : null,
            'model_id' => $model?->id,
            'model_label' => $model ? self::getModelLabel($model) : null,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'description' => $description,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    protected static function getModelLabel(Model $model): string
    {
        foreach (['name', 'title', 'code', 'username', 'email'] as $field) {
            if (isset($model->$field)) {
                return (string) $model->$field;
            }
        }
        
        return class_basename($model) . ' #' . $model->id;
    }
}
