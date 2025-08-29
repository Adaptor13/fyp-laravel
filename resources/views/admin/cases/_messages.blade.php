<div class="card">
    <div class="card-header">
        <h6 class="mb-0"><i class="ti ti-messages"></i> Secure Messages</h6>
    </div>
    <div class="card-body">
        <!-- Check if case has assignees -->
        @if($assignees->count() > 0)
            <!-- Search Bar -->
            <div class="mb-3">
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="ti ti-search"></i>
                    </span>
                    <input type="text" class="form-control" id="messageSearch" placeholder="Search messages..." />
                    <button class="btn btn-outline-secondary" type="button" id="clearSearch" style="display: none;">
                        <i class="ti ti-x"></i>
                    </button>
                </div>
            </div>

            <!-- Messages Thread -->
            <div id="messagesThread" class="mb-4" style="height: 600px; overflow-y: auto; border: 1px solid #dee2e6; border-radius: 0.375rem; padding: 1rem;">
                <div class="text-center text-muted">
                    <i class="ti ti-loader ti-spin"></i> Loading messages...
                </div>
            </div>

            <!-- Compose Message Form -->
            <div id="composeMessage" class="border-top pt-3">
                <form id="messageForm">
                    @csrf
                    <div class="mb-3">
                        <label for="messageBody" class="form-label">New Message</label>
                        <textarea 
                            class="form-control" 
                            id="messageBody" 
                            name="body" 
                            rows="3" 
                            placeholder="Type your message here..."
                            maxlength="5000"
                        ></textarea>
                        <div class="form-text">
                            <span id="charCount">0</span>/5000 characters
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary" id="sendMessageBtn">
                        <i class="ti ti-send"></i> Send Message
                    </button>
                </form>
            </div>
        @else
            <!-- No Assignees Message -->
            <div class="text-center py-5">
                <div class="mb-3">
                    <i class="ti ti-users-off fs-1 text-muted"></i>
                </div>
                <h5 class="text-muted mb-3">No Assignees Found</h5>
                <p class="text-muted mb-4">
                    This case needs to have at least one assignee before messaging can begin. 
                    Please assign case workers, law enforcement, or healthcare professionals to this case.
                </p>
                <div class="alert alert-info">
                    <i class="ti ti-info-circle"></i>
                    Contact an administrator to assign case workers to this case.
                </div>
            </div>
        @endif
    </div>
</div>

