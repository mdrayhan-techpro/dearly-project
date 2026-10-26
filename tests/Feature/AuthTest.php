<?php

namespace Tests\Feature;

use App\Models\MailingCode;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_and_register_pages_are_accessible_to_guests(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('Sign In');

        $response = $this->get('/register');
        $response->assertStatus(200);
        $response->assertSee('Join Dearly');
    }

    public function test_user_can_register_and_is_directed_to_onboarding(): void
    {
        $response = $this->post('/register', [
            'name' => 'Clara Oswald',
            'email' => 'clara@example.com',
            'password' => 'SecretP@ssword123',
            'password_confirmation' => 'SecretP@ssword123',
        ]);

        $response->assertRedirect('/onboarding');

        $this->assertDatabaseHas('users', [
            'email' => 'clara@example.com',
            'name' => 'Clara Oswald',
            'role' => 'user',
        ]);

        $user = User::where('email', 'clara@example.com')->first();
        $this->assertNotNull($user);
        $this->assertAuthenticatedAs($user);
    }

    public function test_onboarding_enforces_adult_age_safeguard(): void
    {
        $user = User::factory()->create([
            'email' => 'minor@example.com',
        ]);

        $this->actingAs($user);

        // Attempt onboarding with age under 18
        $response = $this->post('/onboarding', [
            'nickname' => 'YoungPenPal',
            'age' => 15,
            'preferred_language' => 'en',
        ]);

        $response->assertSessionHasErrors('age');
        $this->assertDatabaseMissing('user_profiles', ['user_id' => $user->id]);
    }

    public function test_user_completes_onboarding_and_receives_unique_mailing_code(): void
    {
        $user = User::factory()->create([
            'email' => 'adult@example.com',
        ]);

        $this->actingAs($user);

        $response = $this->post('/onboarding', [
            'nickname' => 'KindredSpirit',
            'age' => 28,
            'preferred_language' => 'en',
            'interests' => ['Reading', 'Stargazing'],
            'response_cadence' => 'weekly',
        ]);

        $response->assertRedirect('/post-office');

        $this->assertDatabaseHas('user_profiles', [
            'user_id' => $user->id,
            'nickname' => 'KindredSpirit',
            'age' => 28,
            'is_onboarded' => true,
        ]);

        $user->refresh();
        $this->assertNotNull($user->mailingCode);
        $this->assertMatchesRegularExpression('/^DEARLY-[A-Z0-9]{4}-[A-Z0-9]{4}$/', $user->mailingCode->code);
    }

    public function test_user_can_login_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'arthur@example.com',
            'password' => Hash::make('ValidP@ssword123'),
        ]);

        UserProfile::factory()->create([
            'user_id' => $user->id,
            'is_onboarded' => true,
        ]);

        $response = $this->post('/login', [
            'email' => 'arthur@example.com',
            'password' => 'ValidP@ssword123',
        ]);

        $response->assertRedirect('/post-office');
        $this->assertAuthenticatedAs($user);
    }

    public function test_user_cannot_login_with_invalid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'arthur@example.com',
            'password' => Hash::make('ValidP@ssword123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'arthur@example.com',
            'password' => 'WrongPassword!',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_suspended_user_is_blocked_by_check_suspended_middleware(): void
    {
        $user = User::factory()->create([
            'email' => 'suspended@example.com',
            'password' => Hash::make('ValidP@ssword123'),
            'is_suspended' => true,
            'suspension_reason' => 'Violation of correspondence decorum.',
        ]);

        UserProfile::factory()->create([
            'user_id' => $user->id,
            'is_onboarded' => true,
        ]);

        $this->actingAs($user);

        $response = $this->get('/post-office');
        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('account');
        $this->assertGuest();
    }

    public function test_developer_persona_quick_login_authenticates_selected_persona(): void
    {
        $persona = User::factory()->create([
            'email' => 'eleanor@dearlyletters.com',
        ]);

        UserProfile::factory()->create([
            'user_id' => $persona->id,
            'nickname' => 'EleanorVance',
            'is_onboarded' => true,
        ]);

        MailingCode::factory()->create([
            'user_id' => $persona->id,
            'code' => 'DEARLY-ELNR-7K9M',
        ]);

        $response = $this->post('/dev-login', [
            'email' => 'eleanor@dearlyletters.com',
        ]);

        $response->assertRedirect('/post-office');
        $this->assertAuthenticatedAs($persona);
    }

    public function test_authenticated_user_can_logout(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('/logout');
        $response->assertRedirect('/');
        $this->assertGuest();
    }
}
