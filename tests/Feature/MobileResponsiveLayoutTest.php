<?php

namespace Tests\Feature;

use App\Models\MailingCode;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MobileResponsiveLayoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_sees_mobile_hamburger_and_drawer_navigation()
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee('mobileMenuOpen', false);
        $response->assertSee('aria-label="Toggle Menu"', false);
        $response->assertSee(__('How It Works'));
        $response->assertSee(__('Sign In'));
        $response->assertSee(__('Join Post Office'));
    }

    public function test_authenticated_user_sees_mobile_drawer_with_personal_postal_links()
    {
        $user = User::factory()->create(['name' => 'Cordelia Chase']);
        UserProfile::factory()->create([
            'user_id' => $user->id,
            'nickname' => 'Cordelia',
        ]);
        MailingCode::factory()->create([
            'user_id' => $user->id,
            'code' => 'DEARLY-9XYZ-TEST',
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->get(route('post-office.index'));

        $response->assertStatus(200);
        $response->assertSee('mobileMenuOpen', false);
        $response->assertSee('aria-label="Toggle Menu"', false);
        $response->assertSee('Cordelia');
        $response->assertSee('DEARLY-9XYZ-TEST');
        $response->assertSee(__('My Post Office'));
        $response->assertSee(__('Write Letter'));
        $response->assertSee(__('Find Pen Friends'));
        $response->assertSee(__('Mailbox'));
        $response->assertSee(__('My Mailing Code'));
        $response->assertSee(__('Profile & Privacy'));
    }

    public function test_admin_sees_mobile_responsive_sidebar_and_toggle_button()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        UserProfile::factory()->create([
            'user_id' => $admin->id,
            'nickname' => 'Postmaster',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('sidebarOpen', false);
        $response->assertSee('aria-label="Open sidebar"', false);
        $response->assertSee('aria-label="Close sidebar"', false);
        $response->assertSee('overflow-x-auto');
    }
}
