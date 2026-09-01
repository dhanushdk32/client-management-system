<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class StaffProfileController extends Controller
{
    public function settings()
    {
        $staff = Auth::guard('staff')->user();
        return view('staff.settings', compact('staff'));
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $staff = Auth::guard('staff')->user();

        if (!Hash::check($request->current_password, $staff->password)) {
            return back()->with('error', 'Current password does not match!');
        }

        $staff->update([
            'password' => Hash::make($request->new_password)
        ]);

        // Send confirmation email
        try {
            Mail::to($staff->email)->send(new \App\Mail\PasswordChangedMail($staff));
        } catch (\Exception $e) {
            Log::error('Failed to send staff password changed confirmation email: ' . $e->getMessage());
        }

        return back()->with('success', 'Password updated successfully and confirmation email sent.');
    }
}
