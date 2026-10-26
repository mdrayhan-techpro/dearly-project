<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminAuditLog;
use App\Models\ApplicationSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminSettingController extends Controller
{
    public function index(): View
    {
        $settings = ApplicationSetting::all()->pluck('value', 'key');
        $supportedLocales = config('dearly.supported_locales', []);

        return view('admin.settings.index', compact('settings', 'supportedLocales'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'site_name' => ['required', 'string', 'max:100'],
            'site_tagline' => ['required', 'string', 'max:255'],
            'match_threshold' => ['required', 'integer', 'min:50', 'max:100'],
            'max_letters_per_day' => ['required', 'integer', 'min:1', 'max:50'],
            'max_first_contacts_per_day' => ['required', 'integer', 'min:1', 'max:20'],
            'default_language' => ['required', 'string'],
            'contact_email' => ['required', 'email'],
            'registration_enabled' => ['nullable', 'boolean'],
            'maintenance_mode' => ['nullable', 'boolean'],
            'brand_color_ivory' => ['nullable', 'string', 'max:20'],
            'brand_color_burgundy' => ['nullable', 'string', 'max:20'],
            'brand_color_ink' => ['nullable', 'string', 'max:20'],
            'brand_color_brass' => ['nullable', 'string', 'max:20'],
            'brand_color_forest' => ['nullable', 'string', 'max:20'],
            'community_guidelines' => ['nullable', 'string'],
            'privacy_policy' => ['nullable', 'string'],
            'terms_of_service' => ['nullable', 'string'],
        ]);

        $before = ApplicationSetting::all()->pluck('value', 'key')->toArray();

        foreach ($validated as $key => $value) {
            $type = is_bool($value) ? 'boolean' : (is_int($value) ? 'integer' : 'string');
            ApplicationSetting::set($key, $value, $type);
        }

        // Handle boolean checkboxes
        ApplicationSetting::set('registration_enabled', $request->boolean('registration_enabled'), 'boolean');
        ApplicationSetting::set('maintenance_mode', $request->boolean('maintenance_mode'), 'boolean');

        AdminAuditLog::log('settings.update', 'ApplicationSetting', null, [
            'before' => $before,
            'after' => ApplicationSetting::all()->pluck('value', 'key')->toArray(),
        ]);

        return back()->with('status', 'Platform configuration saved successfully.');
    }
}
