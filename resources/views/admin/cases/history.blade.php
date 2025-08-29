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
                                        <div class="change-item mb-2">
                                            <strong class="text-primary">{{ ucfirst(str_replace('_', ' ', $field)) }}:</strong>
                                            <div class="d-flex align-items-center mt-1">
                                                <div class="from-value me-2">
                                                    <small class="text-muted">From:</small>
                                                    @if(is_array($change['from']))
                                                        @if(empty($change['from']))
                                                            <span class="badge bg-secondary">None</span>
                                                        @else
                                                            <div class="mt-1">
                                                                @foreach($change['from'] as $item)
                                                                    <span class="badge bg-light text-dark me-1 mb-1">{{ $item }}</span>
                                                                @endforeach
                                                            </div>
                                                        @endif
                                                    @else
                                                        <span class="badge bg-light text-dark">{{ $change['from'] ?? 'None' }}</span>
                                                    @endif
                                                </div>
                                                <i class="ti ti-arrow-right mx-2 text-muted"></i>
                                                <div class="to-value">
                                                    <small class="text-muted">To:</small>
                                                    @if(is_array($change['to']))
                                                        @if(empty($change['to']))
                                                            <span class="badge bg-secondary">None</span>
                                                        @else
                                                            <div class="mt-1">
                                                                                                                                 @foreach($change['to'] as $item)
                                                                     @if($field === 'evidence')
                                                                         @php
                                                                             $filename = basename($item);
                                                                             $extension = pathinfo($filename, PATHINFO_EXTENSION);
                                                                             $iconClass = match(strtolower($extension)) {
                                                                                 'jpg', 'jpeg', 'png', 'gif', 'webp' => 'ti ti-photo',
                                                                                 'mp4', 'avi', 'mov', 'wmv' => 'ti ti-video',
                                                                                 'pdf' => 'ti ti-file-text',
                                                                                 default => 'ti ti-file'
                                                                             };
                                                                         @endphp
                                                                         <span class="badge bg-info text-white me-1 mb-1 evidence-badge" title="{{ $item }}">
                                                                             <i class="{{ $iconClass }} me-1"></i>
                                                                             <span class="evidence-filename">{{ strlen($filename) > 20 ? substr($filename, 0, 17) . '...' : $filename }}</span>
                                                                         </span>
                                                                     @else
                                                                         <span class="badge bg-success text-white me-1 mb-1">{{ $item }}</span>
                                                                     @endif
                                                                 @endforeach
                                                            </div>
                                                        @endif
                                                    @else
                                                        <span class="badge bg-success text-white">{{ $change['to'] ?? 'None' }}</span>
                                                    @endif
                                                </div>
                                            </div>
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
    border-bottom: 1px solid #f0f0f0;
    padding-bottom: 0.75rem;
}

.change-item:last-child {
    border-bottom: none;
    padding-bottom: 0;
}

.from-value, .to-value {
    flex: 1;
}

.badge {
    font-size: 0.75rem;
    font-weight: 500;
}

.badge.bg-light {
    border: 1px solid #dee2e6;
}

.evidence-badge {
    max-width: 200px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    cursor: help;
}

.evidence-filename {
    max-width: 150px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    display: inline-block;
}

.changes-details {
    max-height: 400px;
    overflow-y: auto;
}

.actor-info {
    margin-top: 0.5rem;
    padding-top: 0.5rem;
    border-top: 1px solid #e9ecef;
}
</style>
