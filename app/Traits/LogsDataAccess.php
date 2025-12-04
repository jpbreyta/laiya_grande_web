<?php

namespace App\Traits;

use App\Models\DataAccessLog;
use Illuminate\Support\Facades\Auth;

trait LogsDataAccess
{
    /**
     * Boot the trait
     */
    protected static function bootLogsDataAccess(): void
    {
        // Log when model is retrieved (viewed)
        static::retrieved(function ($model) {
            if (Auth::check() && request()->isMethod('GET')) {
                DataAccessLog::logAccess(
                    get_class($model),
                    $model->id,
                    'view',
                    'Data accessed via ' . request()->path()
                );
            }
        });

        // Log when model is created
        static::created(function ($model) {
            if (Auth::check()) {
                DataAccessLog::logAccess(
                    get_class($model),
                    $model->id,
                    'create',
                    'Record created'
                );
            }
        });

        // Log when model is updated
        static::updated(function ($model) {
            if (Auth::check()) {
                DataAccessLog::logAccess(
                    get_class($model),
                    $model->id,
                    'update',
                    'Record updated: ' . implode(', ', array_keys($model->getDirty()))
                );
            }
        });

        // Log when model is deleted
        static::deleted(function ($model) {
            if (Auth::check()) {
                DataAccessLog::logAccess(
                    get_class($model),
                    $model->id,
                    'delete',
                    'Record deleted'
                );
            }
        });
    }

    /**
     * Get all access logs for this model
     */
    public function accessLogs()
    {
        return DataAccessLog::where('model_type', get_class($this))
            ->where('model_id', $this->id)
            ->orderBy('accessed_at', 'desc')
            ->get();
    }
}
