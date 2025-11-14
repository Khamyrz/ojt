<!DOCTYPE html>
<html>
<head>
    <title>{{ $intern->first_name }} {{ $intern->last_name }} - DTR</title>
    @php
        use Carbon\Carbon;

        // NOTE: In a real Laravel environment, $intern and $logs would be passed in.
        // Mock data structure for standalone visualization (if needed for testing):
        /*
        $intern = (object)['first_name' => 'Jane', 'last_name' => 'Doe'];
        $logs = collect([
            (object)['date' => '2025-07-01', 'time_in' => '09:00:00', 'time_out' => '17:00:00'],
            (object)['date' => '2025-07-02', 'time_in' => '09:05:00', 'time_out' => '17:05:00'],
            (object)['date' => '2025-07-03', 'time_in' => '09:00:00', 'time_out' => '17:00:00'],
            // Add more entries up to 20 for visual testing
        ]);
        */

        $targetHours = 486;
        $totalMinutes = 0;
        foreach ($logs as $logItem) {
            if ($logItem->time_in && $logItem->time_out) {
                // Assuming 'Asia/Manila' is the required timezone, keeping the original logic
                $timeIn = Carbon::parse($logItem->date . ' ' . $logItem->time_in, 'Asia/Manila');
                $timeOut = Carbon::parse($logItem->date . ' ' . $logItem->time_out, 'Asia/Manila');
                $totalMinutes += $timeIn->diffInMinutes($timeOut);
            }
        }
        $totalHours = round($totalMinutes / 60, 2);
        $remainingHours = max(0, round($targetHours - $totalHours, 2));
        $monthLabel = $logs->first()
            ? Carbon::parse($logs->first()->date, 'Asia/Manila')->format('F Y')
            : now('Asia/Manila')->format('F Y');
    @endphp
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            padding: 10px 20px;
            background-color: #f7f5fa;
            color: #333333;
        }

        .timesheet-wrapper {
            max-width: 900px;
            margin: 0 auto;
            background: #ffffff;
            border: 2px solid #8c6bb1;
            border-radius: 18px;
            box-shadow: 0 15px 40px rgba(140, 107, 177, 0.15);
            overflow: hidden;
        }

        .timesheet-header {
            position: relative;
            background: linear-gradient(135deg, #8c6bb1, #6c4a91);
            color: #ffffff;
            text-align: center;
            padding: 15px 20px 20px;
        }

        .timesheet-header::after {
            content: '';
            position: absolute;
            bottom: -20px;
            left: 50%;
            transform: translateX(-50%);
            width: 200px;
            height: 40px;
            background: inherit;
            border-radius: 50%;
            z-index: -1;
        }

        .timesheet-title {
            font-size: 20px;
            letter-spacing: 1px;
            font-weight: 700;
            text-transform: uppercase;
            margin: 0;
        }

        .timesheet-meta {
            position: relative;
            padding: 25px 20px 35px;
            height: 60px;
        }

        .meta-item {
            position: absolute;
            border-bottom: 2px solid #d5c8eb;
            padding-bottom: 4px;
            font-size: 12px;
            text-align: center;
            min-width: 150px;
        }

        .meta-item:nth-child(1) {
            top: 25px;
            left: 50%;
            transform: translateX(-50%);
        }

        .meta-item:nth-child(2) {
            bottom: 10px;
            left: 15%;
        }

        .meta-item:nth-child(3) {
            bottom: 10px;
            right: 15%;
        }

        .meta-label {
            font-weight: 600;
            color: #6c4a91;
        }

        .timesheet-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }

        .timesheet-table th,
        .timesheet-table td {
            border: 1px solid #cfc1e6;
            padding: 8px;
            text-align: center;
        }

        .timesheet-table th {
            background-color: #6c4a91;
            color: #ffffff;
            font-size: 14px;
            letter-spacing: 0.5px;
        }

        .timesheet-table td {
            font-size: 13px;
        }

        .timesheet-table tbody tr:nth-child(even) {
            background-color: #f9f4ff;
        }

        .timesheet-footer {
            background: #f2ecfb;
            padding: 15px 40px 20px;
            text-align: center;
            color: #4a315f;
            font-size: 14px;
        }

        .summary-grid {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
            margin-bottom: 10px;
        }

        .summary-card {
            min-width: 160px;
            background: #ffffff;
            border: 1px solid #d5c8eb;
            border-radius: 12px;
            padding: 10px 15px;
            box-shadow: 0 8px 20px rgba(108, 74, 145, 0.1);
        }

        .summary-title {
            font-weight: 600;
            color: #6c4a91;
            margin-bottom: 4px;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.8px;
        }

        .summary-value {
            font-size: 18px;
            font-weight: 700;
            color: #3c2753;
        }

        .button-group {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 20px;
        }

        .button {
            display: inline-block;
            min-width: 140px;
            padding: 10px;
            text-align: center;
            background-color: #38c172;
            color: white;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
            border: none;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .button:hover {
            background-color: #2f9e63;
        }

        .back-button {
            background-color: #6c757d;
        }

        .back-button:hover {
            background-color: #5a6268;
        }

        /* --- PRINT OPTIMIZATION FOR SINGLE PAGE --- */
        @media print {
            @page {
                size: A4;
                /* Maximize space with minimal margins */
                margin: 0.5cm;
            }

            body {
                padding: 0;
                background: #ffffff;
                margin: 0;
            }

            .timesheet-wrapper {
                box-shadow: none;
                border: 1px solid #8c6bb1;
                border-radius: 0;
                max-width: 100%;
                margin: 0;
                /* This is critical for print layout */
                border-bottom: none;
            }

            .timesheet-header {
                /* Further reduced top/bottom padding */
                padding: 10px 20px 15px;
                page-break-after: avoid;
            }

            .timesheet-header::after {
                /* Hide the decorative element in print */
                display: none;
            }

            .timesheet-meta {
                /* Reduced vertical space in the metadata section */
                padding: 15px 20px 5px;
                page-break-after: avoid;
                height: 30px; 
            }

            .meta-item {
                font-size: 10px; /* Reduced font size */
            }

            .timesheet-table {
                margin-top: 5px;
                font-size: 10px;
            }
            
            /* CRITICAL: MINIMIZED padding and font size for table cells to ensure 31 rows fit */
            .timesheet-table th,
            .timesheet-table td {
                padding: 2px 4px; /* Minimized padding */
                font-size: 9px; /* Minimized font size */
            }

            .timesheet-table th {
                font-size: 10px; /* Adjusted header font size */
                padding: 4px;
            }

            .timesheet-footer {
                background: #f2ecfb;
                /* Reduced padding */
                padding: 8px 20px 8px;
                font-size: 11px;
                page-break-inside: avoid;
                border-top: 1px solid #cfc1e6;
                border-bottom: 1px solid #8c6bb1; /* Add a bottom border for structure */
            }

            .summary-grid {
                gap: 10px;
                margin-bottom: 5px;
            }

            .summary-card {
                min-width: 120px;
                padding: 5px 8px;
            }

            .summary-title {
                font-size: 9px;
                margin-bottom: 2px;
            }

            .summary-value {
                font-size: 14px;
            }

            .timesheet-footer p {
                margin: 5px 0 0 0;
                font-size: 9px;
            }

            .button-group {
                /* Hide buttons when printing */
                display: none;
            }

            /* Ensure table rows do not break across pages */
            .timesheet-table tr {
                page-break-inside: avoid;
            }

            .timesheet-table thead {
                display: table-header-group;
            }
        }

        @media (max-width: 768px) {
            body {
                padding: 20px;
            }

            .timesheet-meta {
                position: relative;
                padding: 30px 20px 40px;
                height: auto;
                min-height: 80px;
            }

            .meta-item {
                position: static;
                width: 90% !important;
                margin: 0 auto 8px;
                left: auto !important;
                right: auto !important;
                top: auto !important;
                bottom: auto !important;
                transform: none !important;
            }

            /* Show print button on mobile to trigger print dialog */
            .button-group {
                display: flex;
            }
        }
    </style>
</head>
<body>

    <div class="timesheet-wrapper">
        <div class="timesheet-header">
            <div class="timesheet-title">Weekly Timesheet</div>
        </div>

        <div class="timesheet-meta">
            <div class="meta-item">
                <span class="meta-label">Employee:</span>
                &nbsp;{{ $intern->first_name }} {{ $intern->last_name }}
            </div>
            <div class="meta-item">
                <span class="meta-label">For Month Ended:</span>
                &nbsp;{{ $monthLabel }}
            </div>
            <div class="meta-item">
                <span class="meta-label">Position:</span>
                &nbsp;Intern
            </div>
        </div>

        <table class="timesheet-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Time In</th>
                    <th>Time Out</th>
                    <th>Total Hours</th>
                </tr>
            </thead>
            <tbody>
                @php $rowCount = 0; @endphp
                @forelse($logs as $log)
                    @php
                        $rowCount++;
                        $date = Carbon::parse($log->date, 'Asia/Manila')->format('F d, Y');
                        $timeIn = $log->time_in
                            ? Carbon::parse($log->date . ' ' . $log->time_in, 'Asia/Manila')
                            : null;
                        $timeOut = $log->time_out
                            ? Carbon::parse($log->date . ' ' . $log->time_out, 'Asia/Manila')
                            : null;
                        $dailyHours = ($timeIn && $timeOut)
                            ? round($timeIn->diffInMinutes($timeOut) / 60, 2)
                            : null;
                    @endphp
                    <tr>
                        <td>{{ $date }}</td>
                        <td>{{ $timeIn ? $timeIn->format('h:i A') : '—' }}</td>
                        <td>{{ $timeOut ? $timeOut->format('h:i A') : '—' }}</td>
                        <td>{{ $dailyHours !== null ? number_format($dailyHours, 2) : '—' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">No attendance records found.</td>
                    </tr>
                @endforelse

                {{-- Fill remaining rows to a total of 31 for official documentation --}}
                @for($i = $rowCount; $i < 31; $i++)
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                @endfor
            </tbody>
        </table>

        <div class="timesheet-footer">
            <div class="summary-grid">
                <div class="summary-card">
                    <div class="summary-title">Target Hours</div>
                    <div class="summary-value">{{ number_format($targetHours, 2) }}</div>
                </div>
                <div class="summary-card">
                    <div class="summary-title">Logged Hours</div>
                    <div class="summary-value">{{ number_format($totalHours, 2) }}</div>
                </div>
                <div class="summary-card">
                    <div class="summary-title">Remaining Hours</div>
                    <div class="summary-value">{{ number_format($remainingHours, 2) }}</div>
                </div>
            </div>
            <p>Please review and ensure all entries are accurate before printing or submitting.</p>
        </div>
    </div>

    <div class="button-group">
        <button class="button" onclick="window.print()">🖨️ Print</button>
        <a href="{{ url()->previous() }}" class="button back-button">⬅️ Back</a>
    </div>

</body>
</html>