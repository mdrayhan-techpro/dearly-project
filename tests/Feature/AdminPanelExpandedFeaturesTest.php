<?php

namespace Tests\Feature;

use App\Models\Letter;
use App\Models\MailingCode;
use App\Models\MatchResult;
use App\Models\PostageStamp;
use App\Models\StationeryTheme;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\WaxSeal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPanelExpandedFeaturesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    protected function createAdminUser(string $role = 'admin'): User
    {
        $user = User::factory()->create([
            'role' => $role,
            'is_suspended' => false,
        ]);

        UserProfile::factory()->create([
            'user_id' => $user->id,
            'nickname' => 'Admin Postmaster',
            'preferred_language' => 'en',
            'is_onboarded' => true,
        ]);

        MailingCode::create([
            'user_id' => $user->id,
            'code' => MailingCode::generateUniqueCode(),
            'is_active' => true,
            'permissions_policy' => 'anyone',
        ]);

        return $user;
    }

    public function test_admin_can_view_kindred_matches_and_rerun_matcher(): void
    {
        $admin = $this->createAdminUser('super_admin');
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        MatchResult::create([
            'user_id' => $userA->id,
            'matched_user_id' => $userB->id,
            'compatibility_score' => 94.5,
            'shared_dimensions' => ['Solitude', 'Literature'],
            'status' => 'active',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.matches.index'));
        $response->assertStatus(200);
        $response->assertSee('Kindred Pairings & Compatibility Engine');
        $response->assertSee('94.5%');

        $rerunResponse = $this->actingAs($admin)->post(route('admin.matches.rerun'));
        $rerunResponse->assertRedirect();
        $rerunResponse->assertSessionHas('status');
    }

    public function test_admin_can_inspect_postal_traffic_and_quarantine_letter(): void
    {
        $admin = $this->createAdminUser('admin');
        $sender = User::factory()->create();
        $recipient = User::factory()->create();

        $letter = Letter::create([
            'sender_id' => $sender->id,
            'recipient_id' => $recipient->id,
            'title' => 'Suspicious Missive',
            'body' => 'Spam content advertisement.',
            'status' => 'delivered',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.letters.index'));
        $response->assertStatus(200);
        $response->assertSee('Postal Traffic Ledger');
        $response->assertSee('Suspicious Missive');

        // Quarantine
        $quarantineResponse = $this->actingAs($admin)->post(route('admin.letters.quarantine', $letter->id), [
            'reason' => 'Commercial spam solicitation detected',
        ]);
        $quarantineResponse->assertRedirect();

        $letter->refresh();
        $this->assertEquals('quarantined', $letter->status);

        // Release
        $releaseResponse = $this->actingAs($admin)->post(route('admin.letters.release', $letter->id));
        $releaseResponse->assertRedirect();

        $letter->refresh();
        $this->assertEquals('delivered', $letter->status);
    }

    public function test_admin_can_manage_mailing_codes_and_rotate_code(): void
    {
        $admin = $this->createAdminUser('admin');
        $user = User::factory()->create();
        $profile = UserProfile::factory()->create(['user_id' => $user->id, 'nickname' => 'Thomas']);
        $code = MailingCode::create([
            'user_id' => $user->id,
            'code' => 'DEARLY-AAAA-BBBB',
            'is_active' => true,
            'permissions_policy' => 'anyone',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.mailing-codes.index'));
        $response->assertStatus(200);
        $response->assertSee('DEARLY-AAAA-BBBB');

        // Rotate
        $rotateResponse = $this->actingAs($admin)->post(route('admin.mailing-codes.rotate', $code->id), [
            'reason' => 'Privacy renewal',
        ]);
        $rotateResponse->assertRedirect();

        $code->refresh();
        $this->assertNotEquals('DEARLY-AAAA-BBBB', $code->code);
    }

    public function test_admin_can_update_and_toggle_stationery_items(): void
    {
        $admin = $this->createAdminUser('admin');
        $theme = StationeryTheme::first();
        $stamp = PostageStamp::first();
        $seal = WaxSeal::first();

        // Toggle theme
        $initialState = $theme->is_active;
        $this->actingAs($admin)->post(route('admin.stationery.theme_toggle', $theme->id))->assertRedirect();
        $theme->refresh();
        $this->assertEquals(!$initialState, $theme->is_active);

        // Update stamp
        $this->actingAs($admin)->put(route('admin.stationery.stamp_update', $stamp->id), [
            'name' => 'Vintage Updated Stamp',
            'theme_category' => 'classical',
            'image_svg_path' => 'stamps/vintage.svg',
        ])->assertRedirect();

        $stamp->refresh();
        $this->assertEquals('Vintage Updated Stamp', $stamp->name);
    }

    public function test_admin_can_view_analytics_and_census(): void
    {
        $admin = $this->createAdminUser('super_admin');
        $response = $this->actingAs($admin)->get(route('admin.analytics.index'));
        $response->assertStatus(200);
        $response->assertSee('Postal Census & Correspondence Velocity Analytics');
        $response->assertSee('Correspondence Velocity');
    }

    public function test_admin_can_dispatch_broadcast_and_preview_transactional_email(): void
    {
        $admin = $this->createAdminUser('super_admin');
        $response = $this->actingAs($admin)->get(route('admin.broadcast.index'));
        $response->assertStatus(200);
        $response->assertSee('Compose Official Postmaster Dispatch');

        // Send broadcast
        $broadcastResp = $this->actingAs($admin)->post(route('admin.broadcast.send'), [
            'title' => 'Sanctuary Postal Maintenance Notice',
            'body' => 'We are conducting gentle archival work this evening.',
            'audience' => 'all',
            'dispatch_method' => 'notification',
        ]);
        $broadcastResp->assertRedirect();
        $broadcastResp->assertSessionHas('status');

        // Preview email
        $previewResp = $this->actingAs($admin)->get(route('admin.broadcast.preview-email', [
            'type' => 'new_letter',
            'locale' => 'bn',
        ]));
        $previewResp->assertStatus(200);
    }

    public function test_admin_can_view_system_health_and_clear_cache(): void
    {
        $admin = $this->createAdminUser('super_admin');
        $response = $this->actingAs($admin)->get(route('admin.system.index'));
        $response->assertStatus(200);
        $response->assertSee('System Health, Engine Diagnostics & Queue Control');

        $clearCache = $this->actingAs($admin)->post(route('admin.system.clear-cache'));
        $clearCache->assertRedirect();
        $clearCache->assertSessionHas('status');
    }

    public function test_moderator_privacy_shield_masks_user_personal_data(): void
    {
        $moderator = $this->createAdminUser('moderator');
        $targetUser = User::factory()->create([
            'name' => 'Alexander Montgomery',
            'email' => 'alexander.montgomery@example.com',
        ]);
        UserProfile::factory()->create([
            'user_id' => $targetUser->id,
            'nickname' => 'Montgomery',
        ]);

        $response = $this->actingAs($moderator)->get(route('admin.users.index'));
        $response->assertStatus(200);
        $response->assertDontSee('alexander.montgomery@example.com');
        $response->assertSee(\Illuminate\Support\Str::mask($targetUser->name, '*', 2)); // Masked name

        $detailResponse = $this->actingAs($moderator)->get(route('admin.users.show', $targetUser->id));
        $detailResponse->assertStatus(200);
        $detailResponse->assertDontSee('alexander.montgomery@example.com');
        $detailResponse->assertSee('Shielded');
    }
}
