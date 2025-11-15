<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Models\Intern;
use App\Models\TimeLog;
use App\Models\Message;
use App\Models\GradeSubmission;
use App\Models\DocumentRequest;
use App\Mail\InternOtpMail;
use Carbon\Carbon;

class InternAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('intern-login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $intern = Intern::where('email', $request->email)->first();

        if (!$intern || !\Hash::check($request->password, $intern->password)) {
            return back()->with('error', 'Invalid intern credentials.');
        }

        // Must be accepted by admin before login
        if ($intern->status !== 'accepted') {
            return back()->with('error', "Please wait for the Admin's Approval.");
        }

        // Generate and send OTP for 2FA (every login requires OTP)
        $otp = str_pad((string)random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $intern->otp_code = $otp;
        $intern->otp_expires_at = now()->addMinutes(10);
        $intern->otp_verified = false; // Reset OTP verification status
        $intern->save();

        // Store intern ID in session for OTP verification
        $request->session()->put('pending_login_intern_id', $intern->id);

        $mailFailed = false;
        try {
            Mail::to($intern->email)->send(new InternOtpMail($otp));
            \Log::info('2FA OTP email sent successfully to intern: ' . $intern->email);
        } catch (\Throwable $e) {
            $mailFailed = true;
            \Log::error('Failed to send 2FA OTP email to intern: ' . $e->getMessage());
            \Log::error('Email sending error details: ' . $e->getTraceAsString());
        }

        $redirect = redirect()->route('intern.login')
            ->with('success', 'Credentials verified! Please enter the 6-digit code sent to your email to complete login.')
            ->with('otp_email', $intern->email)
            ->with('login_2fa', true);

        if ($mailFailed) {
            $redirect->with('otp_code_fallback', $otp);
        }

        return $redirect;
    }

    public function logout()
    {
        Auth::guard('intern')->logout();
        return redirect()->route('intern.login');
    }

    public function dashboard()
    {
        $intern = Auth::guard('intern')->user();

        // Calculate total OJT hours from TimeLog
        $logs = TimeLog::where('intern_id', $intern->id)->get();
        $totalSeconds = 0;

        foreach ($logs as $log) {
            $in = $log->time_in ? Carbon::parse($log->date . ' ' . $log->time_in, 'Asia/Manila') : null;
            $out = $log->time_out ? Carbon::parse($log->date . ' ' . $log->time_out, 'Asia/Manila') : null;

            if ($in && !$out) {
                $out = Carbon::parse($log->date . ' 17:00:00', 'Asia/Manila');
            }

            if ($in && $out) {
                $totalSeconds += $in->diffInSeconds($out);
            }
        }

        $totalHours = round($totalSeconds / 3600, 2);
        $remainingHours = max(0, 486 - $totalHours);
        $progressPercent = min(100, ($totalHours / 486) * 100);

        // Count unread messages from admin
        $unreadMessages = Message::where('receiver_id', $intern->id)
            ->where('receiver_type', 'intern')
            ->where('sender_type', 'admin')
            ->where('is_read', false)
            ->count();

        // Get pending document requests
        $pendingRequests = DocumentRequest::where('intern_id', $intern->id)
            ->pluck('type')
            ->toArray();

        // Check if it's Friday and intern hasn't submitted journal this week
        $isFriday = now('Asia/Manila')->isFriday();
        $hasSubmittedJournalThisWeek = $intern->hasSubmittedJournalThisWeek();

        // Check if end of month is approaching
        $daysUntilEndOfMonth = now('Asia/Manila')->endOfMonth()->diffInDays(now('Asia/Manila'));

        return view('intern-dashboard', compact(
            'intern',
            'totalHours',
            'remainingHours',
            'progressPercent',
            'unreadMessages',
            'pendingRequests',
            'isFriday',
            'hasSubmittedJournalThisWeek',
            'daysUntilEndOfMonth'
        ));
    }

    public function endorsement()
    {
        $intern = Auth::guard('intern')->user();

        $data = [
            'supervisor_name' => $intern->supervisor_name,
            'supervisor_position' => $intern->supervisor_position,
            'company_name' => $intern->company_name,
            'company_address' => $intern->company_address,
            'interns' => [ $intern->first_name . ' ' . $intern->last_name ],
            'sentAt' => now('Asia/Manila'),
        ];

        return view('Endorsement', $data);
    }

    public function acceptanceLetter()
    {
        $intern = Auth::guard('intern')->user();

        return view('Acceptance-Letter', [
            'intern' => $intern,
            'today' => now('Asia/Manila'),
        ]);
    }

    public function memorandum()
    {
        $intern = Auth::guard('intern')->user();

        return view('memorandum', [
            'intern' => $intern,
            'today' => now('Asia/Manila'),
        ]);
    }

    public function internshipContract()
    {
        $intern = Auth::guard('intern')->user();

        return view('internship-contract', [
            'intern' => $intern,
            'today' => now('Asia/Manila'),
        ]);
    }

    public function showSendDataForm()
    {
        $intern = Auth::guard('intern')->user();

        $requests = DocumentRequest::where('intern_id', $intern->id)
            ->pluck('type')
            ->toArray();

        return view('send-data', compact('intern', 'requests'));
    }

    public function phaseSubmission()
    {
        $intern = Auth::guard('intern')->user();
        return view('phase-submission', compact('intern'));
    }

    /**
     * API endpoint to check current phase status for auto-refresh
     */
    public function checkPhaseStatus()
    {
        $intern = Auth::guard('intern')->user();
        
        return response()->json([
            'current_phase' => $intern->current_phase,
            'pre_deployment_status' => $intern->pre_deployment_status,
            'mid_deployment_status' => $intern->mid_deployment_status,
            'deployment_status' => $intern->deployment_status,
            'status' => $intern->status,
        ]);
    }

    public function uploadDocx(Request $request)
    {
        try {
            $request->validate([
                'semester'   => 'required|in:1st,2nd,3rd,4th',
                'grade_doc'  => 'required|file|mimes:doc,docx|max:10240',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
        }

        $intern = Auth::guard('intern')->user();

        // Store file in storage/app/public/grades
        $file = $request->file('grade_doc');
        $filename = now()->format('YmdHis') . "_intern{$intern->id}." . $file->getClientOriginalExtension();
        $path = $file->storeAs('grades', $filename, 'public');

        // Save or update grade submission
        GradeSubmission::updateOrCreate(
            ['intern_id' => $intern->id, 'semester' => $request->semester],
            ['file_path' => $path, 'submitted_at' => now()]
        );

        // Map semester to request type
        $typeMap = [
            '1st' => 'midterm',
            '2nd' => 'final',
            '3rd' => 'certificate',
            '4th' => 'evaluation',
        ];

        $matchedType = $typeMap[$request->semester] ?? null;

        if ($matchedType) {
            DocumentRequest::where('intern_id', $intern->id)
                ->where('type', $matchedType)
                ->delete();
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => 'File successfully uploaded.']);
        }

        return redirect()->route('intern.dashboard')->with('success', 'File successfully uploaded.');
    }

    /**
     * Show the Daily Time Record page for the logged-in intern.
     */
    public function dtr()
    {
        $intern = Auth::guard('intern')->user();

        // Fetch all time logs for this intern
        $logs = TimeLog::where('intern_id', $intern->id)
            ->orderBy('date', 'desc')
            ->get();

        return view('intern-dashboard', compact('intern', 'logs'));
    }
}
