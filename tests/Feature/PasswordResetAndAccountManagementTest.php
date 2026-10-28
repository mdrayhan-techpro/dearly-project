<?php

namespace Tests\Feature;

use App\Models\MailingCode;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PasswordResetAndAccountManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function createTestUser(string $password = 'Secret123!'): User
    {
        $user = User::factory()->create([
            'password' => Hash::make($password),
        ]);
        UserProfile::factory()->create(['user_id' => $user->id, 'is_onboarded' => true]);
        MailingCode::factory()->create(['user_id' => $user->id]);
        return $user;
    }

    public function test_guest_can_view_forgot_password_page(): void
    {
        $response = $this->get(route('password.request'));
        $response->assertStatus(200);
        $response->assertSee('Forgot Password');
    }

    public function test_user_can_request_password_reset_link(): void
    {
        $user = $this->createTestUser();

        $response = $this->post(route('password.email'), [
            'email' => $user->email,
        ]);

        $response->assertSessionHas('status');
    }

    public function test_authenticated_user_can_update_password(): void
    {
        $user = $this->createTestUser('OldPassword123!');

        $response = $this->actingAs($user)->put(route('profile.password'), [
            'current_password' => 'OldPassword123!',
            'password' => 'NewStrongPass123!',
            'password_confirmation' => 'NewStrongPass123!',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status');
        $this->assertTrue(Hash::check('NewStrongPass123!', $user->fresh()->password));
    }

    public function test_authenticated_user_can_export_data_archive(): void
    {
        $user = $this->createTestUser();

        $response = $this->actingAs($user)->get(route('profile.export'));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/json');
        $this->assertStringContainsString('Dearly Postal Sanctuary', $response->streamedContent());
        $this->assertStringContainsString($user->email, $response->streamedContent());
    }

    public function test_404_error_page_renders_vintage_template(): void
    {
        $response = $this->get('/a-non-existent-postal-route');

        $response->assertStatus(404);
        $response->assertSee('A Letter Lost to Time');
    }
}

