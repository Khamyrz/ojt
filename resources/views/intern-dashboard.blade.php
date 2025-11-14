<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Intern Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f8fafc;
            margin: 0;
            padding: 0;
        }

        /* Header Navigation */
        .header-nav {
            background: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 15px 0;
            margin-bottom: 30px;
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 25px;
        }

        .nav-brand {
            font-size: 24px;
            font-weight: 700;
            color: #2c3e50;
        }

        .nav-actions {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #4a5568;
            font-weight: 500;
        }

        .user-avatar {
            width: 35px;
            height: 35px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
        }

        .logout-btn {
            background: #f56565;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 6px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .logout-btn:hover {
            background: #e53e3e;
            transform: translateY(-1px);
        }

        .nav-btn {
            background: #4299e1;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 6px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .nav-btn:hover {
            background: #3182ce;
            transform: translateY(-1px);
        }

        .phase-notification {
            animation: pulse 2s infinite;
        }

        .phase-notification {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 30px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(245, 158, 11, 0.3);
        }

        .phase-btn {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.3);
            padding: 12px 30px;
            border-radius: 25px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .phase-btn:hover {
            background: rgba(255, 255, 255, 0.3) !important;
            border-color: rgba(255, 255, 255, 0.5) !important;
            transform: translateY(-2px);
        }

        .journal-btn:hover {
            background: rgba(255, 255, 255, 0.3) !important;
            border-color: rgba(255, 255, 255, 0.5) !important;
            transform: translateY(-2px);
        }

        .dtr-btn:hover {
            background: rgba(255, 255, 255, 0.3) !important;
            border-color: rgba(255, 255, 255, 0.5) !important;
            transform: translateY(-2px);
        }

        .unread-badge {
            background: #ef4444;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 600;
            position: absolute;
            top: -5px;
            right: -5px;
        }

        .message-card {
            grid-column: span 2;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group textarea {
            font-family: inherit;
            font-size: 14px;
        }

        .dtr-widget {
            grid-column: span 2;
        }

        .current-time {
            font-size: 14px;
            color: #6b7280;
            margin-top: 5px;
        }

        .dtr-status {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .status-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            background: #f8fafc;
            border-radius: 6px;
            border: 1px solid #e2e8f0;
        }

        .status-item .label {
            font-weight: 600;
            color: #374151;
            font-size: 14px;
        }

        .status-item .value {
            font-weight: 700;
            color: #1f2937;
            font-size: 14px;
        }

        .dtr-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .dtr-actions .card-btn {
            flex: 1;
            min-width: 120px;
        }

        /* Time Action Buttons */
        .time-action-btn {
            flex: 1;
            min-width: 140px;
            padding: 14px 24px;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
        }

        .time-action-btn:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        .time-action-btn:active:not(:disabled) {
            transform: translateY(0);
        }

        .time-action-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .time-in-btn {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }

        .time-in-btn:hover:not(:disabled) {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
        }

        .time-out-btn {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
        }

        .time-out-btn:hover:not(:disabled) {
            background: linear-gradient(135deg, #d97706 0%, #b45309 100%);
        }

        .btn-icon {
            font-size: 18px;
            display: inline-block;
        }

        .btn-text {
            display: inline-block;
        }

        .time-action-btn.processing {
            pointer-events: none;
        }

        .time-action-btn.processing::after {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background-color: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05);
        }

        h1 {
            margin-bottom: 25px;
            color: #2c3e50;
            font-weight: 600;
            text-align: center;
        }

        /* Attendance Notification */
        .attendance-notification {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 30px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.02); }
            100% { transform: scale(1); }
        }

        .attendance-notification h3 {
            font-size: 24px;
            margin-bottom: 15px;
            font-weight: 700;
        }

        .attendance-notification p {
            font-size: 16px;
            margin-bottom: 20px;
            opacity: 0.9;
        }

        .attendance-btn {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.3);
            padding: 12px 30px;
            border-radius: 25px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .attendance-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            border-color: rgba(255, 255, 255, 0.5);
            transform: translateY(-2px);
        }

        .attendance-status {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            margin-left: 15px;
        }

        .status-present {
            background: #48bb78;
            color: white;
        }

        .status-absent {
            background: #f56565;
            color: white;
        }

        .status-released {
            background: #4299e1;
            color: white;
        }

        .status-not-released {
            background: #a0aec0;
            color: white;
        }

        /* Dashboard Cards */
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .dashboard-card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
        }

        .card-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
        }

        .icon-time { background: #4299e1; }
        .icon-journal { background: #48bb78; }
        .icon-messages { background: #ed8936; }
        .icon-documents { background: #9f7aea; }
        .icon-dtr { background: #10b981; } /* Added for DTR widget */

        .card-title {
            font-size: 18px;
            font-weight: 600;
            color: #2d3748;
        }

        .card-content {
            color: #718096;
            line-height: 1.6;
        }

        .card-actions {
            margin-top: 20px;
        }

        .card-btn {
            display: inline-block;
            padding: 10px 20px;
            background: #4299e1;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .card-btn:hover {
            background: #3182ce;
            transform: translateY(-2px);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                margin: 20px;
                padding: 20px;
            }
            
            .dashboard-grid {
                grid-template-columns: 1fr;
            }
            
            .attendance-notification {
                padding: 20px;
            }
            
            .attendance-notification h3 {
                font-size: 20px;
            }
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: #fff;
            margin: 5% auto;
            padding: 30px;
            border-radius: 10px;
            max-width: 600px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            position: absolute;
            right: 20px;
            top: 15px;
        }

        .close:hover,
        .close:focus {
            color: #000;
        }

        .modal h2 {
            margin-top: 0;
            margin-bottom: 20px;
            color: #2c3e50;
        }

        .modal label {
            display: block;
            margin: 12px 0 6px;
            font-weight: bold;
            color: #333;
        }

        .modal input[type="file"],
        .modal select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
        }

        .modal button[type="submit"] {
            margin-top: 20px;
            background-color: #3490dc;
            color: white;
            border: none;
            padding: 12px 18px;
            font-size: 15px;
            border-radius: 6px;
            cursor: pointer;
            width: 100%;
        }

        .modal button[type="submit"]:hover {
            background-color: #2779bd;
        }

        .request-list {
            margin-top: 20px;
            background: #f9f9f9;
            padding: 15px;
            border-radius: 8px;
            font-size: 14px;
        }

        .request-list ul {
            padding-left: 20px;
        }

        .request-list strong {
            display: block;
            margin-bottom: 5px;
        }

        .alert {
            margin-top: 15px;
            padding: 12px;
            border-radius: 6px;
            font-size: 15px;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }

        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <!-- Navigation Header -->
    <div class="header-nav">
        <div class="nav-container">
            <div class="nav-brand">Intern Dashboard</div>
            <div class="nav-actions">
                <div class="user-info">
                    <div class="user-avatar">{{ substr($intern->first_name, 0, 1) }}{{ substr($intern->last_name, 0, 1) }}</div>
                    {{ $intern->first_name }} {{ $intern->last_name }}
                </div>
                <a href="{{ route('intern.phase-submission') }}" class="nav-btn">📋 Phase Submission</a>
                <a href="{{ route('intern.logout') }}" class="logout-btn" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    🚪 Logout
                </a>
                <form id="logout-form" action="{{ route('intern.logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </div>
    </div>

    <div class="container">
        <h1>👋 Welcome, {{ $intern->first_name }} {{ $intern->last_name }}!</h1>

        <!-- Phase Status Notification -->
        @if(!$intern->hasCompletedAllPhases())
            <div class="phase-notification">
                <h3>📋 Phase Completion Required</h3>
                <p>You need to complete all phases before accessing the full dashboard. Click the button below to submit your phase documents.</p>
                <a href="{{ route('intern.phase-submission') }}" class="phase-btn">
                    📋 Submit Phase Documents
                </a>
            </div>
        @endif

        <!-- Friday Journal Reminder -->
        @if(now()->isFriday() && !$intern->hasSubmittedJournalThisWeek())
            <div class="journal-reminder" style="background: linear-gradient(135deg, #10b981, #059669); color: white; padding: 25px; border-radius: 15px; margin-bottom: 30px; text-align: center; box-shadow: 0 10px 30px rgba(16, 185, 129, 0.3);">
                <h3>📝 Friday Journal Reminder</h3>
                <p>It's Friday! Don't forget to submit your weekly journal entry documenting your learning experiences and tasks completed.</p>
                <a href="{{ route('intern.journal') }}" class="journal-btn" style="background: rgba(255, 255, 255, 0.2); color: white; border: 2px solid rgba(255, 255, 255, 0.3); padding: 12px 30px; border-radius: 25px; font-size: 16px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; text-decoration: none; display: inline-block;">
                    ✍️ Write Journal Entry
                </a>
            </div>
        @endif

        <!-- End of Month DTR Reminder -->
        @if(now()->endOfMonth()->diffInDays(now()) <= 3)
            <div class="dtr-reminder" style="background: linear-gradient(135deg, #8b5cf6, #7c3aed); color: white; padding: 25px; border-radius: 15px; margin-bottom: 30px; text-align: center; box-shadow: 0 10px 30px rgba(139, 92, 246, 0.3);">
                <h3>📊 End of Month DTR Reminder</h3>
                <p>The month is ending soon! Make sure your Daily Time Record (DTR) is complete and accurate.</p>
                <a href="{{ route('intern.dtr') }}" class="dtr-btn" style="background: rgba(255, 255, 255, 0.2); color: white; border: 2px solid rgba(255, 255, 255, 0.3); padding: 12px 30px; border-radius: 25px; font-size: 16px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; text-decoration: none; display: inline-block;">
                    📊 View DTR
                </a>
            </div>
        @endif

        <!-- Attendance Notice (self-service) -->
        <div class="attendance-notification" id="selfAttendanceNotice" style="display:none;">
            <h3>⏰ Working Hours</h3>
            <p>Time In is available Monday to Saturday, 8:00 AM to 4:59 PM. Time Out is automatically recorded at 5:00 PM.</p>
            <span class="attendance-status status-released">Self Service</span>
        </div>

        <!-- Dashboard Cards -->
        <div class="dashboard-grid">
      
            <div class="dashboard-card">
                <div class="card-header">
                    <div class="card-icon icon-journal">
                        📝
                    </div>
                    <div>
                        <div class="card-title">Daily Journal</div>
                    </div>
                </div>
                <div class="card-content">
                    Submit your daily journal entries to document your learning experiences and tasks completed.
                </div>
                <div class="card-actions">
                    <button class="card-btn" onclick="openJournalModal()">Write Journal</button>
                </div>
            </div>

            <div class="dashboard-card">
                <div class="card-header">
                    <div class="card-icon icon-messages">
                        💬
                    </div>
                    <div>
                        <div class="card-title">Messages</div>
                        @if($unreadMessages > 0)
                            <div class="unread-badge">{{ $unreadMessages }}</div>
                        @endif
                    </div>
                </div>
                <div class="card-content">
                    View your conversation with the admin and send messages in real-time.
                </div>
                <div class="card-actions">
                    <a href="{{ route('intern.messages') }}" class="card-btn">Open Admin Chat</a>
                </div>
            </div>

            <div class="dashboard-card">
                <div class="card-header">
                    <div class="card-icon icon-documents">
                        📄
                    </div>
                    <div>
                        <div class="card-title">Documents</div>
                    </div>
                </div>
                <div class="card-content">
                    Upload and manage your required documents including grades and evaluations.
                </div>
                <div class="card-actions">
                    <button class="card-btn" onclick="openUploadDocumentsModal()">Upload Documents</button>
                </div>
            </div>

            

            
            <!-- Real-time DTR Widget -->
            <div class="dashboard-card dtr-widget">
                <div class="card-header">
                    <div class="card-icon icon-dtr">
                        ⏰
                    </div>
                    <div>
                        <div class="card-title">Real-time DTR</div>
                        <div class="current-time" id="currentTime"></div>
                    </div>
                </div>
                <div class="card-content">
                    <div class="dtr-status" id="dtrStatus">
                        
                        <div class="status-item">
                            <span class="label">Time In:</span>
                            <span class="value" id="todayTimeIn">-</span>
                        </div>
                        <div class="status-item">
                            <span class="label">Time Out:</span>
                            <span class="value" id="todayTimeOut">-</span>
                        </div>
                        
                        
                    </div>
                    <div class="dtr-actions" style="margin-top: 15px; display: flex; gap: 10px; flex-wrap: wrap;">
                        <button type="button" id="timeInBtn" class="time-action-btn time-in-btn">
                            <span class="btn-icon">⏰</span>
                            <span class="btn-text">Time In</span>
                        </button>
                        <button type="button" id="timeOutBtn" class="time-action-btn time-out-btn">
                            <span class="btn-icon">🕐</span>
                            <span class="btn-text">Time Out</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Upload Documents Modal -->
    <div id="uploadDocumentsModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeUploadDocumentsModal()">&times;</span>
            <h2>📎 Send Grades</h2>
            <div id="uploadDocumentsAlert"></div>
            <form id="uploadDocumentsForm" enctype="multipart/form-data">
                @csrf
                <label for="grade_doc">Upload .doc or .docx file:</label>
                <input type="file" name="grade_doc" id="grade_doc" accept=".doc,.docx" required>

                <label for="semester">Select Document Type:</label>
                <select name="semester" id="semester" required>
                    <option value="">-- Choose --</option>
                    <option value="3rd">Certificate</option>
                    <option value="4th">Evaluation Form</option>
                </select>

                <button type="submit">✅ Submit Data</button>
            </form>

            @if(!empty($pendingRequests))
                <div class="request-list">
                    <strong>📢 Pending Document Requests:</strong>
                    <ul>
                        @foreach($pendingRequests as $req)
                            <li>{{ ucfirst($req) }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>

    <!-- Journal Modal -->
    <div id="journalModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeJournalModal()">&times;</span>
            <h2>📎 Upload Journal Entry (.docx)</h2>
            <div id="journalAlert"></div>
            <form id="journalForm" enctype="multipart/form-data">
                @csrf
                <label for="journal_file">Attach .docx File:</label>
                <input type="file" name="journal_file" id="journal_file" accept=".docx" required>

                <button type="submit">Upload Entry</button>
            </form>
        </div>
    </div>

    <script>
        // Auto-refresh attendance status every 30 seconds
        setInterval(function() {
            location.reload();
        }, 30000);

        // Quick Message Form Handler
        document.getElementById('quickMessageForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const message = formData.get('message');
            
            if (!message.trim()) {
                alert('Please enter a message');
                return;
            }
            
            // Send message via AJAX
            fetch('{{ route("intern.messages.send") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    message: message,
                    receiver_type: 'admin'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Message sent successfully!');
                    this.reset();
                    // Refresh page to update unread count
                    setTimeout(() => location.reload(), 1000);
                } else {
                    alert('Error sending message: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error sending message. Please try again.');
            });
        });

        // Real-time DTR Functions
        function updateCurrentTime() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('en-US', { 
                hour12: true, 
                hour: '2-digit', 
                minute: '2-digit', 
                second: '2-digit' 
            });
            document.getElementById('currentTime').textContent = timeString;
        }

        function updateDTRStatus() {
            fetch('{{ route("intern.dtr.summary") }}')
                .then(response => response.json())
                .then(data => {
                    // Format time display
                    const formatTime = (timeString) => {
                        if (!timeString || timeString === '-') return '-';
                        try {
                            const [hours, minutes, seconds] = timeString.split(':');
                            const hour = parseInt(hours);
                            const ampm = hour >= 12 ? 'PM' : 'AM';
                            const displayHour = hour % 12 || 12;
                            return `${displayHour}:${minutes} ${ampm}`;
                        } catch (e) {
                            return timeString;
                        }
                    };

                    document.getElementById('todayTimeIn').textContent = formatTime(data.today_time_in);
                    document.getElementById('todayTimeOut').textContent = formatTime(data.today_time_out);
                    
                    // Update button states
                    const timeInBtn = document.getElementById('timeInBtn');
                    const timeOutBtn = document.getElementById('timeOutBtn');
                    
                    const now = new Date();
                    const currentHour = now.getHours();
                    const currentMinute = now.getMinutes();
                    // Time In is only available from 8:00 AM to 4:59 PM (hour 8 to 16, minute 0-59)
                    // After 4:59 PM (hour 16, minute 59), time-in is disabled (auto-timeout at 5:00 PM)
                    const canTimeIn = currentHour >= 8 && currentHour < 17;
                    // Time Out can be done manually until 4:59 PM, then auto-timeout at 5:00 PM
                    const canTimeOut = currentHour < 17;
                    
                    const withinHours = !!data.is_working_hours && !!data.is_workday;
                    document.getElementById('selfAttendanceNotice').style.display = 'block';

                    if (!withinHours) {
                        timeInBtn.disabled = true;
                        timeOutBtn.disabled = true;
                    } else if (data.today_status === 'not_started') {
                        // Can only time in from 8:00 AM to 4:59 PM
                        if (timeInBtn) {
                            timeInBtn.disabled = !canTimeIn;
                            const timeInText = timeInBtn.querySelector('.btn-text');
                            if (timeInText) timeInText.textContent = 'Time In';
                        }
                        if (timeOutBtn) {
                            timeOutBtn.disabled = true;
                            const timeOutText = timeOutBtn.querySelector('.btn-text');
                            if (timeOutText) timeOutText.textContent = 'Time Out';
                        }
                    } else if (data.today_status === 'working') {
                        if (timeInBtn) {
                            timeInBtn.disabled = true;
                            const timeInText = timeInBtn.querySelector('.btn-text');
                            if (timeInText) timeInText.textContent = 'Time In';
                        }
                        // Can time out until 5:00 PM (auto-timeout happens at 5:00 PM)
                        if (timeOutBtn) {
                            timeOutBtn.disabled = !canTimeOut;
                            const timeOutText = timeOutBtn.querySelector('.btn-text');
                            if (timeOutText) timeOutText.textContent = 'Time Out';
                        }
                    } else {
                        if (timeInBtn) {
                            timeInBtn.disabled = true;
                            const timeInText = timeInBtn.querySelector('.btn-text');
                            if (timeInText) timeInText.textContent = 'Time In';
                        }
                        if (timeOutBtn) {
                            timeOutBtn.disabled = true;
                            const timeOutText = timeOutBtn.querySelector('.btn-text');
                            if (timeOutText) timeOutText.textContent = 'Time Out';
                        }
                    }
                })
                .catch(error => {
                    console.error('Error fetching DTR status:', error);
                });
        }

        // Time In/Out Button Handlers - Fully Functional Implementation
        (function() {
            'use strict';
            
            let buttonsInitialized = false;
            
            function handleTimeIn() {
                const btn = document.getElementById('timeInBtn');
                if (!btn || btn.disabled) return;
                
                // Set processing state
                btn.disabled = true;
                btn.classList.add('processing');
                const btnText = btn.querySelector('.btn-text');
                const originalText = btnText.textContent;
                btnText.textContent = 'Processing...';
                
                // Get CSRF token
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';
                
                // Make request
                fetch('{{ route("intern.timein") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({})
                })
                .then(response => response.text().then(text => {
                    try {
                        const data = JSON.parse(text);
                        return { ok: response.ok, status: response.status, data: data };
                    } catch (e) {
                        return { ok: false, status: response.status, data: { success: false, message: 'Invalid server response' } };
                    }
                }))
                .then(result => {
                    btn.classList.remove('processing');
                    
                    if (result.ok && result.data && result.data.success) {
                        // Success
                        Swal.fire({
                            icon: 'success',
                            title: '✅ Time In Successful!',
                            text: result.data.message || 'Time In recorded successfully!',
                            timer: 3000,
                            showConfirmButton: true,
                            confirmButtonText: 'OK'
                        });
                        updateDTRStatus();
                    } else {
                        // Error/Warning
                        const message = result.data?.message || 'Failed to record Time In';
                        const isWarning = result.status === 400 && (message.includes('already') || message.includes('available'));
                        
                        Swal.fire({
                            icon: isWarning ? 'warning' : 'error',
                            title: isWarning ? '⚠️ Warning' : '❌ Time In Failed',
                            text: message,
                            showConfirmButton: true
                        });
                        
                        if (!isWarning || !message.includes('already')) {
                            btn.disabled = false;
                            btnText.textContent = originalText;
                        }
                    }
                })
                .catch(error => {
                    btn.classList.remove('processing');
                    Swal.fire({
                        icon: 'error',
                        title: '❌ Connection Error',
                        text: 'Failed to connect to server. Please check your connection.',
                        showConfirmButton: true
                    });
                    btn.disabled = false;
                    btnText.textContent = originalText;
                });
            }
            
            function handleTimeOut() {
                const btn = document.getElementById('timeOutBtn');
                if (!btn || btn.disabled) return;
                
                // Set processing state
                btn.disabled = true;
                btn.classList.add('processing');
                const btnText = btn.querySelector('.btn-text');
                const originalText = btnText.textContent;
                btnText.textContent = 'Processing...';
                
                // Get CSRF token
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';
                
                // Make request
                fetch('{{ route("intern.timeout") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({})
                })
                .then(response => response.text().then(text => {
                    try {
                        const data = JSON.parse(text);
                        return { ok: response.ok, status: response.status, data: data };
                    } catch (e) {
                        return { ok: false, status: response.status, data: { success: false, message: 'Invalid server response' } };
                    }
                }))
                .then(result => {
                    btn.classList.remove('processing');
                    
                    if (result.ok && result.data && result.data.success) {
                        // Success
                        Swal.fire({
                            icon: 'success',
                            title: '✅ Time Out Successful!',
                            text: result.data.message || 'Time Out recorded successfully!',
                            timer: 3000,
                            showConfirmButton: true,
                            confirmButtonText: 'OK'
                        });
                        updateDTRStatus();
                    } else {
                        // Error/Warning
                        const message = result.data?.message || 'Failed to record Time Out';
                        const isWarning = result.status === 400 && (message.includes('already') || message.includes('must time in'));
                        
                        Swal.fire({
                            icon: isWarning ? 'warning' : 'error',
                            title: isWarning ? '⚠️ Warning' : '❌ Time Out Failed',
                            text: message,
                            showConfirmButton: true
                        });
                        
                        if (!isWarning || !message.includes('already')) {
                            btn.disabled = false;
                            btnText.textContent = originalText;
                        }
                    }
                })
                .catch(error => {
                    btn.classList.remove('processing');
                    Swal.fire({
                        icon: 'error',
                        title: '❌ Connection Error',
                        text: 'Failed to connect to server. Please check your connection.',
                        showConfirmButton: true
                    });
                    btn.disabled = false;
                    btnText.textContent = originalText;
                });
            }
            
            function initializeButtons() {
                if (buttonsInitialized) return;
                
                const timeInBtn = document.getElementById('timeInBtn');
                const timeOutBtn = document.getElementById('timeOutBtn');
                
                if (timeInBtn) {
                    timeInBtn.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        handleTimeIn();
                    });
                }
                
                if (timeOutBtn) {
                    timeOutBtn.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        handleTimeOut();
                    });
                }
                
                buttonsInitialized = true;
            }
            
            // Initialize when ready
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initializeButtons);
            } else {
                initializeButtons();
            }
            
            // Fallback initialization
            setTimeout(initializeButtons, 100);
            window.addEventListener('load', () => setTimeout(initializeButtons, 100));
        })();

        // Initialize DTR functionality
        updateCurrentTime();
        updateDTRStatus();
        
        // Update time every second
        setInterval(updateCurrentTime, 1000);
        
        // Update DTR status every 30 seconds
        setInterval(updateDTRStatus, 30000);

        // Modal Functions
        function openUploadDocumentsModal() {
            document.getElementById('uploadDocumentsModal').style.display = 'block';
        }

        function closeUploadDocumentsModal() {
            document.getElementById('uploadDocumentsModal').style.display = 'none';
            document.getElementById('uploadDocumentsAlert').innerHTML = '';
            document.getElementById('uploadDocumentsForm').reset();
        }

        function openJournalModal() {
            document.getElementById('journalModal').style.display = 'block';
        }

        function closeJournalModal() {
            document.getElementById('journalModal').style.display = 'none';
            document.getElementById('journalAlert').innerHTML = '';
            document.getElementById('journalForm').reset();
        }

        // Close modals when clicking outside
        window.onclick = function(event) {
            const uploadModal = document.getElementById('uploadDocumentsModal');
            const journalModal = document.getElementById('journalModal');
            if (event.target == uploadModal) {
                closeUploadDocumentsModal();
            }
            if (event.target == journalModal) {
                closeJournalModal();
            }
        }

        // Upload Documents Form Handler
        document.getElementById('uploadDocumentsForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const alertDiv = document.getElementById('uploadDocumentsAlert');
            
            fetch('{{ route("intern.uploadDocx") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alertDiv.innerHTML = '<div class="alert alert-success">File successfully uploaded.</div>';
                    this.reset();
                    setTimeout(() => {
                        closeUploadDocumentsModal();
                        location.reload();
                    }, 1500);
                } else {
                    alertDiv.innerHTML = '<div class="alert alert-error">' + (data.message || 'Error uploading file.') + '</div>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alertDiv.innerHTML = '<div class="alert alert-error">Error uploading file. Please try again.</div>';
            });
        });

        // Journal Form Handler
        document.getElementById('journalForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const alertDiv = document.getElementById('journalAlert');
            
            fetch('{{ route("intern.journal.submit") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alertDiv.innerHTML = '<div class="alert alert-success">Journal uploaded successfully!</div>';
                    this.reset();
                    setTimeout(() => {
                        closeJournalModal();
                        location.reload();
                    }, 1500);
                } else {
                    alertDiv.innerHTML = '<div class="alert alert-error">' + (data.error || data.message || 'Error uploading journal.') + '</div>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alertDiv.innerHTML = '<div class="alert alert-error">Error uploading journal. Please try again.</div>';
            });
        });
    </script>
</body>
</html>