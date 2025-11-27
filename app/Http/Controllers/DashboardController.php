<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Intern;
use App\Models\Message;
use App\Models\DocumentRequest;
use App\Models\GradeSubmission;
use App\Models\TimeLog;
use App\Models\Journal;
use App\Models\Document;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $adminId = Auth::id();

        // Scope all counts to interns invited/owned by the current admin
        $acceptedCount = Intern::where('status', 'accepted')
            ->whereNull('archived_at')
            ->where('invited_by_user_id', $adminId)
            ->count();

        $pendingCount = Intern::where('status', 'pending')
            ->where('invited_by_user_id', $adminId)
            ->count();

        $messageCount = Message::where(function ($q) use ($adminId) {
                $q->where('receiver_id', $adminId)->where('receiver_type', 'admin');
            })->orWhere(function ($q) use ($adminId) {
                $q->where('sender_id', $adminId)->where('sender_type', 'admin');
            })->count();

        $unreadMessagesCount = Message::where('receiver_id', Auth::id())
            ->where('receiver_type', 'admin')
            ->where('sender_type', 'intern')
            ->where('is_read', false)
            ->count();

        // Progress Tracking
        $totalInterns = $acceptedCount;
        $today = Carbon::today();
        $startOfWeek = Carbon::now()->startOfWeek();
        $startOfMonth = Carbon::now()->startOfMonth();

        // Sum durations for today
        $todayHours = TimeLog::whereDate('time_in', $today)
            ->whereHas('intern', function ($q) use ($adminId) {
                $q->where('invited_by_user_id', $adminId);
            })
            ->get()->sum(function ($log) {
            return ($log->time_in && $log->time_out)
                ? Carbon::parse($log->time_out)->diffInMinutes(Carbon::parse($log->time_in)) / 60
                : 0;
        });

        // Sum durations for this week
        $weekHours = TimeLog::whereBetween('time_in', [$startOfWeek, now()])
            ->whereHas('intern', function ($q) use ($adminId) {
                $q->where('invited_by_user_id', $adminId);
            })
            ->get()->sum(function ($log) {
            return ($log->time_in && $log->time_out)
                ? Carbon::parse($log->time_out)->diffInMinutes(Carbon::parse($log->time_in)) / 60
                : 0;
        });

        // Sum durations for this month
        $monthHours = TimeLog::whereBetween('time_in', [$startOfMonth, now()])
            ->whereHas('intern', function ($q) use ($adminId) {
                $q->where('invited_by_user_id', $adminId);
            })
            ->get()->sum(function ($log) {
            return ($log->time_in && $log->time_out)
                ? Carbon::parse($log->time_out)->diffInMinutes(Carbon::parse($log->time_in)) / 60
                : 0;
        });

        // Count working days (Mon–Sat) for current month
        $workingDaysThisMonth = Carbon::now()->diffInDaysFiltered(function ($date) {
            return !$date->isSunday(); // Mon–Sat only
        }, $startOfMonth);

        // Progress Calculations (capped at 100%)
        $todayProgress = $totalInterns > 0 ? min(100, round(($todayHours / ($totalInterns * 8)) * 100)) : 0;
        $weekProgress = $totalInterns > 0 ? min(100, round(($weekHours / ($totalInterns * 6 * 8)) * 100)) : 0;
        $monthProgress = ($totalInterns > 0 && $workingDaysThisMonth > 0)
            ? min(100, round(($monthHours / ($totalInterns * $workingDaysThisMonth * 8)) * 100))
            : 0;

        // ➕ Count "To Review" Submissions
        $interns = Intern::where('status', 'accepted')
            ->whereNull('archived_at')
            ->where('invited_by_user_id', $adminId)
            ->get();

        $requests = DocumentRequest::all()->groupBy('intern_id')->map->keyBy('type');
        $submissions = GradeSubmission::all()->groupBy('intern_id')->map->keyBy(function ($item) {
            $map = ['1st' => 'midterm', '2nd' => 'final', '3rd' => 'certificate', '4th' => 'evaluation'];
            return $map[$item->semester] ?? null;
        });

        $toReview = 0;

        foreach ($interns as $intern) {
            foreach (['midterm', 'final', 'certificate', 'evaluation'] as $type) {
                $isRequested = isset($requests[$intern->id][$type]);
                $isSubmitted = isset($submissions[$intern->id][$type]) &&
                               !empty($submissions[$intern->id][$type]->file_path);
                if ($isRequested && !$isSubmitted) {
                    $toReview++;
                }
            }
        }

        // ➕ Calculate percentage for circular chart
        $toReviewPercent = $acceptedCount > 0
            ? min(100, round(($toReview / ($acceptedCount * 4)) * 100))
            : 0;

        return view('dashboard', compact(
            'acceptedCount',
            'pendingCount',
            'messageCount',
            'unreadMessagesCount',
            'todayProgress',
            'weekProgress',
            'monthProgress',
            'toReview',
            'toReviewPercent'
        ));
    }

    /**
     * Get notification counts for popup notifications
     */
    public function getNotifications()
    {
        $adminId = Auth::id();
        
        // Pending acceptance
        $pendingAcceptance = Intern::where('status', 'pending')
            ->where('invited_by_user_id', $adminId)
            ->count();
        
        // Pending phase submissions
        $pendingPreDeployment = Intern::where('status', 'accepted')
            ->where('current_phase', 'pre_deployment')
            ->where(function($q) {
                $q->whereNull('pre_deployment_status')
                  ->orWhere('pre_deployment_status', 'pending');
            })
            ->where('invited_by_user_id', $adminId)
            ->count();
        
        $pendingMidDeployment = Intern::where('status', 'accepted')
            ->where('current_phase', 'mid_deployment')
            ->where(function($q) {
                $q->whereNull('mid_deployment_status')
                  ->orWhere('mid_deployment_status', 'pending');
            })
            ->where('invited_by_user_id', $adminId)
            ->count();
        
        $pendingDeployment = Intern::where('status', 'accepted')
            ->where('current_phase', 'deployment')
            ->where(function($q) {
                $q->whereNull('deployment_status')
                  ->orWhere('deployment_status', 'pending');
            })
            ->where('invited_by_user_id', $adminId)
            ->count();
        
        // Unread messages
        $unreadMessages = Message::where('receiver_id', $adminId)
            ->where('receiver_type', 'admin')
            ->where('sender_type', 'intern')
            ->where('is_read', false)
            ->count();
        
        // Pending grade submissions (files sent)
        $pendingGrades = GradeSubmission::whereHas('intern', function($q) use ($adminId) {
                $q->where('invited_by_user_id', $adminId);
            })
            ->whereNotNull('file_path')
            ->whereNull('reviewed_at')
            ->count();
        
        // New journal entries (submitted in last 24 hours)
        $newJournals = Document::where('type', 'journal')
            ->whereHas('intern', function($q) use ($adminId) {
                $q->where('invited_by_user_id', $adminId);
            })
            ->where('submitted_at', '>=', now()->subDay())
            ->count();
        
        // New documents (submitted in last 24 hours)
        $newDocuments = Document::where('type', '!=', 'journal')
            ->whereHas('intern', function($q) use ($adminId) {
                $q->where('invited_by_user_id', $adminId);
            })
            ->where('submitted_at', '>=', now()->subDay())
            ->count();
        
        return response()->json([
            'pending_acceptance' => $pendingAcceptance,
            'pending_pre_deployment' => $pendingPreDeployment,
            'pending_mid_deployment' => $pendingMidDeployment,
            'pending_deployment' => $pendingDeployment,
            'unread_messages' => $unreadMessages,
            'pending_grades' => $pendingGrades,
            'new_journals' => $newJournals,
            'new_documents' => $newDocuments
        ]);
    }

    public function interns(Request $request)
    {
        $filter = $request->get('filter');
        $phase = $request->get('phase', 'all');

        if ($phase && $phase !== 'all') {
            // Phase-focused view: list interns by current_phase regardless of status
            $query = Intern::query()->where('current_phase', $phase)
                ->where('invited_by_user_id', Auth::id());
        } else {
            // Default view: pending accounts awaiting admin approval
            $query = Intern::query()->where('status', 'pending')
                ->where('invited_by_user_id', Auth::id());
        }

        if ($filter && $filter !== 'all') {
            $query->where('section', $filter);
        }

        $interns = $query->select(
                'id',
                'first_name',
                'last_name',
                'course',
                'section',
                'status',
                'current_phase',
                'pre_enrollment_status',
                'pre_deployment_status',
                'mid_deployment_status',
                'deployment_status',
                'resume',
                'application_letter',
                'medical_certificate',
                'insurance',
                'acceptance_letter',
                'parents_waiver',
                'memorandum_of_agreement',
                'internship_contract',
                'recommendation_letter',
                'created_at'
            )
            ->orderBy('created_at', 'asc') // First come first serve
            ->orderBy('id', 'asc') // Secondary sort by ID for consistency
            ->paginate(10);

        $interns->appends($request->all());

        $sectionCounts = ($phase && $phase !== 'all')
            ? Intern::where('current_phase', $phase)
                ->where('invited_by_user_id', Auth::id())
                ->selectRaw('section, COUNT(*) as count')
                ->groupBy('section')
                ->pluck('count', 'section')
            : Intern::where('status', 'pending')
                ->where('invited_by_user_id', Auth::id())
                ->selectRaw('section, COUNT(*) as count')
                ->groupBy('section')
                ->pluck('count', 'section');

        // Phase counts across all interns (regardless of status)
        $phaseCounts = Intern::where('invited_by_user_id', Auth::id())
            ->selectRaw('current_phase, COUNT(*) as count')
            ->groupBy('current_phase')
            ->pluck('count', 'current_phase');

        return view('interns', compact('interns', 'sectionCounts', 'phaseCounts', 'filter', 'phase'));
    }

    public function generateInviteLink(Request $request)
    {
        $userId = auth()->id();
        $payload = [
            'invited_by' => $userId,
            'exp' => now()->addHours(12)->timestamp,
        ];
        $token = \Illuminate\Support\Facades\Crypt::encryptString(json_encode($payload));
        $loginPath = route('intern.login', [], false); // '/intern/login'
        $fullUrl = url($loginPath) . '?invite=' . urlencode($token);
        return response()->json(['link' => $fullUrl]);
    }

    public function documents(Request $request)
    {
        $filter = $request->input('filter');
        $search = $request->input('search');

        $query = Intern::where('status', 'accepted')
            ->where('current_phase', 'completed')
            ->whereNull('archived_at')
            ->where('invited_by_user_id', Auth::id())
            ->with(['timeLogs', 'documents', 'journals']);

        // Apply section filter
        if ($filter && $filter !== 'all') {
            $query->where('section', $filter);
        }

        // Apply search filter
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'LIKE', "%{$search}%")
                  ->orWhere('last_name', 'LIKE', "%{$search}%")
                  ->orWhere('section', 'LIKE', "%{$search}%");
            });
        }

        // Add pagination - 10 items per page
        $interns = $query->select(
                'id',
                'first_name',
                'last_name',
                'supervisor_name',
                'company_name',
                'section',
                'application_letter',
                'parents_waiver',
                'acceptance_letter'
            )
            ->orderBy('section')
            ->paginate(10);

        // Preserve query parameters in pagination links
        $interns->appends($request->all());

        // Get section counts for accepted (unarchived) interns with all phases completed
        $sectionCounts = Intern::where('status', 'accepted')
            ->where('current_phase', 'completed')
            ->whereNull('archived_at')
            ->where('invited_by_user_id', Auth::id())
            ->selectRaw('section, COUNT(*) as count')
            ->groupBy('section')
            ->pluck('count', 'section')
            ->toArray();

        return view('documents', compact('interns', 'sectionCounts', 'filter', 'search'));
    }

    public function documentsArchive(Request $request)
    {
        $search = $request->input('search');

        $query = Intern::whereNotNull('archived_at')
            ->where('invited_by_user_id', Auth::id());

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'LIKE', "%{$search}%")
                  ->orWhere('last_name', 'LIKE', "%{$search}%")
                  ->orWhere('section', 'LIKE', "%{$search}%");
            });
        }

        $archivedInterns = $query->select(
                'id', 'first_name', 'last_name', 'section',
                'application_letter', 'parents_waiver', 'acceptance_letter', 'archived_at'
            )
            ->orderBy('section')
            ->orderBy('last_name')
            ->get();

        return view('documents-archive', compact('archivedInterns', 'search'));
    }

    public function archiveIntern($id)
    {
        $intern = Intern::where('id', $id)
            ->where('invited_by_user_id', Auth::id())
            ->firstOrFail();
        $intern->archived_at = now();
        $intern->save();

        return redirect()->back()->with('success', 'Intern archived successfully.');
    }

    public function qr()
    {
        return view('qr');
    }

    public function messages()
    {
        $interns = Intern::where('status', 'accepted')
            ->where('current_phase', 'completed')
            ->where('invited_by_user_id', Auth::id())
            ->select('id', 'first_name', 'last_name', 'email')
            ->get();

        return view('messages', compact('interns'));
    }

    public function grades(Request $request)
    {
        $filter = $request->input('filter');
        $search = $request->input('search');

        $query = Intern::where('status', 'accepted')
            ->where('current_phase', 'completed')
            ->where('invited_by_user_id', Auth::id());

        // Apply section filter
        if ($filter && $filter !== 'all') {
            $query->where('section', $filter);
        }

        // Apply search filter
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'LIKE', "%{$search}%")
                  ->orWhere('last_name', 'LIKE', "%{$search}%")
                  ->orWhere('section', 'LIKE', "%{$search}%");
            });
        }

        $interns = $query->select('id', 'first_name', 'last_name', 'course', 'section')
            ->orderBy('section')
            ->get();

        $sectionCounts = Intern::where('status', 'accepted')
            ->where('current_phase', 'completed')
            ->where('invited_by_user_id', Auth::id())
            ->selectRaw('section, COUNT(*) as count')
            ->groupBy('section')
            ->pluck('count', 'section')
            ->toArray();

        $requests = DocumentRequest::all()->groupBy('intern_id')->map(function ($items) {
            return $items->keyBy('type');
        });

        $submissions = GradeSubmission::all()->groupBy('intern_id')->map(function ($items) {
            return $items->keyBy(function ($item) {
                $map = [
                    '3rd' => 'certificate',
                    '4th' => 'evaluation',
                ];
                return $map[$item->semester] ?? null;
            });
        });

        return view('grades', compact(
            'interns',
            'sectionCounts',
            'filter',
            'search',
            'requests',
            'submissions'
        ));
    }

    public function sendGradeRequest(Request $request)
    {
        // Basic validation; allow any string and normalize below
        $request->validate([
            'intern_id' => 'required|exists:interns,id',
            'type' => 'required|string',
        ]);

        // Normalize type (handles: Midterm, midterm, Certificate, Evaluation Form, etc.)
        $normalized = strtolower(str_replace(' ', '', $request->type));

        $typeMap = [
            'midterm' => 'midterm',
            'final' => 'final',
            'certificate' => 'certificate',
            'evaluationform' => 'evaluation',
            'evaluation' => 'evaluation',
        ];

        $type = $typeMap[$normalized] ?? null;

        if (!$type) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Invalid document type.'], 400);
            }
            return back()->with('error', 'Invalid document type.');
        }

        DocumentRequest::updateOrCreate(
            ['intern_id' => $request->intern_id, 'type' => $type],
            ['requested_at' => now()]
        );

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Document request sent successfully.']);
        }

        return redirect()->back()->with('success', 'Document request sent successfully.');
    }

    /**
     * Broadcast grade request to all interns
     */
    public function broadcastGradeRequest(Request $request)
    {
        $request->validate([
            'type' => 'required|string|in:certificate,evaluation',
        ]);

        $admin = Auth::user();
        
        // Get all accepted interns for this admin
        $interns = Intern::where('status', 'accepted')
            ->where('current_phase', 'completed')
            ->where('invited_by_user_id', $admin->id)
            ->get();

        if ($interns->isEmpty()) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No interns available to send requests to.'
                ], 400);
            }
            return back()->with('error', 'No interns available to send requests to.');
        }

        // Map type to document request type
        $typeMap = [
            'certificate' => 'certificate',
            'evaluation' => 'evaluation',
        ];

        $documentType = $typeMap[$request->type] ?? null;

        if (!$documentType) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid document type.'
                ], 400);
            }
            return back()->with('error', 'Invalid document type.');
        }

        // Create document requests for all interns
        $count = 0;
        foreach ($interns as $intern) {
            DocumentRequest::updateOrCreate(
                ['intern_id' => $intern->id, 'type' => $documentType],
                ['requested_at' => now()]
            );
            $count++;
        }

        // Send notification messages to all interns
        $documentName = $request->type === 'certificate' ? 'Certificate' : 'Evaluation Form';
        $messageContent = "📋 You have a new document request: {$documentName}. Please submit your {$documentName} through the Grades section.";

        foreach ($interns as $intern) {
            Message::create([
                'sender_id' => $admin->id,
                'receiver_id' => $intern->id,
                'sender_type' => 'admin',
                'receiver_type' => 'intern',
                'content' => $messageContent,
                'is_read' => false,
            ]);
        }

        $message = "Request for {$documentName} has been sent to {$count} intern(s) and they have been notified.";

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'count' => $count
            ]);
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * Download grade file (certificate or evaluation form)
     */
    public function downloadGradeFile($internId, $type)
    {
        // Verify the intern belongs to the authenticated admin
        $adminId = Auth::id();
        $intern = Intern::where('id', $internId)
            ->where('invited_by_user_id', $adminId)
            ->first();

        if (!$intern) {
            abort(403, 'Unauthorized access to this file.');
        }

        // Map type to semester
        $typeMap = [
            'certificate' => '3rd',
            'evaluation' => '4th',
        ];

        $semester = $typeMap[$type] ?? null;
        if (!$semester) {
            abort(404, 'Invalid document type.');
        }

        // Get the grade submission
        $submission = GradeSubmission::where('intern_id', $internId)
            ->where('semester', $semester)
            ->first();

        if (!$submission || empty($submission->file_path)) {
            abort(404, 'File not found.');
        }

        // Get the file path (stored as 'grades/filename.ext')
        $filePath = storage_path('app/public/' . $submission->file_path);

        if (!file_exists($filePath)) {
            abort(404, 'File not found on server.');
        }

        // Get the original filename
        $filename = basename($submission->file_path);
        
        // Get the intern's name for a better filename
        $internName = str_replace(' ', '_', $intern->first_name . '_' . $intern->last_name);
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        $downloadFilename = $internName . '_' . ucfirst($type) . '.' . $extension;

        // Force download with proper headers
        return response()->download($filePath, $downloadFilename, [
            'Content-Type' => 'application/octet-stream',
        ]);
    }

    public function deleteAllInterns()
    {
        $adminId = Auth::id();
        DB::transaction(function () use ($adminId) {
            $internIds = Intern::where('invited_by_user_id', $adminId)->pluck('id');

            TimeLog::whereIn('intern_id', $internIds)->delete();
            Journal::whereIn('intern_id', $internIds)->delete();
            Document::whereIn('intern_id', $internIds)->delete();
            GradeSubmission::whereIn('intern_id', $internIds)->delete();
            Message::whereIn('sender_id', $internIds)->where('sender_type', 'intern')->delete();
            Message::whereIn('receiver_id', $internIds)->where('receiver_type', 'intern')->delete();
            DocumentRequest::whereIn('intern_id', $internIds)->delete();
            Intern::whereIn('id', $internIds)->delete();
        });

        return redirect()->back()->with('success', 'All interns and their related data have been deleted.');
    }

    /**
     * Delete a single intern and their related data/files.
     */
    public function destroyIntern($id)
    {
        $adminId = Auth::id();
        DB::transaction(function () use ($id, $adminId) {
            $intern = Intern::where('id', $id)->where('invited_by_user_id', $adminId)->firstOrFail();
            
            // Delete all related data
            TimeLog::where('intern_id', $id)->delete();
            Journal::where('intern_id', $id)->delete();
            Document::where('intern_id', $id)->delete();
            GradeSubmission::where('intern_id', $id)->delete();
            Message::where('sender_id', $id)->where('sender_type', 'intern')->delete();
            Message::where('receiver_id', $id)->where('receiver_type', 'intern')->delete();
            DocumentRequest::where('intern_id', $id)->delete();
            
            // Delete documents from storage if exist
            if ($intern->application_letter) {
                Storage::disk('public')->delete($intern->application_letter);
            }
            if ($intern->parents_waiver) {
                Storage::disk('public')->delete($intern->parents_waiver);
            }
            if ($intern->acceptance_letter) {
                Storage::disk('public')->delete($intern->acceptance_letter);
            }
            
            // Finally delete the intern
            $intern->delete();
        });

        return redirect()->back()->with('success', 'Intern and all related data deleted successfully.');
    }

    public function index()
    {
        $dtrs = Dtr::with('intern')->get();
        return view('documents', compact('dtrs'));
    }

    /**
     * Export database to SQL file
     *
     * NOTE: To avoid server 500 errors on shared hosting / XAMPP where `exec`
     * is often disabled, this implementation:
     * - If `ojt.sql` exists in the project root, serves that file as the export.
     * - Otherwise, for SQLite, copies the SQLite DB file.
     * - For MySQL without `ojt.sql`, shows a clear message telling the admin
     *   to generate an export via phpMyAdmin and place it as `ojt.sql`.
     */
    public function exportDatabase()
    {
        try {
            $connection = config('database.default');
            $config = config("database.connections.{$connection}");

            $filename = 'ojt_backup_' . date('Y-m-d_H-i-s') . '.sql';

            // 1) Prefer an existing dump file in the project root (ojt.sql)
            $existingDump = base_path('ojt.sql');
            if (file_exists($existingDump)) {
                return response()->download($existingDump, $filename, [
                    'Content-Type' => 'application/sql',
                ]);
            }

            // 2) If using SQLite, copy the SQLite DB file as the export
            if ($connection === 'sqlite') {
                $dbPath = $config['database'];
                if (!file_exists($dbPath)) {
                    throw new \Exception('SQLite database file not found at: ' . $dbPath);
                }

                $backupPath = storage_path('app/backups');
                if (!file_exists($backupPath)) {
                    mkdir($backupPath, 0755, true);
                }

                $filePath = $backupPath . '/' . $filename;
                copy($dbPath, $filePath);

                // Return download response
                return response()->download($filePath, $filename, [
                    'Content-Type' => 'application/octet-stream',
                ])->deleteFileAfterSend(false);
            }

            // 3) For MySQL without exec access, guide the admin
            if ($connection === 'mysql') {
                throw new \Exception(
                    'Automatic MySQL export is not available on this server. ' .
                    'Please export the database via phpMyAdmin (or MySQL Workbench), ' .
                    'save it as "ojt.sql" in the project root, and click Export Database again.'
                );
            }

            throw new \Exception('Unsupported database connection type: ' . $connection);
        } catch (\Exception $e) {
            \Log::error('Database export failed: ' . $e->getMessage());
            return redirect()->route('grades')->with('error', 'Database export failed: ' . $e->getMessage());
        }
    }

    /**
     * Clean up old backup files (keep only last 24)
     */
    private function cleanupOldBackups($backupPath)
    {
        $files = glob($backupPath . '/ojt_backup_*.sql');
        
        if (count($files) > 24) {
            // Sort by modification time
            usort($files, function($a, $b) {
                return filemtime($a) - filemtime($b);
            });
            
            // Delete oldest files
            $filesToDelete = array_slice($files, 0, count($files) - 24);
            foreach ($filesToDelete as $file) {
                @unlink($file);
            }
        }
    }
}