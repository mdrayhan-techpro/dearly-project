<?php

namespace Tests\Feature;

use App\Mail\LetterReadMail;
use App\Mail\NewLetterMail;
use App\Models\DailySendingCounter;
use App\Models\Letter;
use App\Models\MailingCode;
use App\Models\PostageStamp;
use App\Models\StationeryTheme;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\WaxSeal;
use Database\Seeders\ApplicationSettingSeeder;
use Database\Seeders\StationerySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class MailingCodeAndLetterDeliveryTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(ApplicationSettingSeeder::class);
        $this->seed(StationerySeeder::class);
    }

    public function test_user_can_view_mailing_code_page_and_copy_card(): void
    {
        $user = User::factory()->create();
        UserProfile::factory()->create(['user_id' => $user->id, 'is_onboarded' => true]);
        $code = MailingCode::factory()->create([
            'user_id' => $user->id,
            'code' => 'DEARLY-TEST-1234',
        ]);

        $this->actingAs($user);

        $response = $this->get('/mailing-code');
        $response->assertStatus(200);
        $response->assertSee('DEARLY-TEST-1234');
        $response->assertSee('Private Mailing Code');
    }

    public function test_user_can_rotate_mailing_code_and_retire_old_code(): void
    {
        $user = User::factory()->create();
        UserProfile::factory()->create(['user_id' => $user->id, 'is_onboarded' => true]);
        $oldCode = MailingCode::factory()->create([
            'user_id' => $user->id,
            'code' => 'DEARLY-OLDD-1111',
            'is_active' => true,
        ]);

        $this->actingAs($user);

        $response = $this->post('/mailing-code/rotate');
        $response->assertRedirect();

        $oldCode->refresh();
        $this->assertNotEquals('DEARLY-OLDD-1111', $oldCode->code);
        $this->assertMatchesRegularExpression('/^DEARLY-[A-Z0-9]{4}-[A-Z0-9]{4}$/', $oldCode->code);
    }

    public function test_user_can_update_mailing_code_policy(): void
    {
        $user = User::factory()->create();
        UserProfile::factory()->create(['user_id' => $user->id, 'is_onboarded' => true]);
        $code = MailingCode::factory()->create([
            'user_id' => $user->id,
            'permissions_policy' => 'anyone',
        ]);

        $this->actingAs($user);

        $response = $this->post('/mailing-code/policy', [
            'permissions_policy' => 'accepted_connections',
        ]);

        $response->assertRedirect();
        $code->refresh();
        $this->assertEquals('accepted_connections', $code->permissions_policy);
    }

    public function test_user_can_save_and_duplicate_and_delete_draft(): void
    {
        $user = User::factory()->create();
        UserProfile::factory()->create(['user_id' => $user->id, 'is_onboarded' => true]);
        $theme = StationeryTheme::first();

        $this->actingAs($user);

        // Save new draft
        $response = $this->post('/drafts/save', [
            'title' => 'Musings On Autumn Fog',
            'greeting' => 'Dearest Pen-Pal,',
            'body' => 'The leaves are falling slowly past my windowpane...',
            'signoff' => 'Yours faithfully,',
            'stationery_theme_id' => $theme->id,
        ]);

        $this->assertDatabaseHas('letters', [
            'sender_id' => $user->id,
            'title' => 'Musings On Autumn Fog',
            'status' => 'draft',
        ]);

        $draft = Letter::where('sender_id', $user->id)->first();
        $response->assertRedirect("/letters/{$draft->id}/fold");

        // Duplicate draft
        $dupResponse = $this->post("/drafts/{$draft->id}/duplicate");
        $copy = Letter::where('sender_id', $user->id)->where('id', '!=', $draft->id)->first();
        $dupResponse->assertRedirect("/drafts/{$copy->id}/edit");
        $this->assertEquals(2, Letter::where('sender_id', $user->id)->count());

        // Delete original draft
        $delResponse = $this->delete("/drafts/{$draft->id}");
        $delResponse->assertRedirect('/drafts');
        $this->assertDatabaseMissing('letters', ['id' => $draft->id]);
    }

    public function test_letter_dispatch_and_reading_flow_with_authentic_read_receipt(): void
    {
        Mail::fake();

        $sender = User::factory()->create();
        UserProfile::factory()->create(['user_id' => $sender->id, 'nickname' => 'SenderEvelyn', 'is_onboarded' => true]);

        $recipient = User::factory()->create();
        UserProfile::factory()->create(['user_id' => $recipient->id, 'nickname' => 'RecipientArthur', 'is_onboarded' => true]);
        $recipientCode = MailingCode::factory()->create([
            'user_id' => $recipient->id,
            'code' => 'DEARLY-ARTH-9999',
            'is_active' => true,
        ]);

        $theme = StationeryTheme::first();
        $stamp = PostageStamp::first();
        $seal = WaxSeal::first();

        $draft = Letter::create([
            'sender_id' => $sender->id,
            'recipient_id' => $recipient->id,
            'stationery_theme_id' => $theme->id,
            'title' => 'A Thought On Solitude',
            'greeting' => 'Dear Arthur,',
            'body' => 'I find great comfort in reading quietly under lamplight.',
            'signoff' => 'Warmest regards,',
            'status' => 'draft',
        ]);

        $this->actingAs($sender);

        // Dispatch letter through folding studio send
        $sendResponse = $this->post("/letters/{$draft->id}/send", [
            'recipient_code' => 'DEARLY-ARTH-9999',
            'stationery_theme_id' => $theme->id,
            'postage_stamp_id' => $stamp->id,
            'wax_seal_id' => $seal->id,
        ]);

        $sendResponse->assertRedirect('/letters/sent');

        $draft->refresh();
        $this->assertEquals('delivered', $draft->status);
        $this->assertNotNull($draft->sent_at);
        $this->assertNotNull($draft->delivered_at);
        $this->assertFalse($draft->is_read);

        // Assert notification created for recipient
        $this->assertDatabaseHas('notifications', [
            'user_id' => $recipient->id,
            'type' => 'letter_received',
        ]);

        // Assert NewLetterMail queued
        Mail::assertQueued(NewLetterMail::class);

        // Recipient opens the letter
        $this->actingAs($recipient);

        $readResponse = $this->get("/letters/{$draft->id}");
        $readResponse->assertStatus(200);
        $readResponse->assertSee('A Thought On Solitude');
        $readResponse->assertSee('SenderEvelyn');

        $draft->refresh();
        $this->assertTrue($draft->is_read);
        $this->assertEquals('read', $draft->status);
        $this->assertNotNull($draft->read_at);

        // Assert letter event 'opened' was logged
        $this->assertDatabaseHas('letter_events', [
            'letter_id' => $draft->id,
            'event_type' => 'opened',
        ]);

        // Assert sender notification created
        $this->assertDatabaseHas('notifications', [
            'user_id' => $sender->id,
            'type' => 'letter_read',
        ]);

        // Assert LetterReadMail queued
        Mail::assertQueued(LetterReadMail::class);
    }

    public function test_letter_postal_isolation_prevents_unauthorized_access(): void
    {
        $sender = User::factory()->create();
        $recipient = User::factory()->create();
        $intruder = User::factory()->create();
        UserProfile::factory()->create(['user_id' => $intruder->id, 'is_onboarded' => true]);

        $letter = Letter::create([
            'sender_id' => $sender->id,
            'recipient_id' => $recipient->id,
            'title' => 'Confidential Diary',
            'body' => 'Secret thoughts.',
            'status' => 'delivered',
            'is_read' => false,
        ]);

        $this->actingAs($intruder);

        $response = $this->get("/letters/{$letter->id}");
        $response->assertStatus(403);
    }

    public function test_daily_sending_limit_enforced_at_five_letters_per_day(): void
    {
        $sender = User::factory()->create();
        UserProfile::factory()->create(['user_id' => $sender->id, 'is_onboarded' => true]);

        $recipient = User::factory()->create();
        UserProfile::factory()->create(['user_id' => $recipient->id, 'is_onboarded' => true]);
        $recipientCode = MailingCode::factory()->create([
            'user_id' => $recipient->id,
            'code' => 'DEARLY-LIMT-5555',
            'is_active' => true,
        ]);

        // Simulate 5 letters already sent today
        DailySendingCounter::create([
            'user_id' => $sender->id,
            'date' => now()->toDateString(),
            'letters_sent_count' => 5,
            'first_contacts_count' => 1,
        ]);

        $draft = Letter::create([
            'sender_id' => $sender->id,
            'recipient_id' => $recipient->id,
            'title' => 'The 6th Letter',
            'body' => 'This should be blocked.',
            'status' => 'draft',
        ]);

        $this->actingAs($sender);

        $response = $this->post("/letters/{$draft->id}/send", [
            'recipient_code' => 'DEARLY-LIMT-5555',
        ]);

        $response->assertSessionHasErrors();
        $draft->refresh();
        $this->assertEquals('draft', $draft->status);
    }

    public function test_user_can_delete_their_copy_without_affecting_recipient(): void
    {
        $sender = User::factory()->create();
        $recipient = User::factory()->create();
        UserProfile::factory()->create(['user_id' => $sender->id, 'is_onboarded' => true]);
        UserProfile::factory()->create(['user_id' => $recipient->id, 'is_onboarded' => true]);

        $letter = Letter::create([
            'sender_id' => $sender->id,
            'recipient_id' => $recipient->id,
            'title' => 'Mutual Letter',
            'body' => 'Content here.',
            'status' => 'delivered',
            'sender_deleted' => false,
            'recipient_deleted' => false,
        ]);

        // Sender deletes their copy
        $this->actingAs($sender);
        $response = $this->delete("/letters/{$letter->id}");
        $response->assertRedirect('/letters/received');

        $letter->refresh();
        $this->assertTrue($letter->sender_deleted);
        $this->assertFalse($letter->recipient_deleted);

        // Recipient can still access the letter
        $this->actingAs($recipient);
        $readResponse = $this->get("/letters/{$letter->id}");
        $readResponse->assertStatus(200);
    }
}
