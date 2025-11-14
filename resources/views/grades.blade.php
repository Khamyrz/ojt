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

        .grades-container { max-width: 1400px; margin: 0 auto; background: white; border-radius: 16px; padding: 32px; box-shadow: var(--shadow-lg); }

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

        /* Filter Section */
        .filter-section {
            background: var(--light);
            padding: 24px;
            border-radius: 12px;
            margin-bottom: 24px;
        }

        .filter-controls {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            align-items: center;
        }

        .filter-btn {
            padding: 10px 20px;
            border: 2px solid transparent;
            background: white;
            color: var(--dark);
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            position: relative;
        }

        .filter-btn:hover {
            background: var(--primary);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        }

        .filter-btn.active {
            background: var(--primary);
            color: white;
            border-color: var(--primary-dark);
        }

        .badge {
            background: var(--danger);
            color: white;
            border-radius: 12px;
            padding: 2px 8px;
            font-size: 11px;
            font-weight: 700;
            min-width: 20px;
            text-align: center;
        }

        /* Search Bar */
        .search-container {
            flex: 1;
            min-width: 250px;
            max-width: 400px;
            position: relative;
        }

        .search-wrapper {
            display: flex;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: var(--shadow);
            border: 2px solid transparent;
            transition: all 0.3s ease;
        }

        .search-wrapper:focus-within {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
        }

        .search-input {
            flex: 1;
            padding: 12px 16px;
            border: none;
            font-size: 14px;
            outline: none;
            font-family: inherit;
        }

        .search-input::placeholder {
            color: var(--secondary);
        }

        .search-btn {
            padding: 12px 20px;
            border: none;
            background: var(--success);
            color: white;
            cursor: pointer;
            font-weight: 600;
            transition: background 0.3s ease;
        }

        .search-btn:hover {
            background: #059669;
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

        .btn-view {
            background: rgba(139, 92, 246, 0.1);
            color: var(--purple);
            border: 1px solid var(--purple);
        }

        .btn-view:hover {
            background: var(--purple);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3);
        }

        .btn-request {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success);
            border: 1px solid var(--success);
        }

        .btn-request:hover {
            background: var(--success);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
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

        .status-label {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }

        .status-requested {
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning);
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

        /* Section Badge */
        .section-badge {
            padding: 4px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
            background: rgba(37, 99, 235, 0.1);
            color: var(--primary);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .grades-container {
                padding: 20px;
            }

            .filter-controls {
                flex-direction: column;
                align-items: stretch;
            }

            .search-container {
                max-width: 100%;
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

        /* Broadcast Section */
        .broadcast-section {
            background: #f1f5f9;
            padding: 24px;
            border-radius: 12px;
            margin-bottom: 24px;
            border: 2px solid #e2e8f0;
            display: block;
            visibility: visible;
        }

        .broadcast-header {
            margin-bottom: 16px;
        }

        .broadcast-header h3 {
            margin: 0 0 8px 0;
            color: #1e293b;
            font-size: 18px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .broadcast-header p {
            margin: 0;
            color: #64748b;
            font-size: 14px;
        }

        .broadcast-buttons {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        /* Broadcast Button Styles */
        .btn-broadcast {
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            min-width: 250px;
            border: 2px solid;
            white-space: nowrap;
        }

        .btn-certificate {
            background: #10b981;
            color: white;
            border-color: #10b981;
        }

        .btn-certificate:hover:not(:disabled) {
            background: #059669;
            border-color: #059669;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        .btn-evaluation {
            background: #8b5cf6;
            color: white;
            border-color: #8b5cf6;
        }

        .btn-evaluation:hover:not(:disabled) {
            background: #7c3aed;
            border-color: #7c3aed;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3);
        }

        .btn-broadcast:disabled {
            cursor: not-allowed;
            opacity: 0.6;
        }

        /* Ensure broadcast section is always visible */
        #broadcastSection {
            display: block !important;
            visibility: visible !important;
            opacity: 1 !important;
            position: relative !important;
            z-index: 10 !important;
            width: 100% !important;
            max-width: 100% !important;
            overflow: visible !important;
            background: #f1f5f9 !important;
            padding: 24px !important;
            border-radius: 12px !important;
            margin-bottom: 24px !important;
            margin-top: 0 !important;
            border: 2px solid #e2e8f0 !important;
            box-sizing: border-box !important;
        }

        #broadcastSection * {
            visibility: visible !important;
        }

        #broadcastSection .broadcast-header {
            margin-bottom: 16px !important;
            display: block !important;
        }

        #broadcastSection .broadcast-header h3 {
            margin: 0 0 8px 0 !important;
            color: #1e293b !important;
            font-size: 18px !important;
            display: flex !important;
            align-items: center !important;
            gap: 8px !important;
            font-weight: 600 !important;
        }

        #broadcastSection .broadcast-header p {
            margin: 0 !important;
            color: #64748b !important;
            font-size: 14px !important;
        }

        #broadcastSection .broadcast-buttons {
            display: flex !important;
            gap: 12px !important;
            flex-wrap: wrap !important;
            width: 100% !important;
        }

        #broadcastCertificateBtn,
        #broadcastEvaluationBtn {
            display: inline-flex !important;
            visibility: visible !important;
            opacity: 1 !important;
            align-items: center !important;
            gap: 8px !important;
            min-width: 280px !important;
            flex: 1 !important;
            max-width: 400px !important;
            padding: 12px 24px !important;
            border-radius: 8px !important;
            font-weight: 600 !important;
            font-size: 14px !important;
            cursor: pointer !important;
            transition: all 0.3s ease !important;
            border: 2px solid !important;
        }

        #broadcastCertificateBtn {
            background: #10b981 !important;
            color: white !important;
            border-color: #10b981 !important;
            box-shadow: 0 2px 4px rgba(16, 185, 129, 0.2) !important;
        }

        #broadcastEvaluationBtn {
            background: #8b5cf6 !important;
            color: white !important;
            border-color: #8b5cf6 !important;
            box-shadow: 0 2px 4px rgba(139, 92, 246, 0.2) !important;
        }

        /* Button hover effects */
        #broadcastCertificateBtn:hover:not(:disabled) {
            background: #059669 !important;
            border-color: #059669 !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3) !important;
        }

        #broadcastEvaluationBtn:hover:not(:disabled) {
            background: #7c3aed !important;
            border-color: #7c3aed !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3) !important;
        }
    </style>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="grades-container">
        <!-- Page Header -->
        <div class="page-header">
            <h1>
                <i class="fas fa-chart-bar"></i>
                Grades Management
            </h1>
            <p>View, request, and manage intern certificates and evaluation forms</p>
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

        <!-- Broadcast Section - Placed right after alerts, before filter section -->
        <div id="broadcastSection" class="broadcast-section">
            <div class="broadcast-header">
                <h3>
                    <i class="fas fa-bullhorn" style="color: #2563eb;"></i>
                    Broadcast Requests to All Interns
                </h3>
                <p>Send document requests to all interns at once</p>
            </div>
            <div class="broadcast-buttons">
                <button type="button" id="broadcastCertificateBtn" data-type="certificate" class="btn-broadcast btn-certificate">
                    <i class="fas fa-certificate"></i>
                    <span id="certificateBtnText">Send Request to all Interns - Certificate</span>
                </button>
                <button type="button" id="broadcastEvaluationBtn" data-type="evaluation" class="btn-broadcast btn-evaluation">
                    <i class="fas fa-file-alt"></i>
                    <span id="evaluationBtnText">Send Request to all Interns - Evaluation Form</span>
                </button>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="filter-section">
            <div class="filter-controls">
                {{-- Show All Button --}}
                <form method="GET" action="{{ route('grades') }}" style="display: inline;">
                    <input type="hidden" name="filter" value="all">
                    <button type="submit" class="filter-btn {{ !request('filter') || request('filter') === 'all' ? 'active' : '' }}">
                        <i class="fas fa-th-large"></i>
                        Show All
                        @if(isset($sectionCounts) && is_array($sectionCounts) && array_sum($sectionCounts) > 0)
                            <span class="badge">{{ array_sum($sectionCounts) }}</span>
                        @endif
                    </button>
                </form>

                {{-- Section Buttons --}}
                @if(isset($sectionCounts) && is_array($sectionCounts))
                    @foreach(array_keys($sectionCounts) as $section)
                        <form method="GET" action="{{ route('grades') }}" style="display: inline;">
                            <input type="hidden" name="filter" value="{{ $section }}">
                            <button type="submit" class="filter-btn {{ request('filter') === $section ? 'active' : '' }}">
                                <i class="fas fa-users"></i>
                                {{ $section }}
                                @if(isset($sectionCounts[$section]) && $sectionCounts[$section] > 0)
                                    <span class="badge">{{ $sectionCounts[$section] }}</span>
                                @endif
                            </button>
                        </form>
                    @endforeach
                @endif

                {{-- Search Bar --}}
                <div class="search-container">
                    <form class="search-wrapper" action="{{ route('grades') }}" method="GET">
                        <input 
                            type="text" 
                            name="search" 
                            class="search-input" 
                            placeholder="Search by name or section..." 
                            value="{{ request('search') }}" 
                            id="searchInput"
                        >
                        <button type="submit" class="search-btn">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Table -->
        @if(isset($interns) && $interns->count() > 0)
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th><i class="fas fa-user"></i> Full Name</th>
                            <th><i class="fas fa-layer-group"></i> Section</th>
                            <th><i class="fas fa-certificate"></i> Certificate</th>
                            <th><i class="fas fa-file-alt"></i> Evaluation Form</th>
                            <th><i class="fas fa-cog"></i> Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($interns as $intern)
                            <tr>
                                <td>
                                    <strong>{{ $intern->first_name }} {{ $intern->last_name }}</strong>
                                </td>
                                <td>
                                    <span class="section-badge">{{ $intern->section }}</span>
                                </td>
                                @foreach(['certificate', 'evaluation'] as $type)
                                    <td>
                                        @php
                                            $hasSubmission = false;
                                            $wasRequested = false;
                                            
                                            if (isset($submissions) && isset($submissions[$intern->id]) && isset($submissions[$intern->id][$type])) {
                                                $submission = $submissions[$intern->id][$type];
                                                $hasSubmission = !empty($submission->file_path ?? null);
                                            }
                                            
                                            if (isset($requests) && isset($requests[$intern->id]) && isset($requests[$intern->id][$type])) {
                                                $wasRequested = true;
                                            }
                                        @endphp

                                        @if($hasSubmission)
                                            @php
                                                $submissionFile = $submissions[$intern->id][$type]->file_path;
                                            @endphp
                                            <a href="{{ asset('storage/' . $submissionFile) }}"
                                               class="btn btn-view" target="_blank">
                                                <i class="fas fa-eye"></i>
                                                View
                                            </a>
                                        @elseif($wasRequested)
                                            <span class="status-label status-requested">
                                                <i class="fas fa-clock"></i>
                                                Requested
                                            </span>
                                        @else
                                            <form action="{{ route('grades.request') }}" method="POST" style="display: inline;" onsubmit="event.preventDefault(); handleIndividualRequest(this);">
                                                @csrf
                                                <input type="hidden" name="intern_id" value="{{ $intern->id }}">
                                                <input type="hidden" name="type" value="{{ $type }}">
                                                <button type="submit" class="btn btn-request">
                                                    <i class="fas fa-paper-plane"></i>
                                                    Request
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                @endforeach
                                <td>
                                    <div class="action-buttons">
                                        <form action="{{ route('intern.destroy', $intern->id) }}" method="POST" 
                                              onsubmit="return confirm('Are you absolutely sure you want to delete {{ $intern->first_name }} {{ $intern->last_name }}? This will permanently delete the intern and ALL their data including time logs, journals, grades, messages, and documents. This action cannot be undone!')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-delete">
                                                <i class="fas fa-trash-alt"></i>
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
                <p>
                    @if(request('search'))
                        No results found for "{{ request('search') }}"
                    @elseif(request('filter') && request('filter') !== 'all')
                        No accepted interns for section {{ request('filter') }}
                    @else
                        No accepted interns available at the moment
                    @endif
                </p>
            </div>
        @endif
    </div>

    <script>
        // Broadcast Request Function
        function broadcastRequest(type) {
            const btnId = type === 'certificate' ? 'broadcastCertificateBtn' : 'broadcastEvaluationBtn';
            const btnTextId = type === 'certificate' ? 'certificateBtnText' : 'evaluationBtnText';
            const btn = document.getElementById(btnId);
            const btnText = document.getElementById(btnTextId);
            const icon = btn.querySelector('i');
            
            // Disable button and show loading
            btn.disabled = true;
            btn.style.opacity = '0.6';
            btn.style.cursor = 'not-allowed';
            const originalText = btnText.textContent;
            btnText.textContent = 'Sending...';
            
            // Get CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';
            
            // Broadcast route URL
            const broadcastRouteUrl = '{{ url("/grades/broadcast") }}';
            
            // Make request
            fetch(broadcastRouteUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    type: type
                })
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
                        title: '✅ Request Sent Successfully!',
                        text: result.data.message || `Request for ${type === 'certificate' ? 'Certificate' : 'Evaluation Form'} has been sent to all interns.`,
                        timer: 3000,
                        showConfirmButton: true,
                        confirmButtonText: 'OK'
                    });
                    
                    // Update button state
                    btnText.textContent = 'Requested';
                    btn.classList.remove('btn-certificate', 'btn-evaluation');
                    btn.style.background = '#64748b';
                    btn.style.color = 'white';
                    btn.style.borderColor = '#64748b';
                    icon.className = 'fas fa-check-circle';
                    
                    // Update all individual request buttons in the table
                    updateRequestButtons(type);
                } else {
                    // Error/Warning
                    const message = result.data?.message || `Failed to send request for ${type === 'certificate' ? 'Certificate' : 'Evaluation Form'}`;
                    const isWarning = result.status === 400;
                    
                    Swal.fire({
                        icon: isWarning ? 'warning' : 'error',
                        title: isWarning ? '⚠️ Warning' : '❌ Request Failed',
                        text: message,
                        showConfirmButton: true,
                        confirmButtonText: 'OK'
                    });
                    
                    // Re-enable button
                    btn.disabled = false;
                    btn.style.opacity = '1';
                    btn.style.cursor = 'pointer';
                    btnText.textContent = originalText;
                }
            })
            .catch(error => {
                console.error('Broadcast Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: '❌ Connection Error',
                    text: 'Failed to connect to server. Please check your connection and try again.',
                    showConfirmButton: true,
                    confirmButtonText: 'OK'
                });
                
                // Re-enable button
                btn.disabled = false;
                btn.style.opacity = '1';
                btn.style.cursor = 'pointer';
                btnText.textContent = originalText;
            });
        }
        
        // Function to update individual request buttons after broadcast
        function updateRequestButtons(type) {
            const typeMap = {
                'certificate': 'certificate',
                'evaluation': 'evaluation'
            };
            const targetType = typeMap[type];
            
            // Find all request buttons for this type and update them
            document.querySelectorAll('form[action*="grades.request"]').forEach(form => {
                const typeInput = form.querySelector('input[name="type"]');
                if (typeInput && typeInput.value === targetType) {
                    const button = form.querySelector('button[type="submit"]');
                    if (button && button.classList.contains('btn-request')) {
                        // Replace button with "Requested" status
                        const statusLabel = document.createElement('span');
                        statusLabel.className = 'status-label status-requested';
                        statusLabel.innerHTML = '<i class="fas fa-clock"></i> Requested';
                        form.parentElement.replaceChild(statusLabel, form);
                    }
                }
            });
        }
        
        // Function to handle individual request button clicks
        function handleIndividualRequest(form) {
            const button = form.querySelector('button[type="submit"]');
            if (!button) return;
            
            const originalText = button.innerHTML;
            button.disabled = true;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
            
            // Submit form via AJAX
            const formData = new FormData(form);
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';
            
            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(response => {
                if (response.ok) {
                    // Replace button with "Requested" status
                    const statusLabel = document.createElement('span');
                    statusLabel.className = 'status-label status-requested';
                    statusLabel.innerHTML = '<i class="fas fa-clock"></i> Requested';
                    form.parentElement.replaceChild(statusLabel, form);
                    
                    Swal.fire({
                        icon: 'success',
                        title: '✅ Request Sent!',
                        text: 'Document request sent successfully.',
                        timer: 2000,
                        showConfirmButton: false
                    });
                } else {
                    button.disabled = false;
                    button.innerHTML = originalText;
                    Swal.fire({
                        icon: 'error',
                        title: '❌ Request Failed',
                        text: 'Failed to send request. Please try again.',
                        showConfirmButton: true
                    });
                }
            })
            .catch(error => {
                console.error('Request Error:', error);
                button.disabled = false;
                button.innerHTML = originalText;
                Swal.fire({
                    icon: 'error',
                    title: '❌ Connection Error',
                    text: 'Failed to connect to server. Please try again.',
                    showConfirmButton: true
                });
            });
        }

        // Real-time search functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Ensure broadcast section is always visible
            const broadcastSection = document.getElementById('broadcastSection');
            if (broadcastSection) {
                broadcastSection.style.display = 'block';
                broadcastSection.style.visibility = 'visible';
                broadcastSection.style.opacity = '1';
                
                // Ensure buttons are visible
                const certBtn = document.getElementById('broadcastCertificateBtn');
                const evalBtn = document.getElementById('broadcastEvaluationBtn');
                if (certBtn) {
                    certBtn.style.display = 'inline-flex';
                    certBtn.style.visibility = 'visible';
                    certBtn.style.opacity = '1';
                    certBtn.addEventListener('click', function() {
                        broadcastRequest('certificate');
                    });
                }
                if (evalBtn) {
                    evalBtn.style.display = 'inline-flex';
                    evalBtn.style.visibility = 'visible';
                    evalBtn.style.opacity = '1';
                    evalBtn.addEventListener('click', function() {
                        broadcastRequest('evaluation');
                    });
                }
            }
            
            const searchInput = document.getElementById('searchInput');
            const tableRows = document.querySelectorAll('tbody tr');
            
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase().trim();
                    
                    tableRows.forEach(row => {
                        const nameCell = row.querySelector('td:first-child');
                        const sectionCell = row.querySelector('td:nth-child(2)');
                        
                        if (nameCell && sectionCell) {
                            const name = nameCell.textContent.toLowerCase();
                            const section = sectionCell.textContent.toLowerCase();
                            
                            if (name.includes(searchTerm) || section.includes(searchTerm)) {
                                row.style.display = '';
                            } else {
                                row.style.display = 'none';
                            }
                        }
                    });

                    // Show/hide empty state
                    const visibleRows = Array.from(tableRows).filter(row => row.style.display !== 'none');
                    if (visibleRows.length === 0 && searchTerm) {
                        showEmptyState();
                    } else {
                        hideEmptyState();
                    }
                });
            }

            // Add smooth scroll to top on filter change
            const filterButtons = document.querySelectorAll('.filter-btn');
            filterButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                });
            });

            function showEmptyState() {
                const tableContainer = document.querySelector('.table-container');
                if (tableContainer && !document.querySelector('.search-empty-state')) {
                    const emptyState = document.createElement('div');
                    emptyState.className = 'empty-state search-empty-state';
                    emptyState.innerHTML = `
                        <i class="fas fa-search"></i>
                        <h3>No Results Found</h3>
                        <p>Try adjusting your search terms</p>
                    `;
                    tableContainer.style.display = 'none';
                    tableContainer.parentElement.appendChild(emptyState);
                }
            }

            function hideEmptyState() {
                const emptyState = document.querySelector('.search-empty-state');
                const tableContainer = document.querySelector('.table-container');
                if (emptyState) {
                    emptyState.remove();
                }
                if (tableContainer) {
                    tableContainer.style.display = 'block';
                }
            }
        });
    </script>
@endsection