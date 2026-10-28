<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\OAuthAccount;
use App\Models\User;
use App\Models\UserProfile;
use App\Services\MailingCodeService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function showRegister(): View
    {
        return view('auth.register');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();

            if ($user->is_suspended) {
                Auth::logout();
                return back()->withErrors(['email' => 'This account has been suspended by administration.']);
            }

            if ($user->isAdmin() && $request->has('admin_login')) {
                return redirect()->route('admin.dashboard');
            }

            if (!$user->profile || !$user->profile->is_onboarded) {
                return redirect()->route('onboarding.index');
            }

            return redirect()->route('post-office.index');
        }

        return back()->withErrors([
            'email' => 'The provided postal credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'user',
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('onboarding.index');
    }

    /**
     * Local testing quick-login persona switch (allows seamless testing of correspondence without live Google credentials)
     */
    public function devLogin(Request $request): RedirectResponse
    {
        abort_unless(
            app()->environment('local', 'testing'),
            403,
            'Developer quick-login is disabled in this environment.'
        );

        $email = $request->input('email');
        $user = User::where('email', $email)->first();

        if ($user) {
            Auth::login($user);
            $request->session()->regenerate();

            if ($user->isAdmin() && $request->has('admin')) {
                return redirect()->route('admin.dashboard');
            }

            if (!$user->profile || !$user->profile->is_onboarded) {
                return redirect()->route('onboarding.index');
            }

            return redirect()->route('post-office.index');
        }

        return back()->withErrors(['dev' => 'Test persona not found.']);
    }

    /**
     * Get configured Google Socialite driver with SSL cert support.
     */
    protected function getGoogleDriver()
    {
        $driver = Socialite::driver('google');

        $caPath = storage_path('cacert.pem');
        if (file_exists($caPath)) {
            $driver->setHttpClient(new \GuzzleHttp\Client([
                'verify' => $caPath,
            ]));
        }

        return $driver;
    }

    /**
     * Redirect to Google OAuth Provider
     */
    public function googleRedirect(): RedirectResponse
    {
        if (empty(config('services.google.client_id')) || empty(config('services.google.client_secret'))) {
            return redirect()->route('login')->with('warning', 'Google OAuth client credentials are not configured in .env. Please use local login or test personas.');
        }

        return $this->getGoogleDriver()->redirect();
    }

    /**
     * Handle Google OAuth Callback
     */
    public function googleCallback(MailingCodeService $mailingCodeService): RedirectResponse
    {
        try {
            $googleUser = $this->getGoogleDriver()->stateless()->user();
        } catch (Exception $e) {
            Log::error('Google OAuth Callback Exception: ' . $e->getMessage(), [
                'exception' => $e,
            ]);
            return redirect()->route('login')->withErrors(['oauth' => 'Google authentication was cancelled or failed. Please try again. (' . $e->getMessage() . ')']);
        }

        // Check if oauth account exists
        $oauth = OAuthAccount::where('provider', 'google')
            ->where('provider_user_id', $googleUser->getId())
            ->first();

        if ($oauth) {
            $user = $oauth->user;
        } else {
            // Check if user with this email exists
            $user = User::where('email', $googleUser->getEmail())->first();

            if (!$user) {
                $user = User::create([
                    'name' => $googleUser->getName() ?: 'Dearly Member',
                    'email' => $googleUser->getEmail(),
                    'password' => null,
                    'role' => 'user',
                    'email_verified_at' => now(),
                ]);
            }

            OAuthAccount::create([
                'user_id' => $user->id,
                'provider' => 'google',
                'provider_user_id' => $googleUser->getId(),
            ]);
        }

        if ($user->is_suspended) {
            return redirect()->route('login')->withErrors(['account' => 'This account has been suspended by administration.']);
        }

        Auth::login($user, true);
        request()->session()->regenerate();

        if (!$user->profile || !$user->profile->is_onboarded) {
            return redirect()->route('onboarding.index');
        }

        return redirect()->route('post-office.index');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('status', 'You have securely signed out of your post office.');
    }
}
