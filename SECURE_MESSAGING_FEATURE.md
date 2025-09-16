# Secure Messaging / Case Communication Feature

## Backend Implementation

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CaseMessageController extends Controller
{
    /**
     * Store a new message for a case
     */
    public function store(Request $request, Report $case)
    {
        // Check if user can post to this case
        $this->authorize('post', $case);

        // Validate request
        $request->validate([
            'body' => 'required|string|max:5000',
        ]);

        // Check if case is closed
        if ($case->report_status === 'Closed') {
            return response()->json([
                'error' => 'Cannot send messages to closed cases'
            ], 403);
        }

        // Check if case has assignees
        if ($case->assignees()->count() === 0) {
            return response()->json([
                'error' => 'Cannot send messages to cases without assignees'
            ], 403);
        }

        try {
            DB::transaction(function () use ($request, $case) {
                // Create the message
                $message = Message::create([
                    'messageable_type' => Report::class,
                    'messageable_id' => $case->id,
                    'sender_id' => Auth::id(),
                    'body' => $request->body,
                    'attachments' => null, // For future implementation
                ]);

                // Update last_message_at on the case
                $case->update([
                    'last_message_at' => now()
                ]);
            });

            return response()->json([
                'success' => true,
                'message' => 'Message sent successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to send message'
            ], 500);
        }
    }
}
```

**Figure 1: Secure Messaging Backend Code Snippet**

This code shows the implementation of secure messaging functionality within the case management system. When the user attempts to send a message to a case, the system first performs authorization checks to ensure the user has permission to post messages to that specific case. If the user lacks authorization, the system returns a 403 Forbidden response. The system then validates the message content and checks if the case is still active. If the case status is 'Closed', the system returns an error message preventing further communication. The system also verifies that the case has assigned personnel - if no assignees exist, the system returns an error message requiring case assignment before messaging can begin. If all conditions are met, the system creates the message within a database transaction, linking it to the specific case through a polymorphic relationship, and updates the case's last message timestamp. If the transaction fails, the system returns a 500 error response. This ensures that all case communications are properly tracked, authorized, and maintained within the system's audit trail while preventing unauthorized or inappropriate messaging attempts.

## Frontend Implementation

```html
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
```

**Figure 2: Secure Messaging UI Component**

This code shows the user interface for secure messaging within case management. When the user accesses the messaging interface, the system displays a searchable message thread with a scrollable container for viewing conversation history. The system then provides a message composition form with character counting and validation. The system also includes conditional rendering that shows an informative message when no case assignees are present, preventing messaging until proper case assignment occurs. This ensures that users have a clear, intuitive interface for secure case communication while maintaining proper workflow controls.

## JavaScript Implementation

```javascript
// Messaging functionality - only initialize if case has assignees
const caseId = '{{ $report->id }}';
let canPost = true;
let allMessages = []; // Store all messages for search functionality

// Load messages
function loadMessages() {
    $.get(`/cases/${caseId}/messages`, function(data) {
        canPost = data.can_post;
        allMessages = data.messages; // Store all messages
        renderMessages(allMessages);
        updateComposeForm();
        updateMessageCount(allMessages.length);
        
        // Update message count badge based on assignees
        if (!data.has_assignees) {
            $('#messagesCount').text('—');
        }
    }).fail(function() {
        $('#messagesThread').html(`
            <div class="alert alert-danger">
                <i class="ti ti-alert-circle"></i> Failed to load messages
            </div>
        `);
    });
}

// Render messages
function renderMessages(messages) {
    if (messages.length === 0) {
        $('#messagesThread').html(`
            <div class="text-center text-muted">
                <i class="ti ti-messages-off fs-1"></i>
                <p class="mt-2">No messages yet</p>
                <small>Start the conversation by sending the first message</small>
            </div>
        `);
        return;
    }

    // Reverse the messages array to show oldest first (chronological order)
    const chronologicalMessages = messages.slice().reverse();

    const messagesHtml = chronologicalMessages.map(message => {
        const isOwnMessage = message.sender_id === '{{ auth()->id() }}';
        const messageClass = isOwnMessage ? 'text-end' : 'text-start';
        const bubbleClass = isOwnMessage ? 'bg-primary text-white' : 'bg-light';
        
        return `
            <div class="mb-3 ${messageClass}">
                <div class="d-inline-block ${bubbleClass} rounded p-3" style="max-width: 70%;">
                    <div class="message-body">${escapeHtml(message.body)}</div>
                    <div class="message-meta mt-2">
                        <small class="${isOwnMessage ? 'text-white-50' : 'text-muted'}">
                            <strong>${escapeHtml(message.sender.name)}</strong> • 
                            ${new Date(message.created_at).toLocaleString()}
                        </small>
                    </div>
                </div>
            </div>
        `;
    }).join('');

    $('#messagesThread').html(messagesHtml);
    $('#messagesThread').scrollTop($('#messagesThread')[0].scrollHeight);
}

// Handle message form submission
$('#messageForm').on('submit', function(e) {
    e.preventDefault();
    
    const messageBody = $('#messageBody').val().trim();
    if (!messageBody) return;

    const $btn = $('#sendMessageBtn');
    const originalText = $btn.html();
    
    $btn.prop('disabled', true).html('<i class="ti ti-loader ti-spin"></i> Sending...');

    $.ajax({
        url: `/cases/${caseId}/messages`,
        method: 'POST',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            body: messageBody
        },
        success: function(response) {
            $('#messageBody').val('');
            $('#charCount').text('0');
            loadMessages(); // Reload messages to show the new one
        },
        error: function(xhr) {
            let errorMessage = 'Failed to send message';
            if (xhr.responseJSON && xhr.responseJSON.error) {
                errorMessage = xhr.responseJSON.error;
            }
            
            const alertHtml = `
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="ti ti-alert-circle"></i> ${errorMessage}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `;
            $('.container-fluid').prepend(alertHtml);
            
            setTimeout(() => {
                $('.alert').fadeOut(function() {
                    $(this).remove();
                });
            }, 5000);
        },
        complete: function() {
            $btn.prop('disabled', false).html(originalText);
        }
    });
});

// Search functionality
$('#messageSearch').on('input', function() {
    const searchTerm = $(this).val().toLowerCase().trim();
    
    if (searchTerm === '') {
        // Show all messages
        renderMessages(allMessages);
        $('#clearSearch').hide();
    } else {
        // Filter messages
        const filteredMessages = allMessages.filter(message => {
            const messageText = message.body.toLowerCase();
            const senderName = message.sender.name.toLowerCase();
            return messageText.includes(searchTerm) || senderName.includes(searchTerm);
        });
        
        renderMessages(filteredMessages);
        $('#clearSearch').show();
    }
});

// Auto-refresh messages every 30 seconds
setInterval(loadMessages, 30000);
```

**Figure 3: Secure Messaging JavaScript Implementation**

This code shows the JavaScript implementation that powers the secure messaging user interface. When the user loads the messaging interface, the system initializes by calling the `loadMessages()` function to fetch existing messages from the server and the `renderMessages()` function to display them in chronological order with proper styling for sent vs received messages. The system then handles form submission through AJAX using the `$('#messageForm').on('submit')` event handler, providing real-time feedback with loading states and error handling. If the message submission fails, the system displays appropriate error messages and automatically dismisses them after 5 seconds. The system also implements search functionality through the `$('#messageSearch').on('input')` event handler that filters messages by content or sender name using the `renderMessages()` function, and includes an auto-refresh mechanism using `setInterval(loadMessages, 30000)` that updates the message thread every 30 seconds to ensure real-time communication. This ensures that users have a responsive, interactive messaging experience with proper error handling and real-time updates.
