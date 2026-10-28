<?php

namespace Tests\Feature;

use App\Models\MailingCode;
use App\Models\Notification;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotificationAndDeviceAlertsTest extends TestCase
{
    use RefreshDatabase;

    protected function createOnboardedUser(): User
    {
        $user = User::factory()->create();
        UserProfile::factory()->create(['user_id' => $user->id, 'is_onboarded' => true]);
        MailingCode::factory()->create(['user_id' => $user->id]);
        return $user;
    }

    public function test_guest_cannot_access_notifications_feed(): void
    {
        $response = $this->getJson(route('notifications.feed'));
        $response->assertStatus(401);
    }

    public function test_user_can_fetch_notifications_feed(): void
    {
        $user = $this->createOnboardedUser();

        Notification::create([
            'user_id' => $user->id,
            'type' => 'letter_received',
            'title' => 'A Letter Has Arrived',
            'message' => 'A wax-sealed parchment from your pen friend.',
            'action_url' => '/letters/received',
            'is_read' => false,
        ]);

        $response = $this->actingAs($user)->getJson(route('notifications.feed'));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'unread_count',
            'notifications' => [
                '*' => [
                    'id',
                    'type',
                    'title',
                    'message',
                    'action_url',
                    'is_read',
                    'time_ago',
                ]
            ]
        ]);

        $this->assertEquals(1, $response->json('unread_count'));
        $this->assertEquals('A Letter Has Arrived', $response->json('notifications.0.title'));
    }

    public function test_user_can_mark_notification_as_read_via_ajax(): void
    {
        $user = $this->createOnboardedUser();

        $notif = Notification::create([
            'user_id' => $user->id,
            'type' => 'letter_read',
            'title' => 'Letter Opened',
            'message' => 'Your letter was opened and read.',
            'is_read' => false,
        ]);

        $response = $this->actingAs($user)->postJson(route('notifications.read', $notif->id));

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'unread_count' => 0,
        ]);

        $this->assertTrue($notif->fresh()->is_read);
    }

    public function test_user_can_mark_all_notifications_as_read_via_ajax(): void
    {
        $user = $this->createOnboardedUser();

        Notification::create([
            'user_id' => $user->id,
            'type' => 'system',
            'title' => 'Welcome',
            'message' => 'Welcome to Dearly.',
            'is_read' => false,
        ]);

        Notification::create([
            'user_id' => $user->id,
            'type' => 'match_found',
            'title' => 'Match Found',
            'message' => 'A new kindred pen pal has been found.',
            'is_read' => false,
        ]);

        $response = $this->actingAs($user)->postJson(route('notifications.read-all'));

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'unread_count' => 0,
        ]);

        $this->assertEquals(0, Notification::where('user_id', $user->id)->where('is_read', false)->count());
    }

    public function test_static_assets_and_pwa_manifest_are_present(): void
    {
        $this->assertFileExists(public_path('favicon.ico'));
        $this->assertFileExists(public_path('favicon-32x32.png'));
        $this->assertFileExists(public_path('favicon-16x16.png'));
        $this->assertFileExists(public_path('apple-touch-icon.png'));
        $this->assertFileExists(public_path('site.webmanifest'));
        $this->assertFileExists(public_path('sw.js'));
    }
}
