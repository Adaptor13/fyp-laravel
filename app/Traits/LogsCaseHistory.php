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
            $changes = $model->getChanges();
            
            // Filter out automatic timestamp updates that don't represent real user changes
            $filteredChanges = array_filter($changes, function($value, $key) {
                // Skip status_updated_at if it's the only change (automatic update)
                if ($key === 'status_updated_at') {
                    return false;
                }
                return true;
            }, ARRAY_FILTER_USE_BOTH);
            
            // Only log if there are meaningful changes
            if (!empty($filteredChanges)) {
                $model->logHistory('updated', 'Case was updated', $filteredChanges);
            }
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
                if (in_array($field, ['updated_at', 'created_at', 'status_updated_at'])) {
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
        $changesData = null;
        
        if ($changes && is_array($changes)) {
            // For custom actions like assignees_updated, store changes as-is
            if ($action === 'assignees_updated') {
                $changesData = $changes;
            } else {
                // For regular field changes, process with from/to structure
                $changesData = [];
                foreach ($changes as $field => $newValue) {
                    // Skip certain fields that don't need to be logged
                    if (in_array($field, ['updated_at', 'created_at', 'status_updated_at'])) {
                        continue;
                    }
                    
                    $oldValue = $this->getOriginal($field);
                    $changesData[$field] = [
                        'from' => $oldValue,
                        'to' => $newValue
                    ];
                }
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
}
