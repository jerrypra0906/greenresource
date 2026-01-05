<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    /**
     * Display the settings page.
     */
    public function index()
    {
        $settings = [
            'company_name' => SiteSetting::get('company_name', 'Green Resources'),
            'company_address' => SiteSetting::get('company_address', ''),
            'company_phone' => SiteSetting::get('company_phone', ''),
            'company_email' => SiteSetting::get('company_email', 'info@greenresources.com'),
            'contact_email_recipients' => SiteSetting::get('contact_email_recipients', 'info@greenresources.com'),
            'contact_email_cc' => SiteSetting::get('contact_email_cc', ''),
            'contact_email_bcc' => SiteSetting::get('contact_email_bcc', ''),
            'social_facebook' => SiteSetting::get('social_facebook', ''),
            'social_twitter' => SiteSetting::get('social_twitter', ''),
            'social_linkedin' => SiteSetting::get('social_linkedin', ''),
            'social_instagram' => SiteSetting::get('social_instagram', ''),
        ];

        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Update the settings.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'company_address' => 'nullable|string',
            'company_phone' => 'nullable|string|max:50',
            'company_email' => 'required|email|max:255',
            'contact_email_recipients' => 'required|string',
            'contact_email_cc' => 'nullable|string',
            'contact_email_bcc' => 'nullable|string',
            'social_facebook' => 'nullable|url',
            'social_twitter' => 'nullable|url',
            'social_linkedin' => 'nullable|url',
            'social_instagram' => 'nullable|url',
        ]);

        foreach ($validated as $key => $value) {
            SiteSetting::set($key, $value);
        }

        return redirect()->route('admin.settings.index')
            ->with('success', 'Settings updated successfully.');
    }
}
