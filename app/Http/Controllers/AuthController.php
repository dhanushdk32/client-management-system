<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Models\PortalAdmin;
use App\Models\StaffMember;
use App\Models\ClientUser;
use App\Models\Otp;
use Carbon\Carbon;

class AuthController extends Controller
{
    /* =========================================================================
       UNIFIED SINGLE LOGIN
       ========================================================================= */
    public function showLogin()
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }
        if (Auth::guard('staff')->check()) {
            return redirect()->route('staff.dashboard');
        }
        if (Auth::guard('client')->check()) {
            return redirect()->route('client.dashboard');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];
        $remember = $request->has('remember');

        // 1. Try Admin Login
        $admin = PortalAdmin::where('email', $request->email)->first();
        if ($admin) {
            if (Auth::guard('admin')->attempt($credentials, $remember)) {
                $request->session()->regenerate();
                return redirect()->intended(route('admin.dashboard'));
            }
        }

        // 2. Try Staff Member Login
        $staff = StaffMember::where('email', $request->email)->first();
        if ($staff) {
            if ($staff->status === 'Inactive') {
                return back()->withErrors([
                    'email' => 'This staff account has been deactivated. Please contact an administrator.',
                ])->onlyInput('email');
            }
            if ($staff->status === 'Pending Activation') {
                return redirect()->route('account.activate', ['email' => $request->email])
                    ->with('error', 'Your staff account is pending activation. Please verify your OTP to create a password.');
            }
            if (Auth::guard('staff')->attempt($credentials, $remember)) {
                $request->session()->regenerate();
                $staff->update(['last_login_at' => now()]);
                return redirect()->intended(route('staff.dashboard'));
            }
        }

        // 3. Try Client User Login
        $clientUser = ClientUser::where('email', $request->email)->first();
        if ($clientUser) {
            if (in_array(strtolower($clientUser->status), ['inactive', 'suspended'])) {
                return back()->withErrors([
                    'email' => 'This client account is inactive. Please contact your account manager.',
                ])->onlyInput('email');
            }
            if (in_array(strtolower($clientUser->status), ['pending activation', 'pending'])) {
                return redirect()->route('account.activate', ['email' => $request->email])
                    ->with('error', 'Your client account is pending activation. Please verify your OTP to set a password.');
            }
            if (Auth::guard('client')->attempt($credentials, $remember)) {
                $request->session()->regenerate();
                return redirect()->intended(route('client.dashboard'));
            }
        }

        // Generic failure message
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /* =========================================================================
       LOGOUT (ALL GUARDS)
       ========================================================================= */
    public function logout(Request $request)
    {
        if (Auth::guard('admin')->check()) {
            Auth::guard('admin')->logout();
        }
        if (Auth::guard('staff')->check()) {
            Auth::guard('staff')->logout();
        }
        if (Auth::guard('client')->check()) {
            Auth::guard('client')->logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'You have been safely logged out.');
    }

    /* =========================================================================
       COMPATIBILITY ALIASES (FOR LEGACY / SPECIFIC LINKS)
       ========================================================================= */
    public function showClientLogin() { return redirect()->route('login'); }
    public function showStaffLogin() { return redirect()->route('login'); }
    public function showAdminLogin() { return redirect()->route('login'); }
    public function clientLogin(Request $request) { return $this->login($request); }
    public function staffLogin(Request $request) { return $this->login($request); }
    public function adminLogin(Request $request) { return $this->login($request); }

    /* =========================================================================
       OTP ACTIVATION & PASSWORD CREATION
       ========================================================================= */
    public function showActivationForm(Request $request)
    {
        return view('auth.activate-account');
    }

    public function verifyActivationOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|string|size:6',
        ]);

        $otpRecord = Otp::where('email', $request->email)->first();

        if (!$otpRecord) {
            return back()->with('error', 'No active activation request found for this email.');
        }

        if ($otpRecord->otp_code !== $request->otp) {
            return back()->with('error', 'The activation OTP code is incorrect.');
        }

        if (Carbon::now()->greaterThan($otpRecord->expires_at)) {
            return back()->with('error', 'This activation OTP code has expired. Please request a new one.');
        }

        session([
            'activation_email' => $request->email,
            'activation_verified' => true,
        ]);

        return redirect()->route('account.activate.set-password')
            ->with('success', 'OTP verified successfully! Please set your account password.');
    }

    public function showSetPasswordForm()
    {
        if (!session('activation_verified') || !session('activation_email')) {
            return redirect()->route('account.activate')->with('error', 'Please enter your email and OTP first.');
        }

        return view('auth.activate-set-password');
    }

    public function saveActivatedPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|min:8|confirmed',
        ]);

        $email = session('activation_email');
        if (!$email || !session('activation_verified')) {
            return redirect()->route('account.activate')->with('error', 'Session expired. Please start over.');
        }

        $accountType = 'User';
        $userName = '';

        // Check if Staff Member
        $staff = StaffMember::where('email', $email)->first();
        if ($staff) {
            $staff->update([
                'password' => Hash::make($request->password),
                'status' => 'Active',
            ]);
            $accountType = 'Staff Team Member (' . $staff->designation . ')';
            $userName = $staff->name;
        }

        // Check if Client User
        $clientUser = ClientUser::where('email', $email)->first();
        if ($clientUser) {
            $clientUser->update([
                'password' => Hash::make($request->password),
                'status' => 'Active',
            ]);
            $accountType = 'Client Account (' . ($clientUser->client->client_company ?? 'Client Portal') . ')';
            $userName = $clientUser->name;
        }

        // Send confirmation email
        try {
            Mail::to($email)->send(new \App\Mail\PasswordSetupSuccessMail(
                $userName ?: 'Portal User',
                $email,
                route('login'),
                $accountType
            ));
        } catch (\Exception $e) {
            Log::error('Failed to send activation success email: ' . $e->getMessage());
        }

        // Cleanup OTP and session
        Otp::where('email', $email)->delete();
        session()->forget(['activation_email', 'activation_verified']);

        return redirect()->route('login')->with('success', 'Your account has been activated and your password is set! You can now log in.');
    }
}
