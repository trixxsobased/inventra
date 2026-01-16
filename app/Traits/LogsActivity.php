<?php

namespace App\Traits;

use App\Models\ActivityLog;

trait LogsActivity
{
    /**
     * Boot the trait
     */
    public static function bootLogsActivity(): void
    {
        // Log when model is created
        static::created(function ($model) {
            ActivityLog::log(
                'created',
                $model,
                null,
                $model->getAttributes(),
                class_basename($model) . ' baru ditambahkan'
            );
        });

        // Log when model is updated
        static::updated(function ($model) {
            $dirty = $model->getDirty();
            $original = array_intersect_key($model->getOriginal(), $dirty);
            
            // Skip jika tidak ada perubahan signifikan
            if (empty($dirty)) {
                return;
            }

            // Filter out timestamp fields
            unset($dirty['updated_at'], $dirty['created_at']);
            unset($original['updated_at'], $original['created_at']);

            if (!empty($dirty)) {
                ActivityLog::log(
                    'updated',
                    $model,
                    $original,
                    $dirty,
                    class_basename($model) . ' diperbarui'
                );
            }
        });

        // Log when model is deleted
        static::deleted(function ($model) {
            ActivityLog::log(
                'deleted',
                $model,
                $model->getAttributes(),
                null,
                class_basename($model) . ' dihapus'
            );
        });
    }
}
