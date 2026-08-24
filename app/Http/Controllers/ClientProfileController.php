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
        $client = Auth::guard('client')->user()->client;

        // Clients can only update specific contact fields
        $request->validate([
            'primary_contact' => 'nullable|string|max:11',
            'secondary_contact' => 'nullable|string|max:255',
            'website' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
        ]);

        $client->update($request->only([
            'primary_contact', 'secondary_contact', 'website', 'city', 'state', 'country'
        ]));

        return back()->with('success', 'Profile updated successfully.');
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

        return back()->with('success', 'Password updated successfully.');
    }
}
