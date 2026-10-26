<?php

namespace Database\Seeders;

use App\Models\ApplicationSetting;
use Illuminate\Database\Seeder;

class ApplicationSettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'site_name', 'value' => 'Dearly', 'type' => 'string'],
            ['key' => 'site_tagline', 'value' => 'Letters Find Their Way.', 'type' => 'string'],
            ['key' => 'match_threshold', 'value' => '90', 'type' => 'integer'],
            ['key' => 'max_letters_per_day', 'value' => '5', 'type' => 'integer'],
            ['key' => 'max_first_contacts_per_day', 'value' => '2', 'type' => 'integer'],
            ['key' => 'default_language', 'value' => 'en', 'type' => 'string'],
            ['key' => 'contact_email', 'value' => 'postmaster@dearlyletters.com', 'type' => 'string'],
            ['key' => 'registration_enabled', 'value' => '1', 'type' => 'boolean'],
            ['key' => 'maintenance_mode', 'value' => '0', 'type' => 'boolean'],
            ['key' => 'brand_color_ivory', 'value' => '#F6F0E5', 'type' => 'string'],
            ['key' => 'brand_color_burgundy', 'value' => '#783C46', 'type' => 'string'],
            ['key' => 'brand_color_ink', 'value' => '#342A25', 'type' => 'string'],
            ['key' => 'brand_color_brass', 'value' => '#B79A61', 'type' => 'string'],
            ['key' => 'brand_color_forest', 'value' => '#405B4B', 'type' => 'string'],
            [
                'key' => 'faqs',
                'value' => json_encode([
                    [
                        'q' => 'How is Dearly different from an instant messaging app or email?',
                        'a' => 'Dearly is intentionally slow. Letters take time to travel and be received. There are no online presence dots, typing indicators, or pressure to respond instantly. Each letter is crafted on beautiful digital stationery, packaged inside an envelope with collectible stamps, and sealed with virtual wax.'
                    ],
                    [
                        'q' => 'Is my personal email address or real name visible to other users?',
                        'a' => 'Never. Dearly enforces strict privacy by design. Other members only see your chosen public nickname, broad age range, and shared interests. Letters are sent using unique private mailing codes (e.g., DEARLY-7K9M-2Q8P).'
                    ],
                    [
                        'q' => 'How does the friendship compatibility algorithm work?',
                        'a' => 'You choose a 20, 30, 40, or 50-question discovery questionnaire covering 17 personality, lifestyle, and communication dimensions. Our server-side weighted scoring algorithm compares your answers to find kindred spirits with a 90% or higher compatibility score.'
                    ],
                    [
                        'q' => 'What is the daily sending limit?',
                        'a' => 'To preserve the thoughtful and intimate quality of correspondence and prevent spam, users can dispatch up to 5 letters per day, with a maximum of 2 new pen-pal introductions per day.'
                    ],
                    [
                        'q' => 'Are read receipts real or automatic?',
                        'a' => 'Read receipts are authentic: they are triggered only when the authorized recipient legitimately opens and renders your letter inside their Dearly post office.'
                    ],
                ]),
                'type' => 'json'
            ],
            [
                'key' => 'community_guidelines',
                'value' => "### The Dearly Post Office Community Guidelines\n\n1. **Kindness & Courtesy**: Treat every correspondent with dignity, empathy, and respect.\n2. **Slow Correspondence**: Understand that life moves in seasons; respect your pen friends' response rhythms without pressure.\n3. **Privacy First**: Do not pressure others to reveal personal contact details, physical addresses, or external social media profiles.\n4. **Zero Tolerance for Abuse**: Harassment, hate speech, spam, unsolicited commercial advertisements, and inappropriate content result in immediate account suspension.",
                'type' => 'string'
            ],
            [
                'key' => 'privacy_policy',
                'value' => "### Dearly Privacy Policy\n\nDearly is built on the fundamental principle that private correspondence should remain private.\n\n- We never sell, rent, or monetize your personal correspondence or demographic data.\n- We do not reveal your email address, Google account information, or real name to other users.\n- Letters are encrypted in transit and isolated in storage strictly to the sender and intended recipient.\n- You may request data export or complete account deletion at any time through account settings.",
                'type' => 'string'
            ],
            [
                'key' => 'terms_of_service',
                'value' => "### Dearly Terms of Service\n\nBy accessing Dearly, you agree to:\n\n- Be at least 18 years of age.\n- Use the platform solely for peaceful, personal correspondence.\n- Abide by community guidelines and daily postal sending limits.\n- Respect recipient privacy and refrain from automated scraping or bulk unsolicited dispatch.",
                'type' => 'string'
            ],
        ];

        foreach ($settings as $setting) {
            ApplicationSetting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
