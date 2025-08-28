<div class="modal-header bg-info">
    <h5 class="modal-title text-white">
        <i class="ti ti-history me-2"></i>
        Case History - {{ $report->case_id ?? $report->id }}
    </h5>
    <button type="button" class="btn-close m-0 fs-5" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<div class="modal-body">
    @if($history->count() > 0)
        <div class="timeline">
            @foreach($history as $entry)
                <div class="timeline-item">
                    <div class="timeline-marker bg-{{ $entry->action === 'created' ? 'success' : ($entry->action === 'updated' ? 'primary' : 'info') }}">
                        <i class="ti ti-{{ $entry->action === 'created' ? 'plus' : ($entry->action === 'updated' ? 'edit' : 'activity') }}"></i>
                    </div>
                    <div class="timeline-content">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h6 class="mb-1 text-capitalize">{{ str_replace('_', ' ', $entry->action) }}</h6>
                            <small class="text-muted">{{ $entry->created_at->format('M j, Y g:i A') }}</small>
                        </div>
                        
                        @if($entry->details)
                            <p class="mb-2">{{ $entry->details }}</p>
                        @endif
                        
                        @if($entry->changes && is_array($entry->changes))
                            <div class="changes-details">
                                @foreach($entry->changes as $field => $change)
                                    @if(isset($change['from']) && isset($change['to']))
                                        <div class="change-item mb-1">
                                            <strong>{{ ucfirst(str_replace('_', ' ', $field)) }}:</strong>
                                            <span class="text-muted">{{ $change['from'] ?? 'None' }}</span>
                                            <i class="ti ti-arrow-right mx-1"></i>
                                            <span class="text-success">{{ $change['to'] ?? 'None' }}</span>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                        
                        <div class="actor-info">
                            <small class="text-muted">
                                <i class="ti ti-user me-1"></i>
                                {{ $entry->user ? $entry->user->name : 'System' }}
                                @if($entry->user && $entry->user->role)
                                    <span class="badge bg-secondary ms-1">{{ ucfirst(str_replace('_', ' ', $entry->user->role->name)) }}</span>
                                @endif
                            </small>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-4">
            <i class="ti ti-history-off f-s-48 text-muted mb-3"></i>
            <h6 class="text-muted">No History Available</h6>
            <p class="text-muted mb-0">This case doesn't have any history entries yet.</p>
        </div>
    @endif
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
</div>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e9ecef;
}

.timeline-item {
    position: relative;
    margin-bottom: 2rem;
}

.timeline-marker {
    position: absolute;
    left: -22px;
    top: 0;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 12px;
}

.timeline-content {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 1rem;
    border-left: 3px solid #dee2e6;
}

.changes-details {
    background: white;
    border-radius: 4px;
    padding: 0.75rem;
    margin: 0.5rem 0;
    border: 1px solid #e9ecef;
}

.change-item {
    font-size: 0.875rem;
}

.actor-info {
    margin-top: 0.5rem;
    padding-top: 0.5rem;
    border-top: 1px solid #e9ecef;
}
</style>
