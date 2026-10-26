<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminAuditLog;
use App\Models\ApplicationSetting;
use App\Services\TranslationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminSettingController extends Controller
{
    public function __construct(
        protected TranslationService $translationService
    ) {}

    public function index(): View
    {
        $settings = ApplicationSetting::all()->pluck('value', 'key');
        $supportedLocales = config('dearly.supported_locales', []);

        $contentKeys = [
            'about_title',
            'about_badge',
            'about_heading',
            'about_content',
            'about_postmaster_title',
            'about_postmaster_sub',
            'about_postal_mark',
            'how_it_works_badge',
            'how_it_works_heading',
            'how_it_works_cta_text',
            'privacy_policy',
            'terms_of_service',
            'community_guidelines',
        ];

        $localizedContent = [];
        foreach ($contentKeys as $k) {
            $localizedContent[$k] = ApplicationSetting::getAllLocalized($k);
        }

        return view('admin.settings.index', compact('settings', 'supportedLocales', 'localizedContent'));
    }

    public function autoTranslate(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'texts' => ['required', 'array'],
            'source_locale' => ['nullable', 'string'],
            'target_locales' => ['nullable', 'array'],
        ]);

        $sourceLocale = $validated['source_locale'] ?? 'en';
        $targetLocales = $validated['target_locales'] ?? array_keys(config('dearly.supported_locales', []));

        $translations = $this->translationService->translateBatch(
            $validated['texts'],
            $targetLocales,
            $sourceLocale
        );

        return response()->json([
            'success' => true,
            'source_locale' => $sourceLocale,
            'translations' => $translations,
        ]);
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
            'localized' => ['nullable', 'array'],
        ]);

        $before = ApplicationSetting::all()->pluck('value', 'key')->toArray();

        foreach ($validated as $key => $value) {
            if ($key === 'localized') {
                continue;
            }
            $type = is_bool($value) ? 'boolean' : (is_int($value) ? 'integer' : 'string');
            ApplicationSetting::set($key, $value, $type);
        }

        // Save structured localized content
        if ($request->has('localized') && is_array($request->input('localized'))) {
            foreach ($request->input('localized') as $locale => $fields) {
                if (is_array($fields)) {
                    foreach ($fields as $fieldKey => $fieldValue) {
                        ApplicationSetting::setLocalized($fieldKey, $locale, $fieldValue, 'string');
                    }
                }
            }
        }

        // Handle boolean checkboxes
        ApplicationSetting::set('registration_enabled', $request->boolean('registration_enabled'), 'boolean');
        ApplicationSetting::set('maintenance_mode', $request->boolean('maintenance_mode'), 'boolean');

        AdminAuditLog::log('settings.update', 'ApplicationSetting', null, [
            'before' => $before,
            'after' => ApplicationSetting::all()->pluck('value', 'key')->toArray(),
        ]);

        return back()->with('status', 'Platform configuration and localized content saved successfully.');
    }
}
