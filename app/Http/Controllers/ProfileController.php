<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

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
