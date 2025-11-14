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

        .documents-container { max-width: 1400px; margin: 0 auto; background: white; border-radius: 16px; padding: 32px; box-shadow: var(--shadow-lg); }

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

        .btn-dtr {
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning);
            border: 1px solid var(--warning);
        }

        .btn-dtr:hover {
            background: var(--warning);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
        }

        .btn-journal {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success);
            border: 1px solid var(--success);
        }

        .btn-journal:hover {
            background: var(--success);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        .btn-archive {
            background: rgba(107, 114, 128, 0.1);
            color: var(--secondary);
            border: 1px solid var(--secondary);
        }

        .btn-archive:hover {
            background: var(--secondary);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(107, 114, 128, 0.3);
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

        /* Progress Bar */
        .progress-bar-container {
            width: 100%;
            background-color: var(--border);
            border-radius: 10px;
            overflow: hidden;
            height: 20px;
            position: relative;
        }

        .progress-bar {
            height: 100%;
            background: linear-gradient(90deg, var(--success) 0%, #059669 100%);
            color: white;
            text-align: center;
            line-height: 20px;
            font-size: 13px;
            font-weight: bold;
            white-space: nowrap;
            transition: width 0.4s ease;
        }

        .hours-text {
            font-size: 12px;
            color: var(--secondary);
            margin-top: 4px;
            display: block;
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

        /* Company Badge */
        .company-badge {
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
            .documents-container {
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
    </style>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="documents-container">
        <!-- Page Header -->
        <div class="page-header">
            <h1>
                <i class="fas fa-file-alt"></i>
                Document Management
            </h1>
            <p>View, manage, and track intern documents, attendance, and progress</p>
        </div>

        <!-- Alert Messages -->
        @if(session('success'))
            <div class="alert success">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Filter Section -->
        <div class="filter-section">
            <div class="filter-controls">
                {{-- Show All Button --}}
                <form method="GET" action="{{ route('documents') }}" style="display: inline;">
                    <input type="hidden" name="filter" value="all">
                    <button type="submit" class="filter-btn {{ !request('filter') || request('filter') === 'all' ? 'active' : '' }}">
                        <i class="fas fa-th-large"></i>
                        Show All
                        @if(array_sum($sectionCounts))
                            <span class="badge">{{ array_sum($sectionCounts) }}</span>
                        @endif
                    </button>
                </form>

                {{-- Section Buttons --}}
                @foreach(array_keys($sectionCounts) as $section)
                    <form method="GET" action="{{ route('documents') }}" style="display: inline;">
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

                {{-- Archive Button --}}
                <a href="{{ route('documents.archive') }}" class="btn btn-archive" style="margin-left: auto;">
                    <i class="fas fa-archive"></i>
                    Archive
                </a>

                {{-- Search Bar --}}
                <div class="search-container">
                    <form class="search-wrapper" action="{{ route('documents') }}" method="GET">
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
        @if($interns->count() > 0)
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th><i class="fas fa-user"></i> Full Name</th>
                            <th><i class="fas fa-layer-group"></i> Section</th>
                            <th><i class="fas fa-building"></i> Company</th>
                            <th><i class="fas fa-calendar-alt"></i> Attendance</th>
                            <th><i class="fas fa-tasks"></i> Journal</th>
                            <th><i class="fas fa-chart-line"></i> Progress</th>
                            <th><i class="fas fa-cog"></i> Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($interns as $intern)
                            @php
                                $totalHours = $intern->timeLogs->sum(function($log) {
                                    if ($log->time_in && $log->time_out) {
                                        $in = \Carbon\Carbon::parse($log->date . ' ' . $log->time_in, 'Asia/Manila');
                                        $out = \Carbon\Carbon::parse($log->date . ' ' . $log->time_out, 'Asia/Manila');
                                        return round($in->floatDiffInRealHours($out), 2);
                                    }
                                    return 0;
                                });
                                $progressPercent = min(round(($totalHours / 486) * 100, 2), 100);
                            @endphp
                            <tr>
                                <td>
                                    <strong>{{ $intern->first_name }} {{ $intern->last_name }}</strong>
                                </td>
                                <td>
                                    <span class="section-badge">{{ $intern->section ?? 'N/A' }}</span>
                                </td>
                                <td>
                                    <span class="company-badge">{{ $intern->company_name ?? 'N/A' }}</span>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-dtr" onclick="openDTRModal({{ $intern->id }}, '{{ $intern->first_name }} {{ $intern->last_name }}')">
                                        <i class="fas fa-clock"></i>
                                        View DTR
                                    </button>
                                    <div style="margin-top:8px; font-size:12px; color:var(--secondary);">
                                        @php
                                            $grouped = $intern->timeLogs->groupBy(function($log){
                                                return \Carbon\Carbon::parse($log->date, 'Asia/Manila')->format('F Y');
                                            });
                                        @endphp
                                        @foreach($grouped as $monthYear => $logs)
                                            <div>{{ $monthYear }} ({{ $logs->count() }} days)</div>
                                        @endforeach
                                    </div>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-journal" onclick="openJournalModal({{ $intern->id }}, '{{ $intern->first_name }}', '{{ $intern->last_name }}')">
                                        <i class="fas fa-book"></i>
                                        Journal
                                    </button>
                                </td>
                                <td>
                                    <div class="progress-bar-container">
                                        <div class="progress-bar" style="width: {{ $progressPercent }}%;">
                                            {{ $progressPercent }}%
                                        </div>
                                    </div>
                                    <span class="hours-text">{{ $totalHours }} / 486 hours</span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <button type="button" class="btn btn-archive" onclick="openArchiveModal({{ $intern->id }}, '{{ $intern->first_name }} {{ $intern->last_name }}', '{{ $intern->application_letter ? route('documents.view', ['filename' => basename($intern->application_letter)]) : '' }}', '{{ $intern->parents_waiver ? route('documents.view', ['filename' => basename($intern->parents_waiver)]) : '' }}', '{{ $intern->acceptance_letter ? route('documents.view', ['filename' => basename($intern->acceptance_letter)]) : '' }}')">
                                            <i class="fas fa-archive"></i>
                                            Archive
                                        </button>
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

    <!-- DTR Modal -->
    <div id="dtrModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h2>
                    <i class="fas fa-clock"></i>
                    Daily Time Record
                </h2>
                <span class="modal-close" onclick="closeDTRModal()">&times;</span>
            </div>
            <div class="modal-body">
                <div class="dtr-filters">
                    <div class="filter-group">
                        <label for="monthFilter">
                            <i class="fas fa-calendar-alt"></i>
                            Month:
                        </label>
                        <select id="monthFilter" onchange="updateWeekFilter(); loadDTRData();">
                            <option value="">All Months</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label for="weekFilter">
                            <i class="fas fa-calendar-week"></i>
                            Week:
                        </label>
                        <select id="weekFilter" onchange="loadDTRData();">
                            <option value="">All Weeks</option>
                        </select>
                    </div>
                    <button type="button" class="btn-filter-reset" onclick="resetFilters();">
                        <i class="fas fa-redo"></i>
                        Reset
                    </button>
                </div>
                <div id="dtrLoading" class="dtr-loading" style="display: none;">
                    <i class="fas fa-spinner fa-spin"></i>
                    Loading...
                </div>
                <div id="dtrContent">
                    <div class="dtr-summary">
                        <div class="summary-item">
                            <span class="summary-label">Target Hours:</span>
                            <span class="summary-value" id="targetHours">0</span>
                        </div>
                        <div class="summary-item">
                            <span class="summary-label">Total Hours:</span>
                            <span class="summary-value" id="totalHours">0</span>
                        </div>
                        <div class="summary-item">
                            <span class="summary-label">Remaining Hours:</span>
                            <span class="summary-value" id="remainingHours">0</span>
                        </div>
                        <div class="summary-item">
                            <span class="summary-label">Total Days:</span>
                            <span class="summary-value" id="totalDays">0</span>
                        </div>
                    </div>
                    <div class="dtr-table-container">
                        <table class="dtr-table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Time In</th>
                                    <th>Time Out</th>
                                    <th>Total Hours</th>
                                </tr>
                            </thead>
                            <tbody id="dtrTableBody">
                                <tr>
                                    <td colspan="4" class="text-center">Select filters to view DTR data</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Journal Modal -->
    <div id="journalModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h2>
                    <i class="fas fa-book"></i>
                    Journal Entries
                </h2>
                <span class="modal-close" onclick="closeJournalModal()">&times;</span>
            </div>
            <div class="modal-body">
                <div id="journalLoading" class="journal-loading" style="display: none;">
                    <i class="fas fa-spinner fa-spin"></i>
                    Loading...
                </div>
                <div id="journalContent">
                    <div class="journal-info">
                        <div class="info-item">
                            <span class="info-label">Intern:</span>
                            <span class="info-value" id="journalInternName">-</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Company:</span>
                            <span class="info-value" id="journalCompanyName">-</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Total Entries:</span>
                            <span class="info-value" id="journalTotalCount">0</span>
                        </div>
                    </div>
                    <div class="journal-list-container">
                        <div id="journalList" class="journal-list">
                            <div class="journal-empty">
                                <i class="fas fa-book-open"></i>
                                <p>Select an intern to view journal entries</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Modal Styles */
        .modal {
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

        .modal-content {
            background-color: white;
            margin: 2% auto;
            padding: 0;
            border-radius: 16px;
            width: 90%;
            max-width: 1200px;
            max-height: 90vh;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: slideDown 0.3s ease;
            display: flex;
            flex-direction: column;
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

        .modal-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 20px 30px;
            border-radius: 16px 16px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header h2 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .modal-close {
            color: white;
            font-size: 32px;
            font-weight: bold;
            cursor: pointer;
            transition: transform 0.2s;
            line-height: 1;
        }

        .modal-close:hover {
            transform: scale(1.2);
        }

        .modal-body {
            padding: 30px;
            overflow-y: auto;
            flex: 1;
        }

        /* DTR Filters */
        .dtr-filters {
            display: flex;
            gap: 20px;
            align-items: flex-end;
            margin-bottom: 24px;
            padding: 20px;
            background: var(--light);
            border-radius: 12px;
            flex-wrap: wrap;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
            flex: 1;
            min-width: 200px;
        }

        .filter-group label {
            font-weight: 600;
            color: var(--dark);
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .filter-group select {
            padding: 12px 16px;
            border: 2px solid var(--border);
            border-radius: 8px;
            font-size: 14px;
            background: white;
            color: var(--dark);
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: inherit;
        }

        .filter-group select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
        }

        .btn-filter-reset {
            padding: 12px 24px;
            background: var(--secondary);
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
            white-space: nowrap;
        }

        .btn-filter-reset:hover {
            background: #475569;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(100, 116, 139, 0.3);
        }

        /* DTR Summary */
        .dtr-summary {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }

        .summary-item {
            background: var(--light);
            padding: 16px 20px;
            border-radius: 10px;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .summary-label {
            font-size: 12px;
            font-weight: 600;
            color: var(--secondary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .summary-value {
            font-size: 24px;
            font-weight: 700;
            color: var(--primary);
        }

        /* DTR Table */
        .dtr-table-container {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: var(--shadow);
            max-height: 400px;
            overflow-y: auto;
        }

        .dtr-table {
            width: 100%;
            border-collapse: collapse;
        }

        .dtr-table thead {
            position: sticky;
            top: 0;
            z-index: 10;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        }

        .dtr-table th {
            padding: 16px 20px;
            text-align: left;
            font-weight: 600;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: white;
        }

        .dtr-table td {
            padding: 14px 20px;
            color: var(--dark);
            font-size: 14px;
            border-bottom: 1px solid var(--border);
        }

        .dtr-table tbody tr:hover {
            background: var(--light);
        }

        .dtr-table tbody tr:last-child td {
            border-bottom: none;
        }

        .text-center {
            text-align: center;
            color: var(--secondary);
            padding: 40px !important;
        }

        .dtr-loading {
            text-align: center;
            padding: 40px;
            color: var(--primary);
            font-size: 16px;
            font-weight: 600;
        }

        .dtr-loading i {
            font-size: 24px;
            margin-right: 12px;
        }

        @media (max-width: 768px) {
            .modal-content {
                width: 95%;
                margin: 5% auto;
                max-height: 95vh;
            }

            .modal-body {
                padding: 20px;
            }

            .dtr-filters {
                flex-direction: column;
            }

            .filter-group {
                min-width: 100%;
            }

            .dtr-summary {
                grid-template-columns: 1fr;
            }
        }

        /* Journal Modal Styles */
        .journal-loading {
            text-align: center;
            padding: 40px;
            color: var(--primary);
            font-size: 16px;
            font-weight: 600;
        }

        .journal-loading i {
            font-size: 24px;
            margin-right: 12px;
        }

        .journal-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
            padding: 20px;
            background: var(--light);
            border-radius: 12px;
        }

        .info-item {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .info-label {
            font-size: 12px;
            font-weight: 600;
            color: var(--secondary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-value {
            font-size: 16px;
            font-weight: 600;
            color: var(--primary);
        }

        .journal-list-container {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: var(--shadow);
            max-height: 500px;
            overflow-y: auto;
        }

        .journal-list {
            padding: 20px;
        }

        /* Custom Scrollbar for Journal List */
        .journal-list-container::-webkit-scrollbar {
            width: 8px;
        }

        .journal-list-container::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        .journal-list-container::-webkit-scrollbar-thumb {
            background: var(--primary);
            border-radius: 4px;
        }

        .journal-list-container::-webkit-scrollbar-thumb:hover {
            background: var(--primary-dark);
        }

        .journal-entry {
            background: var(--light);
            padding: 16px 20px;
            border-radius: 10px;
            margin-bottom: 16px;
            border-left: 4px solid var(--success);
            transition: all 0.3s ease;
        }

        .journal-entry:hover {
            transform: translateX(4px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);
        }

        .journal-entry-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 12px;
            flex-wrap: wrap;
            gap: 12px;
        }

        .journal-entry-title {
            font-size: 16px;
            font-weight: 700;
            color: var(--dark);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .journal-entry-date {
            font-size: 12px;
            color: var(--secondary);
            font-weight: 500;
            margin-top: 4px;
        }

        .journal-entry-actions {
            display: flex;
            gap: 8px;
        }

        .btn-view-doc {
            padding: 6px 12px;
            background: rgba(16, 185, 129, 0.1);
            color: var(--success);
            border: 1px solid var(--success);
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-view-doc:hover {
            background: var(--success);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        .journal-empty {
            text-align: center;
            padding: 60px 20px;
            color: var(--secondary);
        }

        .journal-empty i {
            font-size: 48px;
            margin-bottom: 16px;
            opacity: 0.5;
        }

        .journal-empty p {
            font-size: 14px;
            margin: 0;
        }

        @media (max-width: 768px) {
            .journal-info {
                grid-template-columns: 1fr;
            }

            .journal-entry-header {
                flex-direction: column;
            }
        }
    </style>

    <script>
        // Real-time search functionality
        document.addEventListener('DOMContentLoaded', function() {
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

        function openArchiveModal(id, fullName, appUrl, parentUrl, acceptUrl) {
            const links = [];
            if (appUrl) links.push(`<a href="${appUrl}" target="_blank">Application Letter</a>`);
            if (parentUrl) links.push(`<a href="${parentUrl}" target="_blank">Parent's Waiver</a>`);
            if (acceptUrl) links.push(`<a href="${acceptUrl}" target="_blank">Acceptance Letter</a>`);

            const html = `
                <div style="text-align:left">
                    <p><strong>Intern:</strong> ${fullName}</p>
                    ${links.length ? `<p><strong>Documents:</strong></p><ul style="padding-left:18px;">${links.map(l=>`<li>${l}</li>`).join('')}</ul>` : '<p><em>No document links available.</em></p>'}
                    <p>Archiving will hide this intern from the Documents list but keep their data for future reference.</p>
                </div>
            `;

            Swal.fire({
                title: 'Archive Intern?',
                html,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Archive',
                cancelButtonText: 'Cancel',
            }).then(res => {
                if (res.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/interns/${id}/archive`;
                    form.innerHTML = `
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    `;
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: "{{ session('success') }}"
            });
        @endif

        // DTR Modal Functions
        let currentInternId = null;
        let availableMonths = [];

        function openDTRModal(internId, internName) {
            currentInternId = internId;
            const modal = document.getElementById('dtrModal');
            modal.style.display = 'block';
            document.body.style.overflow = 'hidden';
            
            // Reset filters
            document.getElementById('monthFilter').value = '';
            document.getElementById('weekFilter').value = '';
            document.getElementById('weekFilter').innerHTML = '<option value="">All Weeks</option>';
            
            // Show loading state
            document.getElementById('dtrLoading').style.display = 'block';
            document.getElementById('dtrContent').style.display = 'none';
            
            // Load initial data to get available months and show all records
            loadDTRData(true);
        }

        function closeDTRModal() {
            const modal = document.getElementById('dtrModal');
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
            currentInternId = null;
        }

        // Close modals when clicking outside
        window.onclick = function(event) {
            const dtrModal = document.getElementById('dtrModal');
            if (event.target === dtrModal) {
                closeDTRModal();
            }
            
            const journalModal = document.getElementById('journalModal');
            if (event.target === journalModal) {
                closeJournalModal();
            }
        }

        function updateWeekFilter() {
            const monthSelect = document.getElementById('monthFilter');
            const weekSelect = document.getElementById('weekFilter');
            const selectedMonth = monthSelect.value;
            
            weekSelect.innerHTML = '<option value="">All Weeks</option>';
            
            if (!selectedMonth) {
                return;
            }

            // Calculate weeks in the selected month
            const [year, month] = selectedMonth.split('-');
            const monthStart = new Date(year, month - 1, 1);
            const monthEnd = new Date(year, month, 0);
            
            // Find the first Monday of the month (or start of month if it's a Monday)
            let currentDate = new Date(monthStart);
            let weekNumber = 1;
            const weeks = [];
            
            while (currentDate <= monthEnd) {
                const weekStart = new Date(currentDate);
                // Adjust to start of week (Monday)
                const dayOfWeek = weekStart.getDay();
                const diff = dayOfWeek === 0 ? -6 : 1 - dayOfWeek;
                weekStart.setDate(weekStart.getDate() + diff);
                
                const weekEnd = new Date(weekStart);
                weekEnd.setDate(weekEnd.getDate() + 6);
                
                // Only add week if it overlaps with the selected month
                if (weekEnd >= monthStart && weekStart <= monthEnd) {
                    weeks.push({
                        number: weekNumber,
                        start: new Date(Math.max(weekStart, monthStart)),
                        end: new Date(Math.min(weekEnd, monthEnd))
                    });
                    weekNumber++;
                }
                
                currentDate.setDate(currentDate.getDate() + 7);
            }
            
            // Populate week dropdown
            weeks.forEach((week, index) => {
                const option = document.createElement('option');
                option.value = index + 1;
                const startStr = week.start.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
                const endStr = week.end.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
                option.textContent = `Week ${index + 1} (${startStr} - ${endStr})`;
                weekSelect.appendChild(option);
            });
        }

        function resetFilters() {
            document.getElementById('monthFilter').value = '';
            document.getElementById('weekFilter').value = '';
            document.getElementById('weekFilter').innerHTML = '<option value="">All Weeks</option>';
            loadDTRData();
        }

        function loadDTRData(isInitial = false) {
            if (!currentInternId) return;
            
            const monthFilter = document.getElementById('monthFilter').value;
            const weekFilter = document.getElementById('weekFilter').value;
            const loadingDiv = document.getElementById('dtrLoading');
            const contentDiv = document.getElementById('dtrContent');
            
            // Show loading
            loadingDiv.style.display = 'block';
            contentDiv.style.display = 'none';
            
            // Build URL with filters
            let url = `/documents/${currentInternId}/dtr/filtered`;
            const params = new URLSearchParams();
            if (monthFilter) params.append('month', monthFilter);
            if (weekFilter) params.append('week', weekFilter);
            if (params.toString()) url += '?' + params.toString();
            
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    // Update available months on initial load
                    if (isInitial && data.available_months) {
                        availableMonths = data.available_months;
                        const monthSelect = document.getElementById('monthFilter');
                        monthSelect.innerHTML = '<option value="">All Months</option>';
                        data.available_months.forEach(month => {
                            const option = document.createElement('option');
                            option.value = month;
                            const [year, m] = month.split('-');
                            const date = new Date(year, m - 1, 1);
                            option.textContent = date.toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
                            monthSelect.appendChild(option);
                        });
                    }
                    
                    // Update summary
                    document.getElementById('targetHours').textContent = data.summary.target_hours.toFixed(2);
                    document.getElementById('totalHours').textContent = data.summary.total_hours.toFixed(2);
                    document.getElementById('remainingHours').textContent = data.summary.remaining_hours.toFixed(2);
                    document.getElementById('totalDays').textContent = data.summary.total_days;
                    
                    // Update table
                    const tbody = document.getElementById('dtrTableBody');
                    if (data.logs && data.logs.length > 0) {
                        tbody.innerHTML = data.logs.map(log => `
                            <tr>
                                <td>${log.date}</td>
                                <td>${log.time_in}</td>
                                <td>${log.time_out}</td>
                                <td>${log.hours}</td>
                            </tr>
                        `).join('');
                    } else {
                        tbody.innerHTML = '<tr><td colspan="4" class="text-center">No attendance records found for the selected filters</td></tr>';
                    }
                    
                    // Hide loading, show content
                    loadingDiv.style.display = 'none';
                    contentDiv.style.display = 'block';
                })
                .catch(error => {
                    console.error('Error loading DTR data:', error);
                    loadingDiv.style.display = 'none';
                    contentDiv.style.display = 'block';
                    document.getElementById('dtrTableBody').innerHTML = 
                        '<tr><td colspan="4" class="text-center">Error loading DTR data. Please try again.</td></tr>';
                });
        }

        // Journal Modal Functions
        let currentJournalInternId = null;

        function openJournalModal(internId, firstName, lastName) {
            currentJournalInternId = internId;
            const modal = document.getElementById('journalModal');
            modal.style.display = 'block';
            document.body.style.overflow = 'hidden';
            
            // Reset content
            document.getElementById('journalInternName').textContent = `${firstName} ${lastName}`;
            document.getElementById('journalCompanyName').textContent = '-';
            document.getElementById('journalTotalCount').textContent = '0';
            document.getElementById('journalList').innerHTML = '<div class="journal-loading"><i class="fas fa-spinner fa-spin"></i> Loading journal entries...</div>';
            
            // Show loading
            document.getElementById('journalLoading').style.display = 'block';
            document.getElementById('journalContent').style.display = 'none';
            
            // Load journal data
            loadJournalData(internId);
        }

        function closeJournalModal() {
            const modal = document.getElementById('journalModal');
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
            currentJournalInternId = null;
        }


        function loadJournalData(internId) {
            fetch(`{{ url('/documents') }}/${internId}/journal`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                // Update intern info
                document.getElementById('journalInternName').textContent = `${data.intern.first_name} ${data.intern.last_name}`;
                document.getElementById('journalCompanyName').textContent = data.intern.company_name || 'N/A';
                document.getElementById('journalTotalCount').textContent = data.journals ? data.journals.length : 0;
                
                // Render journal entries
                const journalList = document.getElementById('journalList');
                
                if (data.journals && data.journals.length > 0) {
                    journalList.innerHTML = data.journals.map((journal, index) => `
                        <div class="journal-entry">
                            <div class="journal-entry-header">
                                <div>
                                    <div class="journal-entry-title">
                                        <i class="fas fa-file-alt"></i>
                                        ${escapeHtml(journal.description)}
                                    </div>
                                    <div class="journal-entry-date">
                                        <i class="fas fa-calendar"></i>
                                        Submitted: ${journal.submitted_at}
                                    </div>
                                </div>
                                <div class="journal-entry-actions">
                                    ${journal.view_url ? `
                                        <a href="${journal.view_url}" target="_blank" class="btn-view-doc">
                                            <i class="fas fa-eye"></i>
                                            View Document
                                        </a>
                                    ` : ''}
                                </div>
                            </div>
                        </div>
                    `).join('');
                } else {
                    journalList.innerHTML = `
                        <div class="journal-empty">
                            <i class="fas fa-book-open"></i>
                            <p>No journal entries submitted yet.</p>
                        </div>
                    `;
                }
                
                // Hide loading, show content
                document.getElementById('journalLoading').style.display = 'none';
                document.getElementById('journalContent').style.display = 'block';
            })
            .catch(error => {
                console.error('Error loading journal data:', error);
                document.getElementById('journalLoading').style.display = 'none';
                document.getElementById('journalContent').style.display = 'block';
                document.getElementById('journalList').innerHTML = `
                    <div class="journal-empty">
                        <i class="fas fa-exclamation-circle"></i>
                        <p>Error loading journal entries. Please try again.</p>
                    </div>
                `;
            });
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
    </script>
@endsection