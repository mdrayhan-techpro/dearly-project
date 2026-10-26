<?php

namespace App\Http\Controllers;

use App\Models\UserProfile;
use App\Services\MailingCodeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class OnboardingController extends Controller
{
    public function index(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        if ($user->profile && $user->profile->is_onboarded) {
            return redirect()->route('post-office.index');
        }

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

        return view('onboarding.index', compact('profile', 'supportedLocales', 'popularInterests'));
    }

    public function store(Request $request, MailingCodeService $mailingCodeService): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'nickname' => [
                'required',
                'string',
                'min:3',
                'max:30',
                'alpha_dash',
                Rule::unique('user_profiles', 'nickname')->ignore($user->profile?->id),
            ],
            'age' => ['required', 'integer', 'min:18', 'max:120'],
            'gender' => ['nullable', 'string', 'in:female,male,non_binary,prefer_not_to_say'],
            'preferred_language' => ['required', 'string', 'in:' . implode(',', array_keys(config('dearly.supported_locales', [])))],
            'country' => ['nullable', 'string', 'max:100'],
            'bio' => ['nullable', 'string', 'max:500'],
            'interests' => ['nullable', 'array', 'max:10'],
            'interests.*' => ['string', 'max:50'],
            'response_cadence' => ['nullable', 'string', 'in:weekly,biweekly,monthly,seasonal'],
        ], [
            'age.min' => 'In accordance with our safeguarding policy, Dearly is an adult-only correspondence platform (minimum age 18).',
            'nickname.alpha_dash' => 'Your public nickname may only contain letters, numbers, dashes, and underscores.',
        ]);

        $commPrefs = [
            'cadence' => $validated['response_cadence'] ?? 'biweekly',
        ];

        UserProfile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'nickname' => $validated['nickname'],
                'age' => $validated['age'],
                'gender' => $validated['gender'] ?? 'prefer_not_to_say',
                'preferred_language' => $validated['preferred_language'],
                'country' => $validated['country'] ?? null,
                'bio' => $validated['bio'] ?? null,
                'interests' => $validated['interests'] ?? [],
                'communication_preferences' => $commPrefs,
                'is_onboarded' => true,
            ]
        );

        // Ensure mailing code is generated
        $mailingCodeService->getOrCreateForUser($user);

        // Set session locale to match preferred language
        session(['locale' => $validated['preferred_language']]);

        return redirect()->route('post-office.index')
            ->with('status', 'Welcome to Dearly! Your post office box and private mailing code have been established.');
    }
}
