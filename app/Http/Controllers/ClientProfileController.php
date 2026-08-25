<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Client;

class ClientProfileController extends Controller
{
    public function show()
    {
        $client = Auth::guard('client')->user()->client;
        return view('client.profile', compact('client'));
    }

    public function update(Request $request)
    {
        $user = Auth::guard('client')->user();
        $client = $user->client;

        $request->validate([
            'client_company' => 'required|string|max:100',
            'industry' => 'nullable|string|max:255',
            'company_size' => 'nullable|string|max:100',
            'website' => 'nullable|string|max:255',
            'client_gst' => 'nullable|string|max:30',
            'client_name' => 'required|string|max:50',
            'client_email' => 'required|email|max:50|unique:client_users,email,'.$user->id,
            'primary_contact' => 'required|string|max:11',
            'secondary_contact' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
        ]);

        $data = $request->only([
            'client_company', 'industry', 'company_size', 'website', 'client_gst',
            'client_name', 'client_email',
            'primary_contact', 'secondary_contact', 'city', 'state', 'country'
        ]);

        // Default empty strings for nullable non-null DB columns
        foreach (['client_gst', 'industry', 'company_size', 'website', 'secondary_contact', 'city', 'state', 'country'] as $field) {
            if (!isset($data[$field]) || is_null($data[$field])) {
                $data[$field] = '';
            }
        }

        $client->update($data);

        // Sync name and email with login account
        $user->update([
            'name' => $data['client_name'],
            'email' => $data['client_email'],
        ]);

        return back()->with('success', 'Profile and company information updated successfully.');
    }
    public function settings()
    {
        return view('client.settings');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::guard('client')->user();

        if (!\Illuminate\Support\Facades\Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Current password does not match!');
        }

        $user->update([
            'password' => \Illuminate\Support\Facades\Hash::make($request->new_password)
        ]);

        // Send confirmation email
        try {
            \Illuminate\Support\Facades\Mail::to($user->email)
                ->send(new \App\Mail\PasswordChangedMail($user));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to send password changed confirmation email: ' . $e->getMessage());
        }

        return back()->with('success', 'Password updated successfully and confirmation email sent.');
    }
}
