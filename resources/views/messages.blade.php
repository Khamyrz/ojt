@extends('layouts.app')

@section('content')
    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1e40af;
            --secondary: #64748b;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --dark: #1e293b;
            --light: #f1f5f9;
            --border: #e2e8f0;
            --purple: #8b5cf6;
            --shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        body { font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: transparent; margin: 0; padding: 0; }

        .messages-container { max-width: 1400px; margin: 0 auto; background: white; border-radius: 16px; padding: 32px; box-shadow: var(--shadow-lg); }

        .page-header {
            margin-bottom: 32px;
            padding-bottom: 20px;
            border-bottom: 2px solid var(--border);
        }

        .page-header h1 {
            font-size: 32px;
            font-weight: 700;
            color: var(--dark);
            margin: 0 0 8px 0;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .page-header p {
            color: var(--secondary);
            font-size: 14px;
            margin: 0;
        }

        /* Alert Messages */
        .alert {
            padding: 16px 20px;
            border-radius: 10px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 500;
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from {
                transform: translateY(-10px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .alert.success {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success);
            border-left: 4px solid var(--success);
        }

        .alert.error {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger);
            border-left: 4px solid var(--danger);
        }

        /* Broadcast Section */
        .broadcast-section {
            background: var(--light);
            padding: 24px;
            border-radius: 12px;
            margin-bottom: 24px;
        }

        .broadcast-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 16px;
        }

        .broadcast-header h3 {
            font-size: 18px;
            font-weight: 600;
            color: var(--dark);
            margin: 0;
        }

        .broadcast-form {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .broadcast-textarea {
            width: 100%;
            padding: 16px;
            border: 2px solid var(--border);
            border-radius: 10px;
            font-size: 14px;
            font-family: inherit;
            resize: vertical;
            min-height: 100px;
            transition: all 0.3s ease;
        }

        .broadcast-textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
        }

        .broadcast-textarea::placeholder {
            color: var(--secondary);
        }

        /* Table Styles */
        .table-container {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: var(--shadow);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        }

        th {
            padding: 16px 20px;
            text-align: left;
            font-weight: 600;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: white;
        }

        th:first-child {
            border-radius: 0;
        }

        tbody tr {
            border-bottom: 1px solid var(--border);
            transition: all 0.2s ease;
        }

        tbody tr:hover {
            background: var(--light);
            transform: scale(1.01);
        }

        tbody tr:last-child {
            border-bottom: none;
        }

        td {
            padding: 16px 20px;
            color: var(--dark);
            font-size: 14px;
        }

        td:first-child {
            font-weight: 600;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 8px;
            align-items: center;
            justify-content: center;
        }

        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            font-size: 13px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-chat {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success);
            border: 1px solid var(--success);
        }

        .btn-chat:hover {
            background: var(--success);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        .btn-clear {
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning);
            border: 1px solid var(--warning);
        }

        .btn-clear:hover {
            background: var(--warning);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
        }

        .btn-delete {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger);
            border: 1px solid var(--danger);
        }

        .btn-delete:hover {
            background: var(--danger);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        }

        .btn-broadcast {
            background: rgba(37, 99, 235, 0.1);
            color: var(--primary);
            border: 1px solid var(--primary);
            padding: 12px 24px;
            font-size: 14px;
        }

        .btn-broadcast:hover {
            background: var(--primary);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        }

        /* Unread Badge */
        .unread-badge {
            background: var(--danger);
            color: white;
            border-radius: 12px;
            padding: 2px 8px;
            font-size: 11px;
            font-weight: 700;
            min-width: 20px;
            text-align: center;
            margin-left: 8px;
        }

        /* Email Badge */
        .email-badge {
            padding: 4px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
            background: rgba(139, 92, 246, 0.1);
            color: var(--purple);
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--secondary);
        }

        .empty-state i {
            font-size: 48px;
            margin-bottom: 16px;
            opacity: 0.5;
        }

        .empty-state h3 {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 8px;
            color: var(--dark);
        }

        .empty-state p {
            font-size: 14px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .messages-container {
                padding: 20px;
            }

            table {
                font-size: 12px;
            }

            th, td {
                padding: 12px 10px;
            }

            .action-buttons {
                flex-direction: column;
            }

            .broadcast-form {
                gap: 12px;
            }
        }

        /* Loading Animation */
        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.5;
            }
        }

        .loading {
            animation: pulse 1.5s ease-in-out infinite;
        }
    </style>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="messages-container">
        <!-- Page Header -->
        <div class="page-header">
            <h1>
                <i class="fas fa-envelope"></i>
                Message Center
            </h1>
            <p>Communicate with interns and manage conversations</p>
        </div>

        <!-- Alert Messages -->
        @if(session('success'))
            <div class="alert success">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="alert error">
                <i class="fas fa-exclamation-circle"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- Broadcast Section -->
        <div class="broadcast-section">
            <div class="broadcast-header">
                <i class="fas fa-bullhorn"></i>
                <h3>Send Message to All Interns</h3>
            </div>
            <form method="POST" action="{{ route('messages.broadcast') }}" class="broadcast-form">
                @csrf
                <textarea 
                    name="content" 
                    class="broadcast-textarea" 
                    placeholder="Write your message to all interns..." 
                    required
                ></textarea>
                <button type="button" class="btn btn-broadcast" onclick="confirmBroadcast()">
                    <i class="fas fa-paper-plane"></i>
                    Send to All Interns
                </button>
            </form>
        </div>

        <!-- Table -->
        @if($interns->count() > 0)
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th><i class="fas fa-user"></i> Full Name</th>
                            <th><i class="fas fa-envelope"></i> Email</th>
                            <th><i class="fas fa-cog"></i> Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($interns as $intern)
                            @php
                                $unreadCount = \App\Models\Message::where('sender_id', $intern->id)
                                    ->where('receiver_id', auth()->id())
                                    ->where('sender_type', 'intern')
                                    ->where('receiver_type', 'admin')
                                    ->where('is_read', false)
                                    ->count();
                            @endphp
                            <tr>
                                <td>
                                    <strong>{{ $intern->first_name }} {{ $intern->last_name }}</strong>
                                    @if($unreadCount > 0)
                                        <span class="unread-badge">{{ $unreadCount }}</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="email-badge">{{ $intern->email }}</span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <button type="button" class="btn btn-chat" onclick="openChatModal({{ $intern->id }}, '{{ $intern->first_name }}', '{{ $intern->last_name }}')">
                                            <i class="fas fa-comments"></i>
                                            Open Chat
                                        </button>
                                        <form id="clear-form-{{ $intern->id }}" action="{{ route('messages.clear', $intern->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-clear" onclick="confirmClear({{ $intern->id }})">
                                                <i class="fas fa-trash"></i>
                                                Clear
                                            </button>
                                        </form>
                                        <form action="{{ route('intern.destroy', $intern->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-delete" onclick="confirmDelete({{ $intern->id }}, '{{ $intern->first_name }} {{ $intern->last_name }}')">
                                                <i class="fas fa-user-times"></i>
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <h3>No Interns Found</h3>
                <p>No accepted interns available for messaging at the moment</p>
            </div>
        @endif
    </div>

    <!-- Chat Modal -->
    <div id="chatModal" class="chat-modal" style="display: none;">
        <div class="chat-modal-content">
            <div class="chat-modal-header">
                <div class="chat-header-info">
                    <h2>
                        <i class="fas fa-comments"></i>
                        <span id="chatInternName">Chat</span>
                    </h2>
                    <span id="chatInternEmail" class="chat-email"></span>
                </div>
                <span class="chat-modal-close" onclick="closeChatModal()">&times;</span>
            </div>
            <div class="chat-messages-container" id="chatMessagesContainer">
                <div class="chat-messages" id="chatMessages">
                    <div class="chat-loading">
                        <i class="fas fa-spinner fa-spin"></i>
                        Loading conversation...
                    </div>
                </div>
            </div>
            <div class="chat-input-container">
                <textarea 
                    id="chatMessageInput" 
                    class="chat-input-field" 
                    placeholder="Type your message..."
                    rows="1"
                ></textarea>
                <button type="button" class="chat-send-btn" id="chatSendBtn" onclick="sendChatMessage()">
                    <i class="fas fa-paper-plane"></i>
                    Send
                </button>
            </div>
        </div>
    </div>

    <style>
        /* Chat Modal Styles */
        .chat-modal {
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .chat-modal-content {
            background-color: white;
            margin: 2% auto;
            border-radius: 16px;
            width: 90%;
            max-width: 800px;
            max-height: 90vh;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: slideDown 0.3s ease;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        @keyframes slideDown {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .chat-modal-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 20px 30px;
            border-radius: 16px 16px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-shrink: 0;
        }

        .chat-header-info {
            flex: 1;
        }

        .chat-modal-header h2 {
            margin: 0 0 4px 0;
            font-size: 20px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .chat-email {
            font-size: 13px;
            opacity: 0.9;
            display: block;
            margin-top: 4px;
        }

        .chat-modal-close {
            color: white;
            font-size: 32px;
            font-weight: bold;
            cursor: pointer;
            transition: transform 0.2s;
            line-height: 1;
            margin-left: 20px;
        }

        .chat-modal-close:hover {
            transform: scale(1.2);
        }

        .chat-messages-container {
            flex: 1;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            background: #f9fbfd;
        }

        .chat-messages {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        /* Custom Scrollbar */
        .chat-messages::-webkit-scrollbar {
            width: 8px;
        }

        .chat-messages::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        .chat-messages::-webkit-scrollbar-thumb {
            background: var(--primary);
            border-radius: 4px;
        }

        .chat-messages::-webkit-scrollbar-thumb:hover {
            background: var(--primary-dark);
        }

        .chat-message {
            padding: 12px 16px;
            border-radius: 18px;
            max-width: 75%;
            word-wrap: break-word;
            line-height: 1.5;
            position: relative;
            animation: messageSlideIn 0.3s ease;
        }

        @keyframes messageSlideIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .chat-message.admin {
            background-color: var(--primary);
            color: white;
            align-self: flex-end;
            border-bottom-right-radius: 4px;
        }

        .chat-message.broadcast {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
            border: 2px solid #f59e0b;
        }

        .broadcast-label {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(255, 255, 255, 0.2);
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }

        .chat-message.intern {
            background-color: #e6e6e6;
            color: #333;
            align-self: flex-start;
            border-bottom-left-radius: 4px;
        }

        .chat-message .message-content {
            font-size: 14px;
            margin-bottom: 4px;
        }

        .chat-message .message-timestamp {
            font-size: 11px;
            opacity: 0.7;
            text-align: right;
        }

        .chat-message.intern .message-timestamp {
            text-align: left;
        }

        .chat-loading {
            text-align: center;
            padding: 40px;
            color: var(--primary);
            font-size: 16px;
            font-weight: 600;
        }

        .chat-loading i {
            font-size: 24px;
            margin-right: 12px;
        }

        .chat-empty {
            text-align: center;
            padding: 40px;
            color: var(--secondary);
            font-size: 14px;
        }

        .chat-input-container {
            padding: 16px 20px;
            background-color: white;
            border-top: 1px solid var(--border);
            display: flex;
            gap: 12px;
            align-items: flex-end;
            flex-shrink: 0;
        }

        .chat-input-field {
            flex: 1;
            padding: 12px 16px;
            border: 2px solid var(--border);
            border-radius: 24px;
            font-size: 14px;
            font-family: inherit;
            resize: none;
            max-height: 120px;
            transition: all 0.3s ease;
            outline: none;
        }

        .chat-input-field:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
        }

        .chat-send-btn {
            background: var(--primary);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 24px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
            white-space: nowrap;
        }

        .chat-send-btn:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        }

        .chat-send-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        @media (max-width: 768px) {
            .chat-modal-content {
                width: 95%;
                margin: 5% auto;
                max-height: 95vh;
            }

            .chat-modal-header {
                padding: 16px 20px;
            }

            .chat-modal-header h2 {
                font-size: 18px;
            }

            .chat-messages {
                padding: 16px;
            }

            .chat-message {
                max-width: 85%;
            }

            .chat-input-container {
                padding: 12px 16px;
            }
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        function confirmClear(internId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "This will delete the conversation with this intern.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#f59e0b',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, clear it!',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('clear-form-' + internId).submit();
                }
            });
        }

        function confirmDelete(internId, internName) {
            Swal.fire({
                title: 'Are you absolutely sure?',
                text: `This will permanently delete ${internName} and ALL their data including time logs, journals, grades, messages, and documents. This action cannot be undone!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete permanently!',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    const deleteForm = document.querySelector(`form[action*="/interns/${internId}"]`);
                    if (deleteForm) {
                        deleteForm.submit();
                    }
                }
            });
        }

        function confirmBroadcast() {
            const textarea = document.querySelector('.broadcast-textarea');
            const message = textarea.value.trim();
            
            if (!message) {
                Swal.fire({
                    icon: 'error',
                    title: 'Message Required',
                    text: 'Please write a message before broadcasting.',
                    confirmButtonColor: '#ef4444',
                });
                return;
            }

            Swal.fire({
                title: 'Send to all interns?',
                text: "Are you sure you want to broadcast this message to all interns?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#2563eb',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, send it!',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.querySelector('.broadcast-form').submit();
                }
            });
        }

        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session('success') }}',
                confirmButtonColor: '#10b981',
                timer: 3000,
                timerProgressBar: true,
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '{{ session('error') }}',
                confirmButtonColor: '#ef4444',
            });
        @endif

        @if(session('warning'))
            Swal.fire({
                icon: 'warning',
                title: 'Warning!',
                text: '{{ session('warning') }}',
                confirmButtonColor: '#f59e0b',
            });
        @endif

        @if($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                html: '<ul style="text-align: left;">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>',
                confirmButtonColor: '#ef4444',
            });
        @endif

        // Chat Modal Functions
        let currentInternId = null;
        let lastMessageId = 0;
        let messagePollInterval = null;
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';

        function openChatModal(internId, firstName, lastName) {
            currentInternId = internId;
            const modal = document.getElementById('chatModal');
            const messagesContainer = document.getElementById('chatMessages');
            const messageInput = document.getElementById('chatMessageInput');
            
            // Update header
            document.getElementById('chatInternName').textContent = `${firstName} ${lastName}`;
            
            // Reset state
            lastMessageId = 0;
            messageInput.value = '';
            messagesContainer.innerHTML = '<div class="chat-loading"><i class="fas fa-spinner fa-spin"></i> Loading conversation...</div>';
            
            // Show modal
            modal.style.display = 'block';
            document.body.style.overflow = 'hidden';
            
            // Load conversation
            loadConversation(internId);
            
            // Start polling for new messages
            if (messagePollInterval) {
                clearInterval(messagePollInterval);
            }
            messagePollInterval = setInterval(() => {
                if (currentInternId) {
                    fetchNewMessages();
                }
            }, 3000);
        }

        function closeChatModal() {
            const modal = document.getElementById('chatModal');
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
            currentInternId = null;
            
            // Stop polling
            if (messagePollInterval) {
                clearInterval(messagePollInterval);
                messagePollInterval = null;
            }
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('chatModal');
            if (event.target === modal) {
                closeChatModal();
            }
        }

        function loadConversation(internId) {
            axios.get(`{{ url('/messages') }}/${internId}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                const data = response.data;
                const messagesContainer = document.getElementById('chatMessages');
                
                // Update email if available
                if (data.intern && data.intern.email) {
                    document.getElementById('chatInternEmail').textContent = data.intern.email;
                }
                
                // Clear loading and render messages
                messagesContainer.innerHTML = '';
                
                if (data.messages && data.messages.length > 0) {
                    data.messages.forEach(message => {
                        appendMessage(message.content, message.sender_type, message.created_at, message.is_broadcast);
                        lastMessageId = Math.max(lastMessageId, message.id);
                    });
                    scrollToBottom();
                } else {
                    messagesContainer.innerHTML = '<div class="chat-empty"><i class="fas fa-comments"></i><p>No messages yet. Start the conversation!</p></div>';
                }
            })
            .catch(error => {
                console.error('Error loading conversation:', error);
                document.getElementById('chatMessages').innerHTML = 
                    '<div class="chat-empty"><i class="fas fa-exclamation-circle"></i><p>Error loading conversation. Please try again.</p></div>';
            });
        }

        function appendMessage(content, senderType, timestamp, isBroadcast = false) {
            const messagesContainer = document.getElementById('chatMessages');
            const messageDiv = document.createElement('div');
            let messageClass = `chat-message ${senderType}`;
            
            if (isBroadcast) {
                messageClass += ' broadcast';
            }
            
            messageDiv.className = messageClass;
            
            const broadcastLabel = isBroadcast 
                ? '<div class="broadcast-label"><i class="fas fa-bullhorn"></i> Broadcasted</div>'
                : '';
            
            messageDiv.innerHTML = `
                ${broadcastLabel}
                <div class="message-content">${escapeHtml(content)}</div>
                <div class="message-timestamp">${timestamp}</div>
            `;
            
            messagesContainer.appendChild(messageDiv);
            scrollToBottom();
        }

        function scrollToBottom() {
            const messagesContainer = document.getElementById('chatMessages');
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }

        function sendChatMessage() {
            if (!currentInternId) return;
            
            const messageInput = document.getElementById('chatMessageInput');
            const sendBtn = document.getElementById('chatSendBtn');
            const content = messageInput.value.trim();
            
            if (!content) return;
            
            // Disable send button
            sendBtn.disabled = true;
            
            axios.post('{{ route("messages.send") }}', {
                receiver_id: currentInternId,
                content: content
            }, {
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                messageInput.value = '';
                const message = response.data;
                appendMessage(message.content, 'admin', new Date().toLocaleString('en-US', {
                    month: 'short',
                    day: 'numeric',
                    year: 'numeric',
                    hour: 'numeric',
                    minute: '2-digit'
                }), false); // Regular messages are not broadcasts
                lastMessageId = Math.max(lastMessageId, message.id || 0);
            })
            .catch(error => {
                console.error('Error sending message:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.response?.data?.message || 'Failed to send message. Please try again.',
                    confirmButtonColor: '#ef4444',
                });
            })
            .finally(() => {
                sendBtn.disabled = false;
            });
        }

        function fetchNewMessages() {
            if (!currentInternId || lastMessageId === 0) return;
            
            axios.get(`{{ url('/api/messages') }}/${currentInternId}/new?last_message_id=${lastMessageId}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                const messages = response.data.messages || [];
                messages.forEach(message => {
                    appendMessage(message.content, 'intern', message.created_at || new Date().toLocaleString(), false);
                    lastMessageId = Math.max(lastMessageId, message.id);
                });
            })
            .catch(error => {
                // Silently fail for polling
                console.error('Error fetching new messages:', error);
            });
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // Handle Enter key in message input
        document.addEventListener('DOMContentLoaded', function() {
            const messageInput = document.getElementById('chatMessageInput');
            if (messageInput) {
                messageInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter' && !e.shiftKey) {
                        e.preventDefault();
                        sendChatMessage();
                    }
                });
                
                // Auto-resize textarea
                messageInput.addEventListener('input', function() {
                    this.style.height = 'auto';
                    this.style.height = Math.min(this.scrollHeight, 120) + 'px';
                });
            }
        });
    </script>
@endsection