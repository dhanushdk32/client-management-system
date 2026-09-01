<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Models\StaffMember;
use App\Models\ClientUser;
use App\Models\Otp;
use Carbon\Carbon;

class AuthController extends Controller
{
    /* =========================================================================
       CLIENT AUTHENTICATION
       ========================================================================= */
    public function showClientLogin()
    {
        if (Auth::guard('client')->check()) {
            return redirect()->route('client.dashboard');
        }
        if (Auth::guard('staff')->check()) {
            return redirect()->route('staff.dashboard');
        }
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }
        return view('auth.client-login');
    }

    public function clientLogin(Request $request)
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

        // Check if account exists and status
        $clientUser = ClientUser::where('email', $request->email)->first();
        if ($clientUser && in_array(strtolower($clientUser->status), ['pending activation', 'pending'])) {
            return redirect()->route('account.activate', ['email' => $request->email])
                ->with('error', 'Your account is pending activation. Please verify your OTP to set a password.');
        }

        if (Auth::guard('client')->attempt($credentials, $remember)) {
            $request->session()->regenerate();
            return redirect()->route('client.dashboard');
        }

        return redirect()->route('client.login')->withErrors([
            'email' => 'Incorrect client email or password.',
        ])->onlyInput('email');
    }

    /* =========================================================================
       STAFF AUTHENTICATION
       ========================================================================= */
    public function showStaffLogin()
    {
        if (Auth::guard('staff')->check()) {
            return redirect()->route('staff.dashboard');
        }
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }
        if (Auth::guard('client')->check()) {
            return redirect()->route('client.dashboard');
        }
        return view('auth.staff-login');
    }

    public function staffLogin(Request $request)
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

        $staff = StaffMember::where('email', $request->email)->first();
        if ($staff && $staff->status === 'Pending Activation') {
            return redirect()->route('account.activate', ['email' => $request->email])
                ->with('error', 'Your staff account is pending activation. Please verify your OTP to create a password.');
        }

        if ($staff && $staff->status === 'Inactive') {
            return redirect()->route('staff.login')->withErrors([
                'email' => 'This staff account has been deactivated. Please contact an administrator.',
            ])->onlyInput('email');
        }

        if (Auth::guard('staff')->attempt($credentials, $remember)) {
            $request->session()->regenerate();
            Auth::guard('staff')->user()->update(['last_login_at' => now()]);
            return redirect()->route('staff.dashboard');
        }

        return redirect()->route('staff.login')->withErrors([
            'email' => 'Incorrect staff email or password.',
        ])->onlyInput('email');
    }

    /* =========================================================================
       ADMIN AUTHENTICATION
       ========================================================================= */
    public function showAdminLogin()
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
        return view('auth.admin-login');
    }

    public function adminLogin(Request $request)
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

        if (Auth::guard('admin')->attempt($credentials, $remember)) {
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('admin.login')->withErrors([
            'email' => 'Incorrect admin credentials or password.',
        ])->onlyInput('email');
    }

    /* =========================================================================
       LOGOUT (GUARD AWARE)
       ========================================================================= */
    public function logout(Request $request)
    {
        $redirectRoute = 'client.login';

        if (Auth::guard('admin')->check()) {
            Auth::guard('admin')->logout();
            $redirectRoute = 'admin.login';
        } elseif (Auth::guard('staff')->check()) {
            Auth::guard('staff')->logout();
            $redirectRoute = 'staff.login';
        } elseif (Auth::guard('client')->check()) {
            Auth::guard('client')->logout();
            $redirectRoute = 'client.login';
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route($redirectRoute);
    }

    /* =========================================================================
       OTP ACTIVATION & PASSWORD CREATION (FOR BOTH STAFF & CLIENTS)
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

        // Store in session for password creation step
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
        $loginUrl = route('client.login');
        $userName = '';

        // Check if Staff Member
        $staff = StaffMember::where('email', $email)->first();
        if ($staff) {
            $staff->update([
                'password' => Hash::make($request->password),
                'status' => 'Active',
            ]);
            $accountType = 'Staff Team Member (' . $staff->designation . ')';
            $loginUrl = route('staff.login');
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
            $loginUrl = route('client.login');
            $userName = $clientUser->name;
        }

        // Send confirmation email
        try {
            Mail::to($email)->send(new \App\Mail\PasswordSetupSuccessMail(
                $userName ?: 'Portal User',
                $email,
                $loginUrl,
                $accountType
            ));
        } catch (\Exception $e) {
            Log::error('Failed to send activation success email: ' . $e->getMessage());
        }

        // Cleanup OTP and session
        Otp::where('email', $email)->delete();
        session()->forget(['activation_email', 'activation_verified']);

        return redirect($loginUrl)->with('success', 'Your account has been activated and your password is set! You can now log in.');
    }
}
