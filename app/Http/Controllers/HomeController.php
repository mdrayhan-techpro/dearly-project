<?php

namespace App\Http\Controllers;

use App\Models\ApplicationSetting;
use App\Models\Letter;
use App\Models\MatchResult;
use App\Models\PostageStamp;
use App\Models\StationeryTheme;
use App\Models\User;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $stats = [
            'letters_delivered' => Letter::whereIn('status', ['sent', 'delivered', 'read'])->count(),
            'active_pen_friends' => User::where('is_suspended', false)->count(),
            'matches_found' => MatchResult::where('status', '!=', 'dismissed')->count(),
        ];

        $themes = StationeryTheme::where('is_active', true)->take(4)->get();
        $stamps = PostageStamp::where('is_active', true)->take(6)->get();

        $faqs = ApplicationSetting::get('faqs', []);

        return view('public.landing', compact('stats', 'themes', 'stamps', 'faqs'));
    }

    public function about(): View
    {
        return view('public.about');
    }

    public function howItWorks(): View
    {
        return view('public.how-it-works');
    }

    public function privacy(): View
    {
        $policy = ApplicationSetting::get('privacy_policy', 'Our detailed privacy policy.');
        return view('public.privacy', compact('policy'));
    }

    public function terms(): View
    {
        $terms = ApplicationSetting::get('terms_of_service', 'Our terms of service.');
        return view('public.terms', compact('terms'));
    }

    public function guidelines(): View
    {
        $guidelines = ApplicationSetting::get('community_guidelines', 'Our community guidelines.');
        return view('public.guidelines', compact('guidelines'));
    }

    public function faq(): View
    {
        $faqs = ApplicationSetting::get('faqs', []);
        return view('public.faq', compact('faqs'));
    }
}
