<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $supportedLocales = array_keys(config('dearly.supported_locales', ['en' => []]));

        // 1. Session locale
        if (Session::has('locale') && in_array(Session::get('locale'), $supportedLocales)) {
            App::setLocale(Session::get('locale'));
        }
        // 2. User profile preference
        elseif ($request->user() && $request->user()->profile?->preferred_language) {
            $lang = $request->user()->profile->preferred_language;
            if (in_array($lang, $supportedLocales)) {
                App::setLocale($lang);
                Session::put('locale', $lang);
            }
        }

        return $next($request);
    }
}
