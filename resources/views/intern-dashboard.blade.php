<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Intern Dashboard</title>
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
            margin-bottom: 15px;
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

        .icon-dtr { background: #10b981; }

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
        .icon-dtr { background: #10b981; }

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
                    <a href="{{ route('intern.journal') }}" class="card-btn">Write Journal</a>
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
                    <a href="{{ route('intern.send-data') }}" class="card-btn">Upload Documents</a>
                </div>
            </div>

            <!-- Real-time DTR Widget -->
            <div class="dashboard-card dtr-widget" style="grid-column: span 2 !important; display: block !important; visibility: visible !important;">
                <div class="card-header">
                    <div class="card-icon icon-dtr" style="background: #10b981 !important;">
                        ⏰
                    </div>
                    <div>
                        <div class="card-title">Daily Time Record (DTR)</div>
                        <div class="current-time" id="currentTime" style="font-size: 14px !important; color: #6b7280 !important; margin-top: 5px !important;"></div>
                    </div>
                </div>
                <div class="card-content">
                    <div class="dtr-status" id="dtrStatus" style="display: grid !important; grid-template-columns: 1fr 1fr !important; gap: 15px !important; margin-bottom: 15px !important;">
                        <div class="status-item" style="display: flex !important; justify-content: space-between !important; align-items: center !important; padding: 10px !important; background: #f8fafc !important; border-radius: 6px !important; border: 1px solid #e2e8f0 !important;">
                            <span class="label" style="font-weight: 600 !important; color: #374151 !important; font-size: 14px !important;">Time In:</span>
                            <span class="value" id="todayTimeIn" style="font-weight: 700 !important; color: #1f2937 !important; font-size: 14px !important;">-</span>
                        </div>
                        <div class="status-item" style="display: flex !important; justify-content: space-between !important; align-items: center !important; padding: 10px !important; background: #f8fafc !important; border-radius: 6px !important; border: 1px solid #e2e8f0 !important;">
                            <span class="label" style="font-weight: 600 !important; color: #374151 !important; font-size: 14px !important;">Time Out:</span>
                            <span class="value" id="todayTimeOut" style="font-weight: 700 !important; color: #1f2937 !important; font-size: 14px !important;">-</span>
                        </div>
                    </div>
                    <div class="dtr-actions" style="margin-top: 15px !important; display: flex !important; gap: 10px !important; flex-wrap: wrap !important;">
                        <button id="timeInBtn" class="card-btn" type="button" onclick="handleTimeIn()" style="background: #10b981 !important; color: white !important; margin-right: 10px !important; padding: 10px 20px !important; border-radius: 8px !important; font-weight: 500 !important; cursor: pointer !important; border: none !important; display: inline-block !important;">Time In</button>
                        <button id="timeOutBtn" class="card-btn" type="button" onclick="handleTimeOut()" style="background: #f59e0b !important; color: white !important; padding: 10px 20px !important; border-radius: 8px !important; font-weight: 500 !important; cursor: pointer !important; border: none !important; display: inline-block !important;">Time Out</button>
                    </div>
                    <div style="margin-top: 15px; padding: 10px; background: #f0f9ff; border-radius: 6px; font-size: 12px; color: #0369a1;">
                        <strong>Progress:</strong> <span id="totalHoursDisplay">0</span> / 486 hours (<span id="progressPercent">0</span>%)
                    </div>
                </div>
            </div>

        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Auto-refresh attendance status every 30 seconds
        setInterval(function() {
            updateDTRStatus();
        }, 30000);

        // Quick Message Form Handler
        const quickMessageForm = document.getElementById('quickMessageForm');
        if (quickMessageForm) {
            quickMessageForm.addEventListener('submit', function(e) {
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
        }

        // Real-time DTR Functions
        function updateCurrentTime() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('en-US', { 
                hour12: true, 
                hour: '2-digit', 
                minute: '2-digit', 
                second: '2-digit' 
            });
            const timeElement = document.getElementById('currentTime');
            if (timeElement) {
                timeElement.textContent = timeString;
            }
        }

        function updateDTRStatus() {
            fetch('{{ route("intern.dtr.summary") }}')
                .then(response => response.json())
                .then(data => {
                    // Format time display
                    const formatTime = (timeString) => {
                        if (!timeString || timeString === '-') return '-';
                        try {
                            const [hours, minutes] = timeString.split(':');
                            const hour = parseInt(hours);
                            const ampm = hour >= 12 ? 'PM' : 'AM';
                            const displayHour = hour % 12 || 12;
                            return `${displayHour}:${minutes} ${ampm}`;
                        } catch (e) {
                            return timeString;
                        }
                    };

                    const timeInElement = document.getElementById('todayTimeIn');
                    const timeOutElement = document.getElementById('todayTimeOut');
                    const totalHoursElement = document.getElementById('totalHoursDisplay');
                    const progressPercentElement = document.getElementById('progressPercent');
                    
                    if (timeInElement) {
                        timeInElement.textContent = formatTime(data.today_time_in);
                    }
                    if (timeOutElement) {
                        timeOutElement.textContent = formatTime(data.today_time_out);
                    }
                    if (totalHoursElement) {
                        totalHoursElement.textContent = data.total_hours.toFixed(2);
                    }
                    if (progressPercentElement) {
                        progressPercentElement.textContent = data.progress_percent.toFixed(1);
                    }
                    
                    // Update button states
                    const timeInBtn = document.getElementById('timeInBtn');
                    const timeOutBtn = document.getElementById('timeOutBtn');
                    
                    const withinHours = !!data.is_working_hours && !!data.is_workday;

                    if (timeInBtn && timeOutBtn) {
                        // Reset button states first
                        timeInBtn.style.cursor = 'pointer';
                        timeOutBtn.style.cursor = 'pointer';
                        
                        if (!withinHours) {
                            timeInBtn.disabled = true;
                            timeOutBtn.disabled = true;
                            timeInBtn.style.opacity = '0.5';
                            timeOutBtn.style.opacity = '0.5';
                            timeInBtn.style.cursor = 'not-allowed';
                            timeOutBtn.style.cursor = 'not-allowed';
                        } else if (data.today_status === 'not_started') {
                            timeInBtn.disabled = false;
                            timeOutBtn.disabled = true;
                            timeInBtn.style.opacity = '1';
                            timeOutBtn.style.opacity = '0.5';
                            timeInBtn.style.cursor = 'pointer';
                            timeOutBtn.style.cursor = 'not-allowed';
                        } else if (data.today_status === 'working') {
                            timeInBtn.disabled = true;
                            timeOutBtn.disabled = false;
                            timeInBtn.style.opacity = '0.5';
                            timeOutBtn.style.opacity = '1';
                            timeInBtn.style.cursor = 'not-allowed';
                            timeOutBtn.style.cursor = 'pointer';
                        } else {
                            timeInBtn.disabled = true;
                            timeOutBtn.disabled = true;
                            timeInBtn.style.opacity = '0.5';
                            timeOutBtn.style.opacity = '0.5';
                            timeInBtn.style.cursor = 'not-allowed';
                            timeOutBtn.style.cursor = 'not-allowed';
                        }
                    }
                })
                .catch(error => {
                    console.error('Error fetching DTR status:', error);
                });
        }

        // Time In Handler
        function handleTimeIn() {
            const btn = document.getElementById('timeInBtn');
            if (!btn) {
                Swal.fire({
                    icon: 'error',
                    title: '❌ Error',
                    text: 'Time In button not found. Please refresh the page.',
                    showConfirmButton: true,
                    confirmButtonText: 'OK'
                });
                return;
            }
            
            if (btn.disabled) {
                Swal.fire({
                    icon: 'warning',
                    title: '⚠️ Time In Not Available',
                    text: 'Time In is currently not available. Please check your status or try again later.',
                    showConfirmButton: true,
                    confirmButtonText: 'OK'
                });
                return;
            }
            
            btn.disabled = true;
            const originalText = btn.textContent;
            btn.textContent = 'Processing...';
            
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';
            
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
            .then(response => {
                const contentType = response.headers.get('content-type');
                if (contentType && contentType.includes('application/json')) {
                    return response.json().then(data => ({ ok: response.ok, status: response.status, data: data }));
                } else {
                    return response.text().then(text => {
                        try {
                            const data = JSON.parse(text);
                            return { ok: response.ok, status: response.status, data: data };
                        } catch (e) {
                            return { ok: false, status: response.status, data: { success: false, message: text || 'Server error occurred' } };
                        }
                    });
                }
            })
            .then(result => {
                if (result.ok && result.data && result.data.success) {
                    // Success
                    Swal.fire({
                        icon: 'success',
                        title: '✅ Time In Successful!',
                        text: result.data.message || 'Time In recorded successfully!',
                        timer: 3000,
                        showConfirmButton: true,
                        confirmButtonText: 'OK'
                    }).then(() => {
                        updateDTRStatus();
                    });
                    setTimeout(() => updateDTRStatus(), 500);
                } else {
                    // Error/Warning handling
                    const message = result.data?.message || 'Failed to record Time In';
                    const status = result.status || 500;
                    
                    // Determine alert type based on message content
                    let alertType = 'error';
                    let alertTitle = '❌ Time In Failed';
                    
                    if (status === 400) {
                        if (message.includes('already') || message.includes('already timed in')) {
                            alertType = 'warning';
                            alertTitle = '⚠️ Already Timed In';
                        } else if (message.includes('Saturday') || message.includes('Sunday') || message.includes('weekend')) {
                            alertType = 'warning';
                            alertTitle = '⚠️ Weekend - Time In Not Available';
                        } else if (message.includes('5:00 PM') || message.includes('5:00PM') || message.includes('17:00')) {
                            alertType = 'warning';
                            alertTitle = '⚠️ Time In Not Available';
                        } else {
                            alertType = 'warning';
                            alertTitle = '⚠️ Warning';
                        }
                    } else if (status === 500 || status >= 500) {
                        alertType = 'error';
                        alertTitle = '❌ Server Error';
                    }
                    
                    Swal.fire({
                        icon: alertType,
                        title: alertTitle,
                        text: message,
                        showConfirmButton: true,
                        confirmButtonText: 'OK'
                    });
                    
                    // Re-enable button if it's not a permanent state (already timed in)
                    if (!message.includes('already') && !message.includes('already timed in')) {
                        btn.disabled = false;
                        btn.textContent = originalText;
                    }
                }
            })
            .catch(error => {
                console.error('Time In Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: '❌ Connection Error',
                    text: 'Failed to connect to server. Please check your internet connection and try again.',
                    showConfirmButton: true,
                    confirmButtonText: 'OK'
                });
                btn.disabled = false;
                btn.textContent = originalText;
            });
        }
        
        // Time Out Handler
        function handleTimeOut() {
            const btn = document.getElementById('timeOutBtn');
            if (!btn) {
                Swal.fire({
                    icon: 'error',
                    title: '❌ Error',
                    text: 'Time Out button not found. Please refresh the page.',
                    showConfirmButton: true,
                    confirmButtonText: 'OK'
                });
                return;
            }
            
            if (btn.disabled) {
                Swal.fire({
                    icon: 'warning',
                    title: '⚠️ Time Out Not Available',
                    text: 'Time Out is currently not available. You must time in first, or you may have already timed out today.',
                    showConfirmButton: true,
                    confirmButtonText: 'OK'
                });
                return;
            }
            
            btn.disabled = true;
            const originalText = btn.textContent;
            btn.textContent = 'Processing...';
            
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';
            
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
            .then(response => {
                const contentType = response.headers.get('content-type');
                if (contentType && contentType.includes('application/json')) {
                    return response.json().then(data => ({ ok: response.ok, status: response.status, data: data }));
                } else {
                    return response.text().then(text => {
                        try {
                            const data = JSON.parse(text);
                            return { ok: response.ok, status: response.status, data: data };
                        } catch (e) {
                            return { ok: false, status: response.status, data: { success: false, message: text || 'Server error occurred' } };
                        }
                    });
                }
            })
            .then(result => {
                if (result.ok && result.data && result.data.success) {
                    // Success
                    Swal.fire({
                        icon: 'success',
                        title: '✅ Time Out Successful!',
                        text: result.data.message || 'Time Out recorded successfully!',
                        timer: 3000,
                        showConfirmButton: true,
                        confirmButtonText: 'OK'
                    }).then(() => {
                        updateDTRStatus();
                    });
                    setTimeout(() => updateDTRStatus(), 500);
                } else {
                    // Error/Warning handling
                    const message = result.data?.message || 'Failed to record Time Out';
                    const status = result.status || 500;
                    
                    // Determine alert type based on message content
                    let alertType = 'error';
                    let alertTitle = '❌ Time Out Failed';
                    
                    if (status === 400) {
                        if (message.includes('already') || message.includes('already timed out')) {
                            alertType = 'warning';
                            alertTitle = '⚠️ Already Timed Out';
                        } else if (message.includes('must time in') || message.includes('time in first')) {
                            alertType = 'error';
                            alertTitle = '❌ Time In Required';
                        } else if (message.includes('Saturday') || message.includes('Sunday') || message.includes('weekend')) {
                            alertType = 'warning';
                            alertTitle = '⚠️ Weekend - Time Out Not Available';
                        } else {
                            alertType = 'warning';
                            alertTitle = '⚠️ Warning';
                        }
                    } else if (status === 500 || status >= 500) {
                        alertType = 'error';
                        alertTitle = '❌ Server Error';
                    }
                    
                    Swal.fire({
                        icon: alertType,
                        title: alertTitle,
                        text: message,
                        showConfirmButton: true,
                        confirmButtonText: 'OK'
                    });
                    
                    // Re-enable button if it's not a permanent state (already timed out)
                    if (!message.includes('already') && !message.includes('already timed out')) {
                        btn.disabled = false;
                        btn.textContent = originalText;
                    }
                }
            })
            .catch(error => {
                console.error('Time Out Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: '❌ Connection Error',
                    text: 'Failed to connect to server. Please check your internet connection and try again.',
                    showConfirmButton: true,
                    confirmButtonText: 'OK'
                });
                btn.disabled = false;
                btn.textContent = originalText;
            });
        }
        
        // Make functions globally available for onclick handlers
        window.handleTimeIn = handleTimeIn;
        window.handleTimeOut = handleTimeOut;
        
        // Attach event listeners as backup (onclick is primary)
        function attachTimeHandlers() {
            const timeInBtn = document.getElementById('timeInBtn');
            const timeOutBtn = document.getElementById('timeOutBtn');
            
            // Attach listeners only if not already attached
            if (timeInBtn && !timeInBtn.hasAttribute('data-listener-attached')) {
                timeInBtn.setAttribute('data-listener-attached', 'true');
                timeInBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    if (!timeInBtn.disabled) {
                        handleTimeIn();
                    }
                });
            }
            
            if (timeOutBtn && !timeOutBtn.hasAttribute('data-listener-attached')) {
                timeOutBtn.setAttribute('data-listener-attached', 'true');
                timeOutBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    if (!timeOutBtn.disabled) {
                        handleTimeOut();
                    }
                });
            }
        }
        
        // Initialize handlers
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', attachTimeHandlers);
        } else {
            attachTimeHandlers();
        }
        
        // Fallback initialization
        setTimeout(attachTimeHandlers, 100);
        setTimeout(attachTimeHandlers, 500);
        window.addEventListener('load', () => setTimeout(attachTimeHandlers, 100));

        // Initialize DTR functionality
        updateCurrentTime();
        updateDTRStatus();
        
        // Update time every second
        setInterval(updateCurrentTime, 1000);
        
        // Update DTR status every 30 seconds
        setInterval(updateDTRStatus, 30000);
    </script>
</body>
</html>