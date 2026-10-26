<?php

namespace Tests\Feature;

use App\Models\ApplicationSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LocalizationAndEditorialContentTest extends TestCase
{
    use RefreshDatabase;

    public function test_all_seven_languages_switch_and_persist_in_session(): void
    {
        $locales = ['en', 'bn', 'hi', 'ar', 'es', 'fr', 'pt'];

        foreach ($locales as $locale) {
            $response = $this->get(route('locale.switch', $locale));
            $response->assertSessionHas('locale', $locale);
            $response->assertRedirect();
        }
    }

    public function test_about_page_renders_localized_dynamic_content(): void
    {
        ApplicationSetting::setLocalized('about_heading', 'bn', 'ধীরগতির চিঠির হারিয়ে যাওয়া শিল্প');
        ApplicationSetting::setLocalized('about_badge', 'bn', 'আমাদের সূচনা ও আদর্শ');

        // Test in Bengali
        $response = $this->withSession(['locale' => 'bn'])->get(route('about'));
        $response->assertStatus(200);
        $response->assertSee('ধীরগতির চিঠির হারিয়ে যাওয়া শিল্প');
        $response->assertSee('আমাদের সূচনা ও আদর্শ');

        // Test in English
        $responseEn = $this->withSession(['locale' => 'en'])->get(route('about'));
        $responseEn->assertStatus(200);
        $responseEn->assertSee('The Lost Art of the Slow Letter');
    }

    public function test_how_it_works_page_renders_localized_steps(): void
    {
        ApplicationSetting::setLocalized('how_it_works_heading', 'bn', 'চিঠিপত্র যেভাবে খুঁজে নেয় তার ঠিকানা');
        ApplicationSetting::setLocalized('how_it_works_steps', 'bn', [
            ['step' => 1, 'title' => 'আপনার ডাক পরিচয় তৈরি করুন', 'desc' => 'গুগল অথবা ইমেইল দিয়ে নিরাপদে সাইন ইন করুন।']
        ], 'json');

        $response = $this->withSession(['locale' => 'bn'])->get(route('how-it-works'));
        $response->assertStatus(200);
        $response->assertSee('চিঠিপত্র যেভাবে খুঁজে নেয় তার ঠিকানা');
        $response->assertSee('আপনার ডাক পরিচয় তৈরি করুন');
    }

    public function test_admin_can_use_auto_translate_endpoint(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'is_suspended' => false]);

        $response = $this->actingAs($admin)->postJson(route('admin.settings.auto-translate'), [
            'source_locale' => 'en',
            'target_locales' => ['bn', 'es'],
            'texts' => [
                'about_heading' => 'The Lost Art of the Slow Letter',
            ],
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'translations' => [
                'bn' => ['about_heading'],
                'es' => ['about_heading'],
            ],
        ]);
    }

    public function test_admin_can_update_localized_settings(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'is_suspended' => false]);

        $payload = [
            'site_name' => 'Dearly',
            'site_tagline' => 'Letters Find Their Way.',
            'match_threshold' => 90,
            'max_letters_per_day' => 5,
            'max_first_contacts_per_day' => 2,
            'default_language' => 'en',
            'contact_email' => 'postmaster@dearlyletters.com',
            'localized' => [
                'bn' => [
                    'about_heading' => 'বাংলায় নতুন শিরোনাম',
                    'about_badge' => 'বাংলা ব্যাজ',
                ],
                'es' => [
                    'about_heading' => 'Título en Español',
                ],
            ],
        ];

        $response = $this->actingAs($admin)->put(route('admin.settings.update'), $payload);
        $response->assertRedirect();

        $this->assertEquals('বাংলায় নতুন শিরোনাম', ApplicationSetting::getLocalized('about_heading', 'bn'));
        $this->assertEquals('Título en Español', ApplicationSetting::getLocalized('about_heading', 'es'));
    }
}
