<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\PortalAdmin;
use App\Models\SystemSetting;

class AdminSettingController extends Controller
{
    public function index()
    {
        $admin = Auth::guard('admin')->user();
        $settings = SystemSetting::getAllSettings();

        return view('admin.settings.index', compact('admin', 'settings'));
    }

    public function updateBrand(Request $request)
    {
        $request->validate([
            'brand_name' => 'required|string|max:100',
            'brand_tagline' => 'nullable|string|max:150',
            'custom_logo' => 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:2048',
            'default_theme' => 'required|in:light,dark',
        ]);

        SystemSetting::set('brand_name', $request->brand_name);
        SystemSetting::set('brand_tagline', $request->brand_tagline ?? 'Software Solution');
        SystemSetting::set('default_theme', $request->default_theme);

        if ($request->hasFile('custom_logo')) {
            $oldPath = SystemSetting::get('brand_logo_path');
            if ($oldPath && Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }

            $path = $request->file('custom_logo')->store('brand', 'public');
            SystemSetting::set('brand_logo_path', $path);
            SystemSetting::set('brand_logo_type', 'custom_upload');
        }

        return back()->with('success_brand', 'Brand appearance and logo settings updated successfully!');
    }

    public function updateCompany(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:150',
            'md_name' => 'required|string|max:100',
            'company_email' => 'required|email|max:100',
            'company_phone' => 'nullable|string|max:25',
            'company_address' => 'nullable|string|max:255',
            'company_city' => 'nullable|string|max:100',
            'company_state' => 'nullable|string|max:100',
            'company_country' => 'nullable|string|max:100',
            'company_gstin' => 'nullable|string|max:50',
            'company_website' => 'nullable|string|max:150',
            'company_about' => 'nullable|string|max:500',
        ]);

        $fields = [
            'company_name',
            'md_name',
            'company_email',
            'company_phone',
            'company_address',
            'company_city',
            'company_state',
            'company_country',
            'company_gstin',
            'company_website',
            'company_about',
        ];

        foreach ($fields as $field) {
            SystemSetting::set($field, $request->input($field, ''));
        }

        return back()->with('success_company', 'Company and Managing Director details saved successfully!');
    }

    public function updateProfile(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:portal_admins,email,' . $admin->id,
        ]);

        $admin->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return back()->with('success_profile', 'Admin profile and credentials updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        $admin = Auth::guard('admin')->user();

        if (!Hash::check($request->current_password, $admin->password)) {
            return back()->with('error_password', 'Current password does not match!');
        }

        $admin->update([
            'password' => Hash::make($request->new_password)
        ]);

        return back()->with('success_password', 'Security password updated successfully.');
    }
}
