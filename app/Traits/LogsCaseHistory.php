<?php

namespace App\Traits;

use App\Models\CaseHistory;
use Illuminate\Support\Facades\Request;

trait LogsCaseHistory
{
    protected static function bootLogsCaseHistory()
    {
        static::created(function ($model) {
            $model->logHistory('created', 'Case was created');
        });

        static::updated(function ($model) {
            $model->logHistory('updated', 'Case was updated', $model->getChanges());
        });

        static::deleting(function ($model) {
            // Log deletion before the model is actually deleted
            $model->logHistory('deleted', 'Case was deleted');
        });
    }

    /**
     * Log a history entry for this case
     */
    public function logHistory($action, $details = null, $changes = null)
    {
        $changesData = null;
        
        if ($changes && is_array($changes)) {
            $changesData = [];
            foreach ($changes as $field => $newValue) {
                // Skip certain fields that don't need to be logged
                if (in_array($field, ['updated_at', 'created_at'])) {
                    continue;
                }
                
                $oldValue = $this->getOriginal($field);
                $changesData[$field] = [
                    'from' => $oldValue,
                    'to' => $newValue
                ];
            }
        }

        CaseHistory::create([
            'report_id' => $this->id,
            'user_id' => auth()->id(),
            'action' => $action,
            'details' => $details,
            'changes' => $changesData,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent()
        ]);
    }

    /**
     * Manually log a specific action
     */
    public function logAction($action, $details = null, $changes = null)
    {
        $this->logHistory($action, $details, $changes);
    }
}
