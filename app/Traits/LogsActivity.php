<?php

namespace App\Traits;

use App\Models\ActivityLog;

trait LogsActivity
{
    public static function bootLogsActivity(): void
    {
        static::created(function ($model) {
            ActivityLog::log(
                'created',
                $model,
                null,
                $model->getAttributes(),
                class_basename($model) . ' baru ditambahkan'
            );
        });

        static::updated(function ($model) {
            $dirty = $model->getDirty();
            $original = array_intersect_key($model->getOriginal(), $dirty);
            
            if (empty($dirty)) {
                return;
            }

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
