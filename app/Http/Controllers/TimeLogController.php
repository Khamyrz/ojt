<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TimeLog;
use App\Models\Intern;
use Carbon\Carbon;

class TimeLogController extends Controller
{
    /**
     * Handle intern time in (only once per day).
     */
    public function timeIn()
    {
        $intern = Auth::guard('intern')->user();
        $now = now('Asia/Manila');
        $today = $now->toDateString();

        // Allow Monday to Saturday only
        if ($now->isSunday()) {
            return request()->expectsJson()
                ? response()->json(['success' => false, 'message' => 'Attendance is closed on Sundays.'], 400)
                : back()->with('error', 'Attendance is closed on Sundays.');
        }

        // Allow time-in only between 8:00 AM and 5:00 PM
        $start = \Carbon\Carbon::createFromTime(8, 0, 0, 'Asia/Manila');
        $end = \Carbon\Carbon::createFromTime(17, 0, 0, 'Asia/Manila');
        if (!$now->betweenIncluded($start, $end)) {
            return request()->expectsJson()
                ? response()->json(['success' => false, 'message' => 'Time In is available from 8:00 AM to 5:00 PM (Mon-Sat).'], 400)
                : back()->with('error', 'Time In is available from 8:00 AM to 5:00 PM (Mon-Sat).');
        }

        $existing = TimeLog::where('intern_id', $intern->id)
            ->where('date', $today)
            ->first();

        if ($existing) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You already timed in today.'
                ]);
            }
            return back()->with('error', 'You already timed in today.');
        }

        TimeLog::create([
            'intern_id' => $intern->id,
            'date' => $today,
            'time_in' => $now->toTimeString(),
        ]);

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Time In recorded successfully!'
            ]);
        }

        return back()->with('success', '✅ Time In recorded!');
    }

    /**
     * Handle intern time out (manual or automatic at 5:00 PM).
     */
    public function timeOut()
    {
        $intern = Auth::guard('intern')->user();
        $now = now('Asia/Manila');
        $today = $now->toDateString();

        $log = TimeLog::where('intern_id', $intern->id)
            ->where('date', $today)
            ->first();

        if (!$log) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You must time in first before timing out.'
                ]);
            }
            return back()->with('error', '⚠️ You must time in first before timing out.');
        }

        if ($log->time_out) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You already timed out today.'
                ]);
            }
            return back()->with('error', '⏳ You already timed out today.');
        }

        // If time out is after 5:00 PM, record exactly 5:00 PM
        $timeOut = $now->greaterThan(Carbon::createFromTime(17, 0, 0, 'Asia/Manila'))
            ? '17:00:00'
            : $now->toTimeString();

        $log->update([
            'time_out' => $timeOut,
        ]);

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Time Out recorded successfully!'
            ]);
        }

        return back()->with('success', '🕔 Time Out recorded!');
    }

    /**
     * Admin view of DTR as printable Blade table (not download).
     */
    public function showDTR($id)
    {
        $intern = Intern::findOrFail($id);
        $logs = TimeLog::where('intern_id', $id)
            ->orderBy('date', 'asc')
            ->get();

        return view('dtr', compact('intern', 'logs'));
    }

    /**
     * Get real-time DTR data for the current month
     */
    public function getRealTimeDTR()
    {
        $intern = Auth::guard('intern')->user();
        $now = now('Asia/Manila');
        $currentMonth = $now->format('Y-m');
        
        $logs = TimeLog::where('intern_id', $intern->id)
            ->whereRaw("DATE_FORMAT(date, '%Y-%m') = ?", [$currentMonth])
            ->orderBy('date', 'desc')
            ->get();

        $totalHours = 0;
        $totalDays = 0;
        $currentDayLog = null;

        foreach ($logs as $log) {
            if ($log->time_in && $log->time_out) {
                $timeIn = Carbon::parse($log->date . ' ' . $log->time_in, 'Asia/Manila');
                $timeOut = Carbon::parse($log->date . ' ' . $log->time_out, 'Asia/Manila');
                $totalHours += $timeIn->diffInHours($timeOut);
                $totalDays++;
            }
            
            // Get current day's log
            if ($log->date === $now->toDateString()) {
                $currentDayLog = $log;
            }
        }

        // Auto-timeout at 5PM if not yet timed out
        if ($currentDayLog && $currentDayLog->time_in && !$currentDayLog->time_out && $now->greaterThan(Carbon::createFromTime(17, 0, 0, 'Asia/Manila'))) {
            $currentDayLog->update(['time_out' => '17:00:00']);
            $currentDayLog = $currentDayLog->fresh();
        }

        $isWorkingHours = $now->between(
            Carbon::createFromTime(8, 0, 0, 'Asia/Manila'),
            Carbon::createFromTime(17, 0, 0, 'Asia/Manila')
        );
        $isWorkday = !$now->isSunday();

        return response()->json([
            'total_hours' => $totalHours,
            'total_days' => $totalDays,
            'current_day_log' => $currentDayLog,
            'current_time' => $now->format('H:i:s'),
            'is_working_hours' => $isWorkingHours,
            'is_workday' => $isWorkday
        ]);
    }

    /**
     * Get DTR summary for dashboard
     */
    public function getDTRSummary()
    {
        $intern = Auth::guard('intern')->user();
        $now = now('Asia/Manila');
        $today = $now->toDateString();
        $currentMonth = $now->format('Y-m');
        
        $todayLog = TimeLog::where('intern_id', $intern->id)
            ->where('date', $today)
            ->first();

        // Auto-timeout at 5 PM if needed
        if ($todayLog && $todayLog->time_in && !$todayLog->time_out && $now->greaterThan(Carbon::createFromTime(17, 0, 0, 'Asia/Manila'))) {
            $todayLog->update(['time_out' => '17:00:00']);
            $todayLog = $todayLog->fresh();
        }

        $monthlyLogs = TimeLog::where('intern_id', $intern->id)
            ->whereRaw("DATE_FORMAT(date, '%Y-%m') = ?", [$currentMonth])
            ->get();

        $monthlyMinutes = 0;
        $monthlyDays = 0;

        foreach ($monthlyLogs as $log) {
            if ($log->time_in && $log->time_out) {
                $timeIn = Carbon::parse($log->date . ' ' . $log->time_in, 'Asia/Manila');
                $timeOut = Carbon::parse($log->date . ' ' . $log->time_out, 'Asia/Manila');
                $monthlyMinutes += $timeIn->diffInMinutes($timeOut);
                $monthlyDays++;
            }
        }

        $monthlyHours = round($monthlyMinutes / 60, 2);

        $allLogs = TimeLog::where('intern_id', $intern->id)->get();
        $totalMinutes = 0;

        foreach ($allLogs as $log) {
            if ($log->time_in && $log->time_out) {
                $timeIn = Carbon::parse($log->date . ' ' . $log->time_in, 'Asia/Manila');
                $timeOut = Carbon::parse($log->date . ' ' . $log->time_out, 'Asia/Manila');
                $totalMinutes += $timeIn->diffInMinutes($timeOut);
            }
        }

        $totalHours = round($totalMinutes / 60, 2);
        $targetHours = 486;
        $remainingHours = max(0, round($targetHours - $totalHours, 2));

        return response()->json([
            'today_status' => $todayLog ? ($todayLog->time_out ? 'completed' : 'working') : 'not_started',
            'today_time_in' => $todayLog?->time_in,
            'today_time_out' => $todayLog?->time_out,
            'monthly_hours' => $monthlyHours,
            'monthly_days' => $monthlyDays,
            'target_hours' => $targetHours,
            'total_hours' => $totalHours,
            'remaining_hours' => $remainingHours,
            'progress_percent' => min(100, round(($totalHours / $targetHours) * 100)),
            'is_working_hours' => $now->between(
                Carbon::createFromTime(8, 0, 0, 'Asia/Manila'),
                Carbon::createFromTime(17, 0, 0, 'Asia/Manila')
            ),
            'is_workday' => !$now->isSunday()
        ]);
    }

    /**
     * Show DTR for the authenticated intern (intern view).
     */
    public function showOwnDTR()
    {
        $intern = Auth::guard('intern')->user();
        $logs = TimeLog::where('intern_id', $intern->id)
            ->orderBy('date', 'asc')
            ->get();
        return view('dtr', compact('intern', 'logs'));
    }

    /**
     * Get filtered DTR data for modal (month and week filter)
     */
    public function getFilteredDTR($id, Request $request)
    {
        $intern = Intern::findOrFail($id);
        $query = TimeLog::where('intern_id', $id);

        // Filter by month if provided
        if ($request->has('month') && $request->month) {
            $query->whereRaw("DATE_FORMAT(date, '%Y-%m') = ?", [$request->month]);
        }

        // Filter by week if provided (week number in the month)
        if ($request->has('week') && $request->week && $request->has('month') && $request->month) {
            $monthStart = Carbon::createFromFormat('Y-m', $request->month)->startOfMonth();
            $monthEnd = $monthStart->copy()->endOfMonth();
            $targetWeek = (int)$request->week;
            
            // Calculate all weeks in the month
            $weeks = [];
            $currentDate = $monthStart->copy();
            
            while ($currentDate <= $monthEnd) {
                // Find Monday of the week containing currentDate
                $dayOfWeek = $currentDate->dayOfWeek;
                $mondayOffset = $dayOfWeek === 0 ? -6 : 1 - $dayOfWeek;
                $weekStart = $currentDate->copy()->addDays($mondayOffset);
                
                // Clamp to month boundaries
                if ($weekStart < $monthStart) {
                    $weekStart = $monthStart->copy();
                }
                
                $weekEnd = $weekStart->copy()->endOfWeek();
                if ($weekEnd > $monthEnd) {
                    $weekEnd = $monthEnd->copy();
                }
                
                // Only add if week overlaps with month
                if ($weekEnd >= $monthStart && $weekStart <= $monthEnd) {
                    $weekKey = $weekStart->format('Y-m-d');
                    if (!isset($weeks[$weekKey])) {
                        $weeks[$weekKey] = [
                            'start' => $weekStart,
                            'end' => $weekEnd
                        ];
                    }
                }
                
                $currentDate->addWeek();
            }
            
            // Get the target week (1-indexed)
            $weekKeys = array_keys($weeks);
            if (isset($weekKeys[$targetWeek - 1])) {
                $selectedWeek = $weeks[$weekKeys[$targetWeek - 1]];
                $query->whereBetween('date', [
                    $selectedWeek['start']->toDateString(),
                    $selectedWeek['end']->toDateString()
                ]);
            } else {
                // Week not found, return empty
                $query->whereRaw('1 = 0');
            }
        }

        $logs = $query->orderBy('date', 'asc')->get();

        // Calculate totals
        $targetHours = 486;
        $totalMinutes = 0;
        foreach ($logs as $log) {
            if ($log->time_in && $log->time_out) {
                $timeIn = Carbon::parse($log->date . ' ' . $log->time_in, 'Asia/Manila');
                $timeOut = Carbon::parse($log->date . ' ' . $log->time_out, 'Asia/Manila');
                $totalMinutes += $timeIn->diffInMinutes($timeOut);
            }
        }
        $totalHours = round($totalMinutes / 60, 2);
        $remainingHours = max(0, round($targetHours - $totalHours, 2));

        // Format logs for response
        $formattedLogs = $logs->map(function($log) {
            $date = Carbon::parse($log->date, 'Asia/Manila');
            $timeIn = $log->time_in
                ? Carbon::parse($log->date . ' ' . $log->time_in, 'Asia/Manila')
                : null;
            $timeOut = $log->time_out
                ? Carbon::parse($log->date . ' ' . $log->time_out, 'Asia/Manila')
                : null;
            $dailyHours = ($timeIn && $timeOut)
                ? round($timeIn->diffInMinutes($timeOut) / 60, 2)
                : null;

            return [
                'date' => $date->format('F d, Y'),
                'date_raw' => $log->date,
                'time_in' => $timeIn ? $timeIn->format('h:i A') : '—',
                'time_out' => $timeOut ? $timeOut->format('h:i A') : '—',
                'hours' => $dailyHours !== null ? number_format($dailyHours, 2) : '—',
                'hours_raw' => $dailyHours
            ];
        });

        // Get available months for filter
        $allLogs = TimeLog::where('intern_id', $id)->orderBy('date', 'desc')->get();
        $availableMonths = $allLogs->map(function($log) {
            return Carbon::parse($log->date, 'Asia/Manila')->format('Y-m');
        })->unique()->values();

        return response()->json([
            'intern' => [
                'id' => $intern->id,
                'name' => $intern->first_name . ' ' . $intern->last_name
            ],
            'logs' => $formattedLogs,
            'summary' => [
                'target_hours' => $targetHours,
                'total_hours' => $totalHours,
                'remaining_hours' => $remainingHours,
                'total_days' => $logs->count()
            ],
            'available_months' => $availableMonths
        ]);
    }
}
