<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Client;
use App\Models\StaffMember;

class ClientProfileController extends Controller
{
    public function show()
    {
        $user = Auth::guard('client')->user();
        $client = $user->client;
        $client->load(['services.teamLeader', 'assignedStaff']);
        $primaryService = $client->services->first();
        $allStaff = StaffMember::pluck('name', 'id')->toArray();

        return view('client.profile', compact('client', 'primaryService', 'allStaff'));
    }

    public function update(Request $request)
    {
        $user = Auth::guard('client')->user();
        $client = $user->client;

        $request->validate([
            'client_name' => 'required|string|max:100|regex:/^[a-zA-Z\s\.\'-]+$/',
            'client_email' => 'required|email|max:100|unique:client_users,email,' . $user->id,
            'primary_contact' => 'required|string|regex:/^[0-9+\s\-]{7,15}$/',
            'secondary_contact' => 'nullable|string|regex:/^[0-9+\s\-]{7,15}$/',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100|regex:/^[a-zA-Z\s\.\'-]*$/',
            'state' => 'nullable|string|max:100|regex:/^[a-zA-Z\s\.\'-]*$/',
            'country' => 'nullable|string|max:100|regex:/^[a-zA-Z\s\.\'-]*$/',
        ], [
            'client_name.regex' => 'The Client Name must only contain letters, dots, and spaces.',
            'primary_contact.regex' => 'The Primary Contact must only contain numeric digits.',
            'secondary_contact.regex' => 'The Secondary Contact must only contain numeric digits.',
            'city.regex' => 'City name must only contain letters and spaces.',
            'state.regex' => 'State name must only contain letters and spaces.',
            'country.regex' => 'Country name must only contain letters and spaces.',
        ]);

        $location = $request->address ?: trim(($request->city ? $request->city : '') . ($request->state ? ', ' . $request->state : ''));

        $client->update([
            'client_name' => $request->client_name,
            'client_email' => $request->client_email,
            'primary_contact' => $request->primary_contact,
            'secondary_contact' => $request->secondary_contact ?? '',
            'client_location' => $location ?: ($client->client_location ?? 'Not Specified'),
            'city' => $request->city ?? '',
            'state' => $request->state ?? '',
            'country' => $request->country ?? 'India',
        ]);

        // Sync name and email with client user login
        $user->update([
            'name' => $request->client_name,
            'email' => $request->client_email,
        ]);

        return back()->with('success', 'Your client profile information has been updated successfully.');
    }

    public function settings()
    {
        return view('client.settings');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
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
