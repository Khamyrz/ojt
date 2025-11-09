@extends('layouts.app')

@section('content')
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

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
            --shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body { font-family: 'Inter', sans-serif; background: transparent; padding: 0; }

        /* Use layout's container; only section-level styles below */

        .header {
            margin-bottom: 32px;
        }

        .header h1 {
            font-size: 28px;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 8px;
        }

        .header p {
            color: var(--secondary);
            font-size: 14px;
        }

        .datetime-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 32px;
        }

        .datetime-card {
            background: white;
            padding: 24px;
            border-radius: 12px;
            box-shadow: var(--shadow);
            border-left: 4px solid var(--primary);
        }

        .datetime-card h3 {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--secondary);
            margin-bottom: 12px;
            font-weight: 600;
        }

        .datetime-card p {
            font-size: 18px;
            font-weight: 600;
            color: var(--dark);
            margin: 0;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 32px;
        }

        .stat-card {
            background: white;
            padding: 24px;
            border-radius: 12px;
            box-shadow: var(--shadow);
            position: relative;
            overflow: hidden;
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100px;
            height: 100px;
            border-radius: 50%;
            opacity: 0.1;
            transform: translate(30%, -30%);
        }

        .stat-card.success::before {
            background: var(--success);
        }

        .stat-card.danger::before {
            background: var(--danger);
        }

        .stat-card.primary::before {
            background: var(--primary);
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
            font-size: 20px;
        }

        .stat-card.success .stat-icon {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success);
        }

        .stat-card.danger .stat-icon {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger);
        }

        .stat-card.primary .stat-icon {
            background: rgba(37, 99, 235, 0.1);
            color: var(--primary);
        }

        .stat-label {
            font-size: 13px;
            color: var(--secondary);
            margin-bottom: 8px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-value {
            font-size: 32px;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 8px;
        }

        .stat-change {
            font-size: 12px;
            color: var(--success);
            display: flex;
            align-items: center;
            gap: 4px;
        }

        /* Review Chart - SMALLER */
        .chart-section {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: var(--shadow);
            margin-bottom: 32px;
        }

        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
        }

        .chart-header h3 {
            font-size: 15px;
            font-weight: 700;
            color: var(--dark);
        }

        .circular-chart {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 15px 0;
        }

        .circle-progress {
            position: relative;
            width: 110px;
            height: 110px;
        }

        .circle-bg {
            fill: none;
            stroke: #e2e8f0;
            stroke-width: 8;
        }

        .circle-bar {
            fill: none;
            stroke: var(--danger);
            stroke-width: 8;
            stroke-linecap: round;
            transform: rotate(-90deg);
            transform-origin: center;
            transition: stroke-dashoffset 1s ease;
        }

        .circle-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
        }

        .circle-text .percentage {
            font-size: 22px;
            font-weight: 700;
            color: var(--dark);
        }

        .circle-text .label {
            font-size: 10px;
            color: var(--secondary);
            margin-top: 2px;
        }

        /* Progress Section - SMALLER */
        .progress-section {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: var(--shadow);
        }

        .progress-header {
            margin-bottom: 16px;
        }

        .progress-header h3 {
            font-size: 15px;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 10px;
        }

        .region-tags {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
        }

        .region-tag {
            padding: 3px 8px;
            border-radius: 14px;
            font-size: 10px;
            font-weight: 600;
            color: white;
        }

        .region-tag.north { background: var(--primary); }
        .region-tag.west { background: var(--success); }
        .region-tag.south { background: var(--warning); }
        .region-tag.east { background: var(--danger); }

        .progress-item {
            margin-bottom: 16px;
        }

        .progress-item:last-child {
            margin-bottom: 0;
        }

        .progress-label {
            display: flex;
            justify-content: space-between;
            margin-bottom: 6px;
        }

        .progress-label span:first-child {
            font-size: 12px;
            font-weight: 600;
            color: var(--dark);
        }

        .progress-label span:last-child {
            font-size: 11px;
            font-weight: 600;
            color: var(--primary);
        }

        .progress-bar-container {
            background: #e2e8f0;
            height: 6px;
            border-radius: 3px;
            overflow: hidden;
        }

        .progress-bar-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--primary) 0%, #60a5fa 100%);
            border-radius: 3px;
            transition: width 1s ease;
            position: relative;
        }

        .progress-bar-fill::after {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3));
            animation: shimmer 2s infinite;
        }

        @keyframes shimmer {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }

        /* Footer Stats */
        .footer-stats {
            display: flex;
            gap: 16px;
            margin-top: 32px;
            flex-wrap: wrap;
        }

        .footer-stat {
            flex: 1;
            min-width: 200px;
            padding: 16px 20px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 14px;
            font-weight: 600;
        }

        .footer-stat.total {
            background: rgba(37, 99, 235, 0.1);
            color: var(--primary);
        }

        .footer-stat.active {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success);
        }

        .footer-stat i {
            font-size: 18px;
        }
    </style>
    <div class="dashboard-container" style="background: transparent; box-shadow:none; padding:0; margin:0; max-width:none;">
        <div class="main-content" style="background: var(--light); border-radius: 16px;">
        <div class="header">
            <h1>Welcome Back, Admin</h1>
            <p>Here's what's happening with your intern management system today.</p>
        </div>

        <!-- Date & Time -->
        <div class="datetime-cards">
            <div class="datetime-card">
                <h3><i class="fas fa-calendar"></i> Date Today</h3>
                <p>{{ \Carbon\Carbon::now()->format('l, F j, Y') }}</p>
            </div>
            <div class="datetime-card">
                <h3><i class="fas fa-clock"></i> Current Time</h3>
                <p>{{ \Carbon\Carbon::now()->format('h:i A') }}</p>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="stats-grid">
            <div class="stat-card success">
                <div class="stat-icon">
                    <i class="fas fa-user-check"></i>
                </div>
                <div class="stat-label">Total Interns</div>
                <div class="stat-value">{{ $acceptedCount }}</div>
                <div class="stat-change">
                    <i class="fas fa-arrow-up"></i>
                    <span>Active interns</span>
                </div>
            </div>

            <div class="stat-card danger">
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-label">Pending Reviews</div>
                <div class="stat-value">{{ $toReview }}</div>
                <div class="stat-change">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>Requires attention</span>
                </div>
            </div>

            <div class="stat-card primary">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-label">Total Students</div>
                <div class="stat-value">{{ $acceptedCount + $pendingCount }}</div>
                <div class="stat-change">
                    <i class="fas fa-info-circle"></i>
                    <span>All registered</span>
                </div>
            </div>
        </div>

        <!-- Review Chart -->
        <div class="chart-section">
            <div class="chart-header">
                <h3>Review Status Overview</h3>
            </div>
            <div class="circular-chart">
                <div class="circle-progress">
                    <svg width="140" height="140">
                        <circle class="circle-bg" cx="70" cy="70" r="60"></circle>
                        <circle class="circle-bar" cx="70" cy="70" r="60"
                                stroke-dasharray="{{ 2 * 3.14159 * 60 }}"
                                stroke-dashoffset="{{ 2 * 3.14159 * 60 * (1 - $toReviewPercent / 100) }}">
                        </circle>
                    </svg>
                    <div class="circle-text">
                        <div class="percentage">{{ $toReviewPercent }}%</div>
                        <div class="label">Pending</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Progress Overview -->
        <div class="progress-section">
            <div class="progress-header">
                <h3>Progress Overview</h3>
                <div class="region-tags">
                    <span class="region-tag north">North Region</span>
                    <span class="region-tag west">West Region</span>
                    <span class="region-tag south">South Region</span>
                    <span class="region-tag east">East Region</span>
                </div>
            </div>

            <div class="progress-item">
                <div class="progress-label">
                    <span>Today's Progress</span>
                    <span>{{ $todayProgress }}%</span>
                </div>
                <div class="progress-bar-container">
                    <div class="progress-bar-fill" style="width: {{ $todayProgress }}%;"></div>
                </div>
            </div>

            <div class="progress-item">
                <div class="progress-label">
                    <span>This Week's Progress</span>
                    <span>{{ $weekProgress }}%</span>
                </div>
                <div class="progress-bar-container">
                    <div class="progress-bar-fill" style="width: {{ $weekProgress }}%;"></div>
                </div>
            </div>

            <div class="progress-item">
                <div class="progress-label">
                    <span>This Month's Progress</span>
                    <span>{{ $monthProgress }}%</span>
                </div>
                <div class="progress-bar-container">
                    <div class="progress-bar-fill" style="width: {{ $monthProgress }}%;"></div>
                </div>
            </div>
        </div>

        <!-- Footer Stats -->
        <div class="footer-stats">
            <div class="footer-stat total">
                <i class="fas fa-users"></i>
                <span>Total Students: {{ $acceptedCount + $pendingCount }}</span>
            </div>
            <div class="footer-stat active">
                <i class="fas fa-circle"></i>
                <span>Active Online: {{ $acceptedCount }}</span>
            </div>
        </div>
        </div>
    </div>

    <script>
    // Animate progress bars on load
    window.addEventListener('load', function() {
        const progressBars = document.querySelectorAll('.progress-bar-fill');
        progressBars.forEach(bar => {
            const width = bar.style.width;
            bar.style.width = '0%';
            setTimeout(() => {
                bar.style.width = width;
            }, 100);
        });
    });

    // Update time every minute
    setInterval(function() {
        location.reload();
    }, 60000);
    </script>
@endsection