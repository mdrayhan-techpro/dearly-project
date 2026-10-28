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

    public function test_all_seven_languages_render_localized_policies_and_faqs(): void
    {
        $this->seed(\Database\Seeders\ApplicationSettingSeeder::class);

        $locales = ['en', 'bn', 'hi', 'ar', 'es', 'fr', 'pt'];

        foreach ($locales as $locale) {
            // 1. Privacy Policy
            $resPrivacy = $this->withSession(['locale' => $locale])->get(route('privacy'));
            $resPrivacy->assertStatus(200);

            // 2. Terms of Service
            $resTerms = $this->withSession(['locale' => $locale])->get(route('terms'));
            $resTerms->assertStatus(200);

            // 3. Community Guidelines
            $resGuidelines = $this->withSession(['locale' => $locale])->get(route('guidelines'));
            $resGuidelines->assertStatus(200);

            // 4. FAQ
            $resFaq = $this->withSession(['locale' => $locale])->get(route('faq'));
            $resFaq->assertStatus(200);

            // Verify that content is actually localized in DB
            $policyText = ApplicationSetting::getLocalized('privacy_policy', $locale);
            $this->assertNotEmpty($policyText);

            $termsText = ApplicationSetting::getLocalized('terms_of_service', $locale);
            $this->assertNotEmpty($termsText);

            $guidelinesText = ApplicationSetting::getLocalized('community_guidelines', $locale);
            $this->assertNotEmpty($guidelinesText);

            $faqs = ApplicationSetting::getLocalized('faqs', $locale);
            $this->assertIsArray($faqs);
            $this->assertCount(5, $faqs);
        }
    }

    public function test_form_validation_returns_localized_error_messages(): void
    {
        // 1. Bengali validation error
        $resBn = $this->withSession(['locale' => 'bn'])
            ->post(route('login.submit'), []);
        $resBn->assertSessionHasErrors(['email', 'password']);
        $errorsBn = session('errors')->getBag('default');
        $this->assertStringContainsString('পূরণ করা আবশ্যক', $errorsBn->first('email'));

        // 2. Hindi validation error
        $resHi = $this->withSession(['locale' => 'hi'])
            ->post(route('login.submit'), []);
        $resHi->assertSessionHasErrors(['email', 'password']);
        $errorsHi = session('errors')->getBag('default');
        $this->assertStringContainsString('अनिवार्य', $errorsHi->first('email'));

        // 3. Arabic validation error
        $resAr = $this->withSession(['locale' => 'ar'])
            ->post(route('login.submit'), []);
        $resAr->assertSessionHasErrors(['email', 'password']);
        $errorsAr = session('errors')->getBag('default');
        $this->assertStringContainsString('مطلوب', $errorsAr->first('email'));

        // 4. Spanish validation error
        $resEs = $this->withSession(['locale' => 'es'])
            ->post(route('login.submit'), []);
        $resEs->assertSessionHasErrors(['email', 'password']);
        $errorsEs = session('errors')->getBag('default');
        $this->assertStringContainsString('obligatorio', $errorsEs->first('email'));

        // 5. French validation error
        $resFr = $this->withSession(['locale' => 'fr'])
            ->post(route('login.submit'), []);
        $resFr->assertSessionHasErrors(['email', 'password']);
        $errorsFr = session('errors')->getBag('default');
        $this->assertStringContainsString('obligatoire', $errorsFr->first('email'));

        // 6. Portuguese validation error
        $resPt = $this->withSession(['locale' => 'pt'])
            ->post(route('login.submit'), []);
        $resPt->assertSessionHasErrors(['email', 'password']);
        $errorsPt = session('errors')->getBag('default');
        $this->assertStringContainsString('obrigatório', $errorsPt->first('email'));
    }

    public function test_arabic_rtl_layout_rendering_and_attributes(): void
    {
        $response = $this->withSession(['locale' => 'ar'])->get(route('home'));
        $response->assertStatus(200);

        // Assert html has lang="ar" and dir="rtl"
        $response->assertSee('dir="rtl"', false);
        $response->assertSee('lang="ar"', false);

        // Assert localized Arabic navigation & branding are present
        $response->assertSee('الرسائل تجد طريقها');
        $response->assertSee('تسجيل الدخول');
        $response->assertSee('الانضمام إلى مكتب البريد');

        // Also test English has dir="ltr"
        $responseEn = $this->withSession(['locale' => 'en'])->get(route('home'));
        $responseEn->assertStatus(200);
        $responseEn->assertSee('dir="ltr"', false);
        $responseEn->assertSee('lang="en"', false);
    }
}


