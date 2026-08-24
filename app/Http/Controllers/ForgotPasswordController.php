<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Models\Otp;
use App\Models\User;
use App\Models\ClientUser;
use App\Mail\SendOtpMail;
use Carbon\Carbon;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    public function showEmailForm()
    {
        return view('auth.forgot-password');
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $email = $request->email;

        // Check if email exists in Admin or Client
        $isAdmin = User::where('email', $email)->exists();
        $isClient = ClientUser::where('email', $email)->exists();

        if (!$isAdmin && !$isClient) {
            return back()->with('error', 'We could not find an account with that email address.');
        }

        // Generate 6-digit OTP
        $otpCode = str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);

        // Store or update OTP
        Otp::updateOrCreate(
            ['email' => $email],
            [
                'otp_code' => $otpCode,
                'expires_at' => Carbon::now()->addMinutes(10)
            ]
        );

        // Send OTP via Email
        try {
            Mail::to($email)->send(new SendOtpMail($otpCode));
            // Redirect to OTP verification page with email in session
            session(['reset_email' => $email]);
            return redirect()->route('password.verify.form')->with('success', 'OTP has been sent to your email.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to send OTP email: ' . $e->getMessage());
            return back()->with('error', 'Failed to send OTP email. Please check SMTP configuration.');
        }
    }

    public function showOtpForm()
    {
        if (!session()->has('reset_email')) {
            return redirect()->route('password.request');
        }
        return view('auth.verify-otp');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|numeric|digits:6'
        ]);

        $email = session('reset_email');
        if (!$email) {
            return redirect()->route('password.request');
        }

        $otpRecord = Otp::where('email', $email)->first();

        if (!$otpRecord) {
            return back()->with('error', 'Invalid request. Please try again.');
        }

        if ($otpRecord->otp_code !== $request->otp) {
            return back()->with('error', 'The OTP is incorrect.');
        }

        if (Carbon::now()->greaterThan($otpRecord->expires_at)) {
            return back()->with('error', 'The OTP has expired. Please request a new one.');
        }

        // OTP is valid, proceed to reset password form
        session(['otp_verified' => true]);
        return redirect()->route('password.reset.form')->with('success', 'OTP verified. You may now reset your password.');
    }

    public function showResetForm()
    {
        if (!session()->has('reset_email') || !session()->has('otp_verified')) {
            return redirect()->route('password.request');
        }
        return view('auth.reset-password');
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|min:8|confirmed'
        ]);

        $email = session('reset_email');
        if (!$email || !session()->has('otp_verified')) {
            return redirect()->route('password.request');
        }

        $isAdmin = User::where('email', $email)->first();
        if ($isAdmin) {
            $isAdmin->password = Hash::make($request->password);
            $isAdmin->save();
        }

        $isClient = ClientUser::where('email', $email)->first();
        if ($isClient) {
            $isClient->password = Hash::make($request->password);
            $isClient->save();
        }

        // Cleanup
        Otp::where('email', $email)->delete();
        session()->forget(['reset_email', 'otp_verified']);

        return redirect()->route('login')->with('success', 'Your password has been reset successfully. You can now log in.');
    }
}
