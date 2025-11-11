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

        .interns-container { max-width: 1400px; margin: 0 auto; background: white; border-radius: 16px; padding: 32px; box-shadow: var(--shadow-lg); }

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

        /* Phase Filter */
        .phase-filter {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .phase-select {
            padding: 10px 16px;
            border: 2px solid var(--border);
            border-radius: 8px;
            background: white;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .phase-select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
        }

        /* Phase Indicator Banner */
        .phase-indicator {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 20px 24px;
            border-radius: 12px;
            margin-bottom: 24px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(37, 99, 235, 0.3);
            animation: slideIn 0.3s ease;
        }

        .phase-indicator h3 {
            margin: 0 0 8px 0;
            font-size: 20px;
            font-weight: 600;
        }

        .phase-indicator p {
            margin: 0 0 16px 0;
            opacity: 0.9;
            font-size: 14px;
        }

        .phase-indicator a {
            display: inline-block;
            color: white;
            text-decoration: none;
            padding: 8px 16px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 20px;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .phase-indicator a:hover {
            background: rgba(255, 255, 255, 0.2);
            border-color: rgba(255, 255, 255, 0.5);
            transform: translateY(-1px);
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

        /* Section Heading */
        .section-heading {
            background: var(--light);
            font-weight: 600;
            text-align: center;
            padding: 12px;
            color: var(--secondary);
            font-size: 14px;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 8px;
            align-items: center;
            justify-content: center;
            flex-wrap: wrap;
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

        .btn-edit {
            background: rgba(100, 116, 139, 0.1);
            color: var(--secondary);
            border: 1px solid var(--secondary);
        }

        .btn-edit:hover {
            background: var(--secondary);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(100, 116, 139, 0.3);
        }

        .btn-accept {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success);
            border: 1px solid var(--success);
        }

        .btn-accept:hover {
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

        .btn-reject {
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning);
            border: 1px solid var(--warning);
        }

        .btn-reject:hover {
            background: var(--warning);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
        }

        .btn-invite {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success);
            border: 1px solid var(--success);
            padding: 12px 24px;
            font-size: 14px;
        }

        .btn-invite:hover {
            background: var(--success);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        /* Status Labels */
        .status-label {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }

        .status-pending {
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning);
        }

        .status-accepted {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success);
        }

        .status-rejected {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger);
        }

        /* Phase Labels */
        .phase-label {
            padding: 4px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }

        .phase-pre-enrollment {
            background: rgba(100, 116, 139, 0.1);
            color: var(--secondary);
        }

        .phase-pre-deployment {
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning);
        }

        .phase-mid-deployment {
            background: rgba(37, 99, 235, 0.1);
            color: var(--primary);
        }

        .phase-deployment {
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
            .interns-container {
                padding: 20px;
            }

            .filter-controls {
                flex-direction: column;
                align-items: stretch;
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
    </style>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="interns-container">
        <!-- Page Header -->
        <div class="page-header">
            <h1>
                <i class="fas fa-users"></i>
                Intern Management
            </h1>
            <p>Manage intern applications, phases, and track progress</p>
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

        <!-- Generate Invitation Link Button -->
        <div style="text-align:center; margin-bottom: 24px;">
            <button type="button" class="btn btn-invite" onclick="generateAndCopyInviteLink()">
                <i class="fas fa-link"></i>
                Generate Invitation Link
            </button>
        </div>

        <!-- Filter Section -->
        <div class="filter-section">
            <div class="filter-controls">
                <!-- Phase Filter -->
                <div class="phase-filter">
                    <form method="GET" action="{{ route('interns') }}" style="display:flex; gap:10px; align-items:center;">
                        <input type="hidden" name="filter" value="{{ request('filter') }}">
                        <select name="phase" onchange="this.form.submit()" class="phase-select">
                            <option value="all" {{ ($phase === 'all' || !$phase) ? 'selected' : '' }}>All Phases</option>
                            <option value="pre_deployment" {{ $phase === 'pre_deployment' ? 'selected' : '' }}>Pre-Deployment</option>
                            <option value="mid_deployment" {{ $phase === 'mid_deployment' ? 'selected' : '' }}>Mid-Deployment</option>
                            <option value="deployment" {{ $phase === 'deployment' ? 'selected' : '' }}>Deployment</option>
                        </select>
                        @if($phase && $phase !== 'all')
                            <a href="{{ route('interns', ['filter' => request('filter')]) }}" class="btn btn-edit">
                                <i class="fas fa-times"></i>
                                Reset Phase
                            </a>
                        @endif
                    </form>
                </div>

                <!-- Section Filter Buttons -->
                @foreach(['North', 'East', 'West', 'South', 'Northeast'] as $section)
                    <form method="GET" action="{{ route('interns') }}" style="display: inline;">
                        <input type="hidden" name="filter" value="{{ $section }}">
                        @if($phase && $phase !== 'all')
                            <input type="hidden" name="phase" value="{{ $phase }}">
                        @endif
                        <button type="submit" class="filter-btn {{ request('filter') === $section ? 'active' : '' }}">
                            <i class="fas fa-users"></i>
                            {{ $section }}
                            @if(isset($sectionCounts[$section]) && $sectionCounts[$section] > 0)
                                <span class="badge">{{ $sectionCounts[$section] }}</span>
                            @endif
                        </button>
                    </form>
                @endforeach
            </div>
        </div>

        <!-- Phase Indicator Banner -->
        @if($phase && $phase !== 'all')
            <div class="phase-indicator">
                <h3>
                    <i class="fas fa-clipboard-list"></i>
                    Currently Viewing: {{ ucfirst(str_replace('_', ' ', $phase)) }} Phase
                </h3>
                <p>
                    Showing interns in the {{ ucfirst(str_replace('_', ' ', $phase)) }} phase
                    @if(isset($phaseCounts[$phase]))
                        • {{ $phaseCounts[$phase] }} intern(s) found
                    @endif
                </p>
                <a href="{{ route('interns', ['filter' => request('filter')]) }}">
                    <i class="fas fa-eye"></i>
                    View All Phases
                </a>
            </div>
        @endif

        <!-- Table -->
        @if($interns->count() > 0)
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th><i class="fas fa-user"></i> First Name</th>
                            <th><i class="fas fa-user"></i> Last Name</th>
                            <th><i class="fas fa-graduation-cap"></i> Course</th>
                            <th><i class="fas fa-users"></i> Section</th>
                            <th><i class="fas fa-tasks"></i> Current Phase</th>
                            <th><i class="fas fa-info-circle"></i> Phase Status</th>
                            <th><i class="fas fa-cog"></i> Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $currentSection = null; @endphp
                        @foreach($interns as $intern)
                            @if($currentSection !== $intern->section)
                                @php $currentSection = $intern->section; @endphp
                                <tr>
                                    <td colspan="7" class="section-heading">
                                        <i class="fas fa-map-marker-alt"></i>
                                        Section: {{ $currentSection }}
                                    </td>
                                </tr>
                            @endif
                            <tr>
                                <td><strong>{{ $intern->first_name }}</strong></td>
                                <td><strong>{{ $intern->last_name }}</strong></td>
                                <td>{{ $intern->course }}</td>
                                <td>{{ $intern->section }}</td>
                                <td>
                                    @switch($intern->current_phase)
                                        @case('pre_enrollment')
                                            <span class="phase-label phase-pre-enrollment">Pre-Enrollment</span>
                                            @break
                                        @case('pre_deployment')
                                            <span class="phase-label phase-pre-deployment">Pre-Deployment</span>
                                            @break
                                        @case('mid_deployment')
                                            <span class="phase-label phase-mid-deployment">Mid-Deployment</span>
                                            @break
                                        @case('deployment')
                                            <span class="phase-label phase-deployment">Deployment</span>
                                            @break
                                        @default
                                            <span class="phase-label phase-pre-enrollment">{{ ucfirst($intern->current_phase) }}</span>
                                    @endswitch
                                </td>
                                <td>
                                    @if($intern->status === 'pending')
                                        <span class="status-label status-pending">Pending</span>
                                    @elseif($intern->status === 'accepted')
                                        <span class="status-label status-accepted">Accepted</span>
                                    @elseif($intern->status === 'rejected')
                                        <span class="status-label status-rejected">Rejected</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <button type="button" class="btn btn-view" onclick="showInternFiles({{ $intern->id }})">
                                            <i class="fas fa-eye"></i>
                                            View
                                        </button>
                                        <button type="button" class="btn btn-edit" onclick="showEditIntern({{ $intern->id }})">
                                            <i class="fas fa-edit"></i>
                                            Edit
                                        </button>
                                        
                                        @if($intern->status === 'pending')
                                            <button type="button" class="btn btn-accept" onclick="confirmAccept({{ $intern->id }}, '{{ $intern->first_name }} {{ $intern->last_name }}')">
                                                <i class="fas fa-check"></i>
                                                Accept
                                            </button>
                                            <button type="button" class="btn btn-reject" onclick="confirmReject({{ $intern->id }}, '{{ $intern->first_name }} {{ $intern->last_name }}')">
                                                <i class="fas fa-times"></i>
                                                Reject
                                            </button>
                                        @endif

                                        {{-- Pre-Deployment Phase Buttons - Only show when intern is accepted and in pre_deployment phase with pending/null status --}}
                                        @if($intern->status === 'accepted' && $intern->current_phase === 'pre_deployment' && ($intern->pre_deployment_status === 'pending' || $intern->pre_deployment_status === null))
                                            <button type="button" class="btn btn-accept" onclick="confirmPhaseAccept({{ $intern->id }}, '{{ $intern->first_name }} {{ $intern->last_name }}', 'pre-deployment')">
                                                <i class="fas fa-check"></i>
                                                Accept Pre-Deployment
                                            </button>
                                            <button type="button" class="btn btn-reject" onclick="confirmPhaseReject({{ $intern->id }}, '{{ $intern->first_name }} {{ $intern->last_name }}', 'pre-deployment')">
                                                <i class="fas fa-times"></i>
                                                Reject Pre-Deployment
                                            </button>
                                        @endif

                                        {{-- Mid-Deployment Phase Buttons - Only show when intern is accepted and in mid_deployment phase with pending/null status --}}
                                        @if($intern->status === 'accepted' && $intern->current_phase === 'mid_deployment' && ($intern->mid_deployment_status === 'pending' || $intern->mid_deployment_status === null))
                                            <button type="button" class="btn btn-accept" onclick="confirmPhaseAccept({{ $intern->id }}, '{{ $intern->first_name }} {{ $intern->last_name }}', 'mid-deployment')">
                                                <i class="fas fa-check"></i>
                                                Accept Mid-Deployment
                                            </button>
                                            <button type="button" class="btn btn-reject" onclick="confirmPhaseReject({{ $intern->id }}, '{{ $intern->first_name }} {{ $intern->last_name }}', 'mid-deployment')">
                                                <i class="fas fa-times"></i>
                                                Reject Mid-Deployment
                                            </button>
                                        @endif

                                        {{-- Deployment Phase Buttons - Only show when intern is accepted and in deployment phase with pending/null status --}}
                                        @if($intern->status === 'accepted' && $intern->current_phase === 'deployment' && ($intern->deployment_status === 'pending' || $intern->deployment_status === null))
                                            <button type="button" class="btn btn-accept" onclick="confirmPhaseAccept({{ $intern->id }}, '{{ $intern->first_name }} {{ $intern->last_name }}', 'deployment')">
                                                <i class="fas fa-check"></i>
                                                Accept Deployment
                                            </button>
                                            <button type="button" class="btn btn-reject" onclick="confirmPhaseReject({{ $intern->id }}, '{{ $intern->first_name }} {{ $intern->last_name }}', 'deployment')">
                                                <i class="fas fa-times"></i>
                                                Reject Deployment
                                            </button>
                                        @endif

                                        <button type="button" class="btn btn-delete" onclick="confirmDelete({{ $intern->id }}, '{{ $intern->first_name }} {{ $intern->last_name }}')">
                                            <i class="fas fa-trash"></i>
                                            Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-user-slash"></i>
                <h3>No Interns Found</h3>
                <p>No interns match the current filter criteria</p>
            </div>
        @endif
    </div>

    <!-- View Intern Files Modal -->
    <div id="viewInternModal" class="modal" style="display: none;">
        <div class="modal-content" style="max-width: 800px;">
            <span class="close" onclick="closeViewInternModal()">&times;</span>
            <h2 style="margin-bottom: 20px; color: #1e293b;">
                <i class="fas fa-folder-open"></i> Documents
            </h2>
            <div id="internDetailsContent"></div>
            <div id="docNav" style="display:none; margin: 10px 0; display:flex; align-items:center; gap:8px;">
                <button type="button" id="docPrevBtn" class="btn btn-edit" style="padding:6px 12px;">
                    <i class="fas fa-arrow-left"></i> Back
                </button>
                <span id="docCounter" style="font-size:12px; color:#64748b;"></span>
                <button type="button" id="docNextBtn" class="btn btn-edit" style="padding:6px 12px;">
                    Next <i class="fas fa-arrow-right"></i>
                </button>
            </div>
            <div style="margin-top: 8px; background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px;">
                <iframe id="docPreviewFrame" title="Document Preview" style="width:100%; height:520px; border:0;"></iframe>
            </div>
        </div>
    </div>

    <!-- Edit Intern Modal -->
    <div id="editInternModal" class="modal" style="display: none;">
        <div class="modal-content" style="max-width: 600px;">
            <span class="close" onclick="closeEditInternModal()">&times;</span>
            <h2 style="margin-bottom: 20px; color: #1e293b;">
                <i class="fas fa-edit"></i> Edit Intern
            </h2>
            <div id="editInternDetails" class="detail-item" style="margin-bottom: 16px; display:none;">
                <label>Intern Details</label>
                <div class="value" id="editInternDetailsContent"></div>
            </div>
            <form id="editInternForm" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group" style="margin-bottom: 16px;">
                    <label style="display: block; margin-bottom: 6px; font-weight: 600; color: #1e293b;">First Name</label>
                    <input type="text" name="first_name" id="edit_first_name" required 
                           style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 8px;">
                </div>
                <div class="form-group" style="margin-bottom: 16px;">
                    <label style="display: block; margin-bottom: 6px; font-weight: 600; color: #1e293b;">Last Name</label>
                    <input type="text" name="last_name" id="edit_last_name" required 
                           style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 8px;">
                </div>
                <div class="form-group" style="margin-bottom: 16px;">
                    <label style="display: block; margin-bottom: 6px; font-weight: 600; color: #1e293b;">Course</label>
                    <input type="text" name="course" id="edit_course" required 
                           style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 8px;">
                </div>
                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 6px; font-weight: 600; color: #1e293b;">Section</label>
                    <select name="section" id="edit_section" required 
                            style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 8px;">
                        <option value="">Select Section</option>
                        <option value="North">North</option>
                        <option value="South">South</option>
                        <option value="West">West</option>
                        <option value="East">East</option>
                        <option value="Northeast">Northeast</option>
                    </select>
                </div>
                <div style="text-align: right; margin-top: 20px;">
                    <button type="button" class="btn btn-edit" onclick="closeEditInternModal()" style="margin-right: 10px; background: #64748b;">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-edit" style="background: #2563eb;">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            overflow-y: auto;
        }

        .modal-content {
            background: white;
            margin: 5% auto;
            padding: 30px;
            border-radius: 16px;
            position: relative;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            position: absolute;
            top: 15px;
            right: 20px;
        }

        .close:hover {
            color: #000;
        }

        .detail-item {
            margin-bottom: 16px;
            padding: 12px;
            background: #f1f5f9;
            border-radius: 8px;
        }

        .detail-item label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }

        .detail-item .value {
            font-size: 16px;
            color: #1e293b;
            font-weight: 500;
        }
    </style>

    <script>
        // Generate and copy invitation link to clipboard
        async function generateAndCopyInviteLink() {
            try {
                const button = event.target;
                const originalText = button.innerHTML;
                button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Generating...';
                button.disabled = true;

                const response = await fetch('{{ route('interns.invite-link') }}', {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    }
                });

                if (!response.ok) {
                    throw new Error('Failed to generate invitation link');
                }

                const data = await response.json();
                const inviteLink = data.link;

                await navigator.clipboard.writeText(inviteLink);

                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Invitation link generated and copied to clipboard! You can now paste it anywhere.',
                    confirmButtonColor: '#10b981',
                    timer: 3000,
                    timerProgressBar: true,
                });

                button.innerHTML = originalText;
                button.disabled = false;

            } catch (error) {
                console.error('Error generating invitation link:', error);
                
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Failed to generate invitation link. Please try again.',
                    confirmButtonColor: '#ef4444',
                });
                
                const button = event.target;
                button.innerHTML = '<i class="fas fa-link"></i> Generate Invitation Link';
                button.disabled = false;
            }
        }

        // Accept intern confirmation
        function confirmAccept(internId, internName) {
            Swal.fire({
                title: 'Accept Intern?',
                text: `Are you sure you want to accept ${internName}? This will allow them to log in and start their internship.`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, accept!',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]');
                    if (!csrfToken) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'CSRF token not found. Please refresh the page and try again.',
                            confirmButtonColor: '#ef4444',
                        });
                        return;
                    }

                    const form = document.createElement('form');
                    form.method = 'POST';
                    const baseUrl = '{{ url("/") }}';
                    form.action = `${baseUrl}/interns/${internId}/accept`;
                    form.style.display = 'none';
                    
                    const tokenInput = document.createElement('input');
                    tokenInput.type = 'hidden';
                    tokenInput.name = '_token';
                    tokenInput.value = csrfToken.getAttribute('content');
                    
                    form.appendChild(tokenInput);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        // Reject intern confirmation
        function confirmReject(internId, internName) {
            Swal.fire({
                title: 'Reject Intern?',
                text: `Are you sure you want to reject ${internName}? This will remove them from the intern list.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#f59e0b',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, reject!',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `/interns/${internId}/reject`;
                }
            });
        }

        // Phase accept confirmation
        function confirmPhaseAccept(internId, internName, phase) {
            Swal.fire({
                title: `Accept ${phase.charAt(0).toUpperCase() + phase.slice(1)} Phase?`,
                text: `Are you sure you want to accept the ${phase} phase for ${internName}?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, accept!',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    let actionUrl = '';
                    if (phase === 'pre-deployment') {
                        actionUrl = `/interns/${internId}/accept-pre-deployment`;
                    } else if (phase === 'mid-deployment') {
                        actionUrl = `/interns/${internId}/accept-mid-deployment`;
                    } else if (phase === 'deployment') {
                        actionUrl = `/interns/${internId}/accept-deployment`;
                    }
                    
                    if (actionUrl) {
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = actionUrl;
                        
                        const csrfToken = document.createElement('input');
                        csrfToken.type = 'hidden';
                        csrfToken.name = '_token';
                        csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                        
                        form.appendChild(csrfToken);
                        document.body.appendChild(form);
                        form.submit();
                    }
                }
            });
        }

        // Phase reject confirmation
        function confirmPhaseReject(internId, internName, phase) {
            Swal.fire({
                title: `Reject ${phase.charAt(0).toUpperCase() + phase.slice(1)} Phase?`,
                text: `Are you sure you want to reject the ${phase} phase for ${internName}? They will need to resubmit their documents.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#f59e0b',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, reject!',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    let actionUrl = '';
                    if (phase === 'pre-deployment') {
                        actionUrl = `/interns/${internId}/reject-pre-deployment`;
                    } else if (phase === 'mid-deployment') {
                        actionUrl = `/interns/${internId}/reject-mid-deployment`;
                    } else if (phase === 'deployment') {
                        actionUrl = `/interns/${internId}/reject-deployment`;
                    }
                    
                    if (actionUrl) {
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = actionUrl;
                        
                        const csrfToken = document.createElement('input');
                        csrfToken.type = 'hidden';
                        csrfToken.name = '_token';
                        csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                        
                        form.appendChild(csrfToken);
                        document.body.appendChild(form);
                        form.submit();
                    }
                }
            });
        }

        // Delete intern confirmation
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
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/interns/${internId}`;
                    
                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    
                    const methodField = document.createElement('input');
                    methodField.type = 'hidden';
                    methodField.name = '_method';
                    methodField.value = 'DELETE';
                    
                    form.appendChild(csrfToken);
                    form.appendChild(methodField);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        // Show session messages with SweetAlert
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

        // Show intern documents-only modal
        async function showInternFiles(internId) {
            try {
                const response = await fetch(`{{ url('/interns') }}/${internId}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!response.ok) {
                    throw new Error('Failed to load intern');
                }

                const intern = await response.json();
                const content = document.getElementById('internDetailsContent');
                const frame = document.getElementById('docPreviewFrame');
                const nav = document.getElementById('docNav');
                const prevBtn = document.getElementById('docPrevBtn');
                const nextBtn = document.getElementById('docNextBtn');
                const counter = document.getElementById('docCounter');

                // Helper to build viewer src for different file types
                function buildViewerSrc(path) {
                    if (!path) return null;
                    const filePath = String(path);
                    const lower = filePath.toLowerCase();
                    const origin = window.location.origin;
                    if (lower.endsWith('.pdf') || lower.endsWith('.html') || lower.endsWith('.htm')) {
                        // Use backend streaming to force inline view for PDF/HTML
                        const fname = filePath.split('/').pop();
                        return `{{ route('documents.view', ':fname') }}`.replace(':fname', encodeURIComponent(fname));
                    }
                    if (lower.endsWith('.doc') || lower.endsWith('.docx')) {
                        // Try Office web viewer
                        const publicUrl = origin + (filePath.startsWith('documents') ? `/storage/${filePath}` : filePath);
                        return `https://view.officeapps.live.com/op/embed.aspx?src=${encodeURIComponent(publicUrl)}`;
                    }
                    // Fallback to public storage direct
                    return origin + (filePath.startsWith('documents') ? `/storage/${filePath}` : filePath);
                }

                function docButton(label, path) {
                    if (!path) return '';
                    const src = buildViewerSrc(path);
                    const safeLabel = label.replace(/</g, '&lt;');
                    const downloadUrl = (path && path.startsWith('documents')) ? (`/storage/${path}`) : path;
                    return `
                        <button type="button" class="btn btn-view" onclick="(function(){ 
                            const frame = document.getElementById('docPreviewFrame'); 
                            if (frame) { frame.src = '${src}'; } 
                        })()">
                            <i class="fas fa-eye"></i> ${safeLabel}
                        </button>
                        <a class="btn btn-edit" href="${downloadUrl}" target="_blank">
                            <i class="fas fa-external-link-alt"></i> Open
                        </a>
                    `;
                }

                // Phase gating: current phase docs visible; previous phases only if accepted
                const currentPhase = intern.current_phase;
                const preAccepted = intern.pre_deployment_status === 'accepted';
                const midAccepted = intern.mid_deployment_status === 'accepted';
                const depAccepted = intern.deployment_status === 'accepted';

                function canShowPre() {
                    return currentPhase === 'pre_deployment' || preAccepted;
                }
                function canShowMid() {
                    return currentPhase === 'mid_deployment' || midAccepted;
                }
                function canShowDep() {
                    return currentPhase === 'deployment' || depAccepted;
                }

                // Build document controls (only allowed phases)
                const docs = [];
                const docEntries = []; // [{label, src}]
                if (canShowPre()) {
                    if (intern.resume) { docs.push(docButton('Resume', intern.resume)); docEntries.push({label:'Resume', src: buildViewerSrc(intern.resume)}); }
                    if (intern.application_letter) { docs.push(docButton('Application Letter', intern.application_letter)); docEntries.push({label:'Application Letter', src: buildViewerSrc(intern.application_letter)}); }
                    if (intern.medical_certificate) { docs.push(docButton('Medical Certificate', intern.medical_certificate)); docEntries.push({label:'Medical Certificate', src: buildViewerSrc(intern.medical_certificate)}); }
                    if (intern.insurance) { docs.push(docButton('Insurance', intern.insurance)); docEntries.push({label:'Insurance', src: buildViewerSrc(intern.insurance)}); }
                    if (intern.parents_waiver) { docs.push(docButton("Parent's Waiver", intern.parents_waiver)); docEntries.push({label:"Parent's Waiver", src: buildViewerSrc(intern.parents_waiver)}); }
                    if (intern.acceptance_letter) { docs.push(docButton('Acceptance Letter (Auto)', intern.acceptance_letter)); docEntries.push({label:'Acceptance Letter (Auto)', src: buildViewerSrc(intern.acceptance_letter)}); }
                }
                if (canShowMid()) {
                    if (intern.memorandum_of_agreement) { docs.push(docButton('Memorandum (Auto)', intern.memorandum_of_agreement)); docEntries.push({label:'Memorandum (Auto)', src: buildViewerSrc(intern.memorandum_of_agreement)}); }
                    if (intern.internship_contract) { docs.push(docButton('Internship Contract (Auto)', intern.internship_contract)); docEntries.push({label:'Internship Contract (Auto)', src: buildViewerSrc(intern.internship_contract)}); }
                }
                
                // Endorsement (Auto) via route for this intern
                const endorsementUrl = `{{ route('documents.endorsement', ':id') }}`.replace(':id', intern.id);
                let endorsementBtn = '';
                if (canShowDep()) {
                    endorsementBtn = `
                        <button type="button" class="btn btn-view" onclick="(function(){ 
                            const frame = document.getElementById('docPreviewFrame'); 
                            if (frame) { frame.src = '${endorsementUrl}'; } 
                        })()">
                            <i class="fas fa-eye"></i> Endorsement (Auto)
                        </button>
                        <a class="btn btn-edit" href="${endorsementUrl}" target="_blank">
                            <i class="fas fa-external-link-alt"></i> Open
                        </a>
                    `;
                    docEntries.push({label:'Endorsement (Auto)', src: endorsementUrl});
                }

                // Build the modal body with only docs + viewer
                // Determine default preview: first available entry in allowed list
                let defaultSrc = (docEntries.length > 0) ? docEntries[0].src : null;

                content.innerHTML = `
                    <div style="margin-top: 16px;">
                        <h3 style="font-size:16px; margin: 10px 0; color:#1e293b;"><i class="fas fa-folder-open"></i> Documents</h3>
                        <div class="action-buttons" style="justify-content:flex-start; flex-wrap:wrap; gap:8px;">
                            ${docs.join('')}
                            ${endorsementBtn}
                        </div>
                    </div>
                `;

                // Initialize preview and navigation
                let currentIndex = 0;
                function refreshNav() {
                    if (docEntries.length <= 1) {
                        nav.style.display = docEntries.length === 1 ? 'flex' : 'none';
                    } else {
                        nav.style.display = 'flex';
                    }
                    prevBtn.disabled = currentIndex <= 0;
                    nextBtn.disabled = currentIndex >= (docEntries.length - 1);
                    counter.textContent = docEntries.length ? `${currentIndex + 1} / ${docEntries.length} — ${docEntries[currentIndex].label}` : 'No documents available';
                }
                prevBtn.onclick = function() {
                    if (currentIndex > 0) {
                        currentIndex -= 1;
                        frame.src = docEntries[currentIndex].src;
                        refreshNav();
                    }
                };
                nextBtn.onclick = function() {
                    if (currentIndex < docEntries.length - 1) {
                        currentIndex += 1;
                        frame.src = docEntries[currentIndex].src;
                        refreshNav();
                    }
                };
                if (defaultSrc) {
                    frame.src = defaultSrc;
                } else {
                    frame.removeAttribute('src');
                }
                refreshNav();

                document.getElementById('viewInternModal').style.display = 'block';
            } catch (error) {
                console.error('Error loading intern files:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to load documents. Please try again.',
                    confirmButtonColor: '#ef4444',
                });
            }
        }

        // Close view intern modal
        function closeViewInternModal() {
            document.getElementById('viewInternModal').style.display = 'none';
        }

        // Show edit intern modal
        async function showEditIntern(internId) {
            try {
                const response = await fetch(`{{ url('/interns') }}/${internId}/edit`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!response.ok) {
                    throw new Error('Failed to load intern data');
                }

                const intern = await response.json();
                
                document.getElementById('edit_first_name').value = intern.first_name || '';
                document.getElementById('edit_last_name').value = intern.last_name || '';
                document.getElementById('edit_course').value = intern.course || '';
                document.getElementById('edit_section').value = intern.section || '';
                
                const form = document.getElementById('editInternForm');
                form.action = `{{ url('/interns') }}/${internId}`;

                // Populate read-only details in the edit modal
                const detailsContainer = document.getElementById('editInternDetails');
                const detailsContent = document.getElementById('editInternDetailsContent');
                detailsContent.innerHTML = `
                    <div style="display:grid; grid-template-columns: repeat(2, minmax(0,1fr)); gap: 8px;">
                        <div><strong>Email:</strong> ${intern.email || 'N/A'}</div>
                        <div><strong>Phone:</strong> ${intern.phone || 'N/A'}</div>
                        <div><strong>Status:</strong> ${intern.status || 'N/A'}</div>
                        <div><strong>Current Phase:</strong> ${intern.current_phase ? intern.current_phase.replace(/_/g,' ') : 'N/A'}</div>
                        <div><strong>Company:</strong> ${intern.company_name || 'N/A'}</div>
                        <div><strong>Supervisor:</strong> ${intern.supervisor_name || 'N/A'}</div>
                    </div>
                `;
                detailsContainer.style.display = 'block';
                
                document.getElementById('editInternModal').style.display = 'block';
            } catch (error) {
                console.error('Error loading intern data:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to load intern data. Please try again.',
                    confirmButtonColor: '#ef4444',
                });
            }
        }

        // Close edit intern modal
        function closeEditInternModal() {
            document.getElementById('editInternModal').style.display = 'none';
        }

        // Handle edit form submission
        document.getElementById('editInternForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const internId = this.action.split('/').pop();
            
            try {
                const response = await fetch(this.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const data = await response.json();

                if (response.ok) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Intern updated successfully.',
                        confirmButtonColor: '#10b981',
                        timer: 2000,
                        timerProgressBar: true,
                    }).then(() => {
                        closeEditInternModal();
                        window.location.reload();
                    });
                } else {
                    throw new Error(data.message || 'Failed to update intern');
                }
            } catch (error) {
                console.error('Error updating intern:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.message || 'Failed to update intern. Please try again.',
                    confirmButtonColor: '#ef4444',
                });
            }
        });

        // Close modals when clicking outside
        window.onclick = function(event) {
            const viewModal = document.getElementById('viewInternModal');
            const editModal = document.getElementById('editInternModal');
            if (event.target === viewModal) {
                closeViewInternModal();
            }
            if (event.target === editModal) {
                closeEditInternModal();
            }
        }
    </script>
@endsection