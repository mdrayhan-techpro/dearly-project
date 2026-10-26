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
        $locale = app()->getLocale();
        $stats = [
            'letters_delivered' => Letter::whereIn('status', ['sent', 'delivered', 'read'])->count(),
            'active_pen_friends' => User::where('is_suspended', false)->count(),
            'matches_found' => MatchResult::where('status', '!=', 'dismissed')->count(),
        ];

        $themes = StationeryTheme::where('is_active', true)->take(4)->get();
        $stamps = PostageStamp::where('is_active', true)->take(6)->get();

        $faqs = ApplicationSetting::getLocalized('faqs', $locale, []);

        return view('public.landing', compact('stats', 'themes', 'stamps', 'faqs'));
    }

    public function about(): View
    {
        $locale = app()->getLocale();
        $about = [
            'title' => ApplicationSetting::getLocalized('about_title', $locale, 'About the Dearly Post Office'),
            'badge' => ApplicationSetting::getLocalized('about_badge', $locale, 'Our Origin & Ethos'),
            'heading' => ApplicationSetting::getLocalized('about_heading', $locale, 'The Lost Art of the Slow Letter'),
            'content' => ApplicationSetting::getLocalized('about_content', $locale, ''),
            'postmaster_title' => ApplicationSetting::getLocalized('about_postmaster_title', $locale, 'The Postmaster'),
            'postmaster_sub' => ApplicationSetting::getLocalized('about_postmaster_sub', $locale, 'The Dearly Sanctuary'),
            'postal_mark' => ApplicationSetting::getLocalized('about_postal_mark', $locale, 'Archival Record'),
        ];

        return view('public.about', compact('about'));
    }

    public function howItWorks(): View
    {
        $locale = app()->getLocale();
        $howItWorks = [
            'badge' => ApplicationSetting::getLocalized('how_it_works_badge', $locale, 'The Postal Manual'),
            'heading' => ApplicationSetting::getLocalized('how_it_works_heading', $locale, 'How Correspondence Finds Its Way'),
            'steps' => ApplicationSetting::getLocalized('how_it_works_steps', $locale, []),
            'cta_text' => ApplicationSetting::getLocalized('how_it_works_cta_text', $locale, 'Start Your First Letter'),
        ];

        return view('public.how-it-works', compact('howItWorks'));
    }

    public function privacy(): View
    {
        $locale = app()->getLocale();
        $policy = ApplicationSetting::getLocalized('privacy_policy', $locale, 'Our detailed privacy policy.');
        return view('public.privacy', compact('policy'));
    }

    public function terms(): View
    {
        $locale = app()->getLocale();
        $terms = ApplicationSetting::getLocalized('terms_of_service', $locale, 'Our terms of service.');
        return view('public.terms', compact('terms'));
    }

    public function guidelines(): View
    {
        $locale = app()->getLocale();
        $guidelines = ApplicationSetting::getLocalized('community_guidelines', $locale, 'Our community guidelines.');
        return view('public.guidelines', compact('guidelines'));
    }

    public function faq(): View
    {
        $locale = app()->getLocale();
        $faqs = ApplicationSetting::getLocalized('faqs', $locale, []);
        return view('public.faq', compact('faqs'));
    }
}
