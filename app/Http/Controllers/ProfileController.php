<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ProfileController extends Controller
{
    public function show(): View
    {
        $user = auth()->user();
        $profile = $user->profile ?: new UserProfile();
        $supportedLocales = config('dearly.supported_locales', []);

        $popularInterests = [
            'Reading & Literature', 'Antique Botany & Plants', 'Tea & Coffee Rituals',
            'Analog Photography (35mm)', 'Fountain Pens & Ink', 'Vinyl Records & Music',
            'Astronomy & Stargazing', 'Old Architecture & Castles', 'Handmade Crafts & Pottery',
            'Classical Philosophy', 'Poetry & Haiku', 'Cooking & Sourdough',
            'Travel & World Cultures', 'Vintage Postcards', 'Solitary Nature Walks',
            'Museums & Art History', 'Journaling & Notebooks', 'Folk Tales & Mythology'
        ];

        return view('profile.show', compact('user', 'profile', 'supportedLocales', 'popularInterests'));
    }

    public function update(Request $request): RedirectResponse
    {
        $user = auth()->user();

        $validated = $request->validate([
            'nickname' => [
                'required',
                'string',
                'min:3',
                'max:30',
                'alpha_dash',
                Rule::unique('user_profiles', 'nickname')->ignore($user->profile?->id),
            ],
            'bio' => ['nullable', 'string', 'max:500'],
            'interests' => ['nullable', 'array', 'max:10'],
            'interests.*' => ['string', 'max:50'],
            'country' => ['nullable', 'string', 'max:100'],
            'preferred_language' => ['required', 'string', 'in:' . implode(',', array_keys(config('dearly.supported_locales', [])))],
            'is_matching_paused' => ['nullable', 'boolean'],
        ]);

        $user->profile->update([
            'nickname' => $validated['nickname'],
            'bio' => $validated['bio'],
            'interests' => $validated['interests'] ?? [],
            'country' => $validated['country'],
            'preferred_language' => $validated['preferred_language'],
            'is_matching_paused' => $request->boolean('is_matching_paused'),
        ]);

        session(['locale' => $validated['preferred_language']]);

        return back()->with('status', 'Your profile and privacy settings have been updated.');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $user = auth()->user();

        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('status', __('Your password has been securely updated.'));
    }

    public function exportData(): StreamedResponse
    {
        $user = auth()->user();
        $user->load(['profile', 'mailingCode', 'questionnaireAttempts.answers', 'sentLetters', 'receivedLetters']);

        $export = [
            'exported_at' => now()->toIso8601String(),
            'platform' => 'Dearly Postal Sanctuary',
            'account' => [
                'name' => $user->name,
                'email' => $user->email,
                'created_at' => $user->created_at?->toIso8601String(),
            ],
            'profile' => [
                'nickname' => $user->profile?->nickname,
                'bio' => $user->profile?->bio,
                'interests' => $user->profile?->interests,
                'country' => $user->profile?->country,
                'preferred_language' => $user->profile?->preferred_language,
            ],
            'mailing_code' => $user->mailingCode?->code,
            'questionnaire_history' => $user->questionnaireAttempts->map(fn($q) => [
                'completed_at' => $q->completed_at?->toIso8601String(),
                'length' => $q->questionnaire_length,
                'answers_count' => $q->answers->count(),
            ]),
            'correspondence_metrics' => [
                'sent_letters_count' => $user->sentLetters->count(),
                'received_letters_count' => $user->receivedLetters->count(),
            ],
        ];

        $json = json_encode($export, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        $filename = 'dearly-archive-' . ($user->profile?->nickname ?? 'account') . '-' . date('Y-m-d') . '.json';

        return response()->streamDownload(function () use ($json) {
            echo $json;
        }, $filename, [
            'Content-Type' => 'application/json',
        ]);
    }

    public function switchLanguage(string $locale): RedirectResponse
    {
        if (array_key_exists($locale, config('dearly.supported_locales', []))) {
            session(['locale' => $locale]);
            if (Auth::check() && Auth::user()->profile) {
                Auth::user()->profile->update(['preferred_language' => $locale]);
            }
        }

        return back();
    }

    public function destroyAccount(Request $request): RedirectResponse
    {
        $user = auth()->user();

        // Anonymize user records and soft delete
        $user->update([
            'is_suspended' => true,
            'suspension_reason' => 'Account deleted by user request.',
            'email' => 'deleted_' . $user->id . '@dearlyletters.invalid',
        ]);

        if ($user->profile) {
            $user->profile->update([
                'nickname' => 'DepartedFriend_' . $user->id,
                'bio' => null,
                'is_matching_paused' => true,
            ]);
        }

        if ($user->mailingCode) {
            $user->mailingCode->update(['is_active' => false]);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('status', 'Your account has been closed and removed from discovery.');
    }
}
