<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Models\LoginAttempt;
use App\Mail\AdminPasswordResetMail;
use App\Mail\OtpMail;
use Carbon\Carbon;

class AdminController extends Controller
{
    /**
     * Check if admin exists
     */
    public function checkAdminExists()
    {
        $adminExists = User::where('role', 'admin')->exists();
        
        return response()->json([
            'adminExists' => $adminExists
        ]);
    }

    /**
     * Handle admin login
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8'
        ]);

        $email = $request->email;
        $password = $request->password;
        $ipAddress = $request->ip();
        $userAgent = $request->userAgent();

        // Get user location data
        $locationData = $this->getLocationData($ipAddress);

        // Check if user is admin
        $user = User::where('email', $email)->where('role', 'admin')->first();

        if (!$user) {
            // Log failed attempt
            $this->logLoginAttempt($email, $ipAddress, $userAgent, false, 'Admin not found', $locationData);
            
            return response()->json([
                'success' => false,
                'message' => 'Invalid admin credentials.'
            ], 401);
        }

        // Verify password
        if (!Hash::check($password, $user->password)) {
            // Log failed attempt
            $this->logLoginAttempt($email, $ipAddress, $userAgent, false, 'Invalid password', $locationData);
            
            return response()->json([
                'success' => false,
                'message' => 'Invalid admin credentials.'
            ], 401);
        }

        // Log successful attempt
        $this->logLoginAttempt($email, $ipAddress, $userAgent, true, 'Login successful', $locationData);

        // Login the user
        Auth::login($user);

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'redirect_url' => route('admin.dashboard')
        ]);
    }

    /**
     * Handle admin registration
     */
    public function register(Request $request)
    {
        // Check if admin already exists
        $adminExists = User::where('role', 'admin')->exists();
        
        if ($adminExists) {
            return response()->json([
                'success' => false,
                'message' => 'Admin account already exists. Only one admin account is allowed.'
            ], 400);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed'
        ]);

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'admin',
                'email_verified_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Admin account created successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create admin account: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Handle admin forgot password
     */
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $user = User::where('email', $request->email)->where('role', 'admin')->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Admin account not found.'
            ], 404);
        }

        try {
            // Generate 6-digit OTP and set expiry
            $otp = str_pad((string)random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            $user->otp_code = $otp;
            $user->otp_expires_at = now()->addMinutes(10);
            $user->otp_verified = false;
            $user->save();

            // Send OTP email using existing mailable
            try {
                Mail::to($user->email)->send(new OtpMail($otp));
            } catch (\Throwable $e) {
                // Continue but include fallback OTP in response for non-email environments
                return response()->json([
                    'success' => true,
                    'message' => 'OTP generated.',
                    'otp_fallback' => $otp
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'OTP sent to your email.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate OTP: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verify admin forgot password OTP (no password change here, just identity verification)
     */
    public function verifyForgotOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|digits:6',
        ]);

        $user = User::where('email', $request->email)
            ->where('role', 'admin')
            ->first();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Admin account not found.'], 404);
        }

        if (!$user->otp_code || !$user->otp_expires_at || now()->greaterThan($user->otp_expires_at)) {
            return response()->json(['success' => false, 'message' => 'OTP has expired. Please request a new code.'], 400);
        }

        if (hash_equals($user->otp_code, $request->otp)) {
            $user->otp_verified = true;
            // Optionally clear OTP immediately to prevent reuse
            $user->otp_code = null;
            $user->otp_expires_at = null;
            $user->save();

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Invalid OTP code.'], 400);
    }

    /**
     * Show admin dashboard
     */
    public function dashboard()
    {
        // Get login attempts grouped by IP address
        $loginAttempts = LoginAttempt::select('ip_address', 'city', 'country', 'latitude', 'longitude')
            ->selectRaw('COUNT(*) as total_attempts')
            ->selectRaw('SUM(CASE WHEN success = 1 THEN 1 ELSE 0 END) as successful_attempts')
            ->selectRaw('SUM(CASE WHEN success = 0 THEN 1 ELSE 0 END) as failed_attempts')
            ->selectRaw('GROUP_CONCAT(DISTINCT email) as emails')
            ->selectRaw('MAX(created_at) as last_attempt')
            ->groupBy('ip_address', 'city', 'country', 'latitude', 'longitude')
            ->orderBy('last_attempt', 'desc')
            ->get();

        return view('admin-dashboard', compact('loginAttempts'));
    }

    /**
     * Get detailed login attempts for an IP
     */
    public function getIpDetails($ipAddress)
    {
        $attempts = LoginAttempt::where('ip_address', $ipAddress)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'attempts' => $attempts
        ]);
    }

    /**
     * Log login attempt
     */
    private function logLoginAttempt($email, $ipAddress, $userAgent, $success, $reason, $locationData = null)
    {
        try {
            LoginAttempt::create([
                'email' => $email,
                'ip_address' => $ipAddress,
                'user_agent' => $userAgent,
                'success' => $success,
                'reason' => $reason,
                'city' => $locationData['city'] ?? null,
                'country' => $locationData['country'] ?? null,
                'latitude' => $locationData['latitude'] ?? null,
                'longitude' => $locationData['longitude'] ?? null,
                'created_at' => now()
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to log login attempt: ' . $e->getMessage());
        }
    }

    /**
     * Get location data from IP address
     */
    private function getLocationData($ipAddress)
    {
        try {
            // Skip for localhost/private IPs
            if ($ipAddress === '127.0.0.1' || $ipAddress === '::1' || strpos($ipAddress, '192.168.') === 0 || strpos($ipAddress, '10.') === 0) {
                return [
                    'city' => 'Local',
                    'country' => 'Local',
                    'latitude' => 0,
                    'longitude' => 0
                ];
            }

            // Use ipapi.co for IP geolocation (free tier: 1000 requests/day)
            $response = file_get_contents("http://ipapi.co/{$ipAddress}/json/");
            $data = json_decode($response, true);

            if ($data && !isset($data['error'])) {
                return [
                    'city' => $data['city'] ?? 'Unknown',
                    'country' => $data['country_name'] ?? 'Unknown',
                    'latitude' => $data['latitude'] ?? 0,
                    'longitude' => $data['longitude'] ?? 0
                ];
            }
        } catch (\Exception $e) {
            \Log::error('Failed to get location data: ' . $e->getMessage());
        }

        return [
            'city' => 'Unknown',
            'country' => 'Unknown',
            'latitude' => 0,
            'longitude' => 0
        ];
    }
}