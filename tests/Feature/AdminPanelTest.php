<?php

namespace Tests\Feature;

use App\Models\AdminAuditLog;
use App\Models\ApplicationSetting;
use App\Models\MailingCode;
use App\Models\Question;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\UserReport;
use Database\Seeders\ApplicationSettingSeeder;
use Database\Seeders\QuestionnaireSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPanelTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(ApplicationSettingSeeder::class);
        $this->seed(QuestionnaireSeeder::class);
    }

    public function test_standard_user_cannot_access_admin_dashboard(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        UserProfile::factory()->create(['user_id' => $user->id, 'is_onboarded' => true]);

        $this->actingAs($user);

        $response = $this->get('/admin');
        $response->assertStatus(403);
    }

    public function test_admin_user_can_access_admin_dashboard_and_view_metrics(): void
    {
        $admin = User::factory()->create(['role' => 'super_admin']);
        UserProfile::factory()->create(['user_id' => $admin->id, 'is_onboarded' => true]);

        $this->actingAs($admin);

        $response = $this->get('/admin');
        $response->assertStatus(200);
        $response->assertSee('Postmaster Command');
        $response->assertSee('Total Registered Users');
    }

    public function test_admin_can_view_user_directory_and_suspend_user_with_audit_log(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        UserProfile::factory()->create(['user_id' => $admin->id, 'is_onboarded' => true]);

        $target = User::factory()->create(['role' => 'user']);
        UserProfile::factory()->create(['user_id' => $target->id, 'nickname' => 'ProblemUser', 'is_onboarded' => true]);
        $targetCode = MailingCode::factory()->create([
            'user_id' => $target->id,
            'code' => 'DEARLY-SUSP-1234',
            'is_active' => true,
        ]);

        $this->actingAs($admin);

        // View directory
        $response = $this->get('/admin/users');
        $response->assertStatus(200);
        $response->assertSee('ProblemUser');

        // Inspect user
        $inspectResponse = $this->get("/admin/users/{$target->id}");
        $inspectResponse->assertStatus(200);
        $inspectResponse->assertSee('DEARLY-SUSP-1234');

        // Suspend user
        $suspendResponse = $this->post("/admin/users/{$target->id}/suspend", [
            'reason' => 'Deliberate spamming of postal introductions.',
        ]);

        $suspendResponse->assertRedirect();

        $target->refresh();
        $this->assertTrue($target->is_suspended);
        $this->assertEquals('Deliberate spamming of postal introductions.', $target->suspension_reason);

        // Mailing code deactivated
        $targetCode->refresh();
        $this->assertFalse($targetCode->is_active);

        // Audit log created
        $this->assertDatabaseHas('admin_audit_logs', [
            'admin_id' => $admin->id,
            'action' => 'user.suspend',
            'target_type' => 'User',
            'target_id' => $target->id,
        ]);

        // Reinstate user
        $reinstateResponse = $this->post("/admin/users/{$target->id}/reinstate");
        $reinstateResponse->assertRedirect();

        $target->refresh();
        $this->assertFalse($target->is_suspended);
        $this->assertNull($target->suspension_reason);

        $targetCode->refresh();
        $this->assertTrue($targetCode->is_active);

        $this->assertDatabaseHas('admin_audit_logs', [
            'admin_id' => $admin->id,
            'action' => 'user.reinstate',
            'target_type' => 'User',
            'target_id' => $target->id,
        ]);
    }

    public function test_admin_can_create_new_question_with_options(): void
    {
        $admin = User::factory()->create(['role' => 'super_admin']);
        UserProfile::factory()->create(['user_id' => $admin->id, 'is_onboarded' => true]);

        $this->actingAs($admin);

        $response = $this->post('/admin/questions', [
            'dimension' => 'epistolary_style',
            'question_text' => 'How do you prefer to open a formal letter?',
            'weight' => 1.5,
            'options' => [
                'With a poetic inquiry into the weather and season.',
                'With a warm and direct personal greeting.',
                'With a quote from literature or poetry.',
                'With a reflection on the last time we corresponded.',
                'With a formal epistolary salutation.',
            ],
        ]);

        $response->assertRedirect('/admin/questions');

        $this->assertDatabaseHas('questions', [
            'dimension' => 'epistolary_style',
            'question_text' => 'How do you prefer to open a formal letter?',
        ]);

        $question = Question::where('question_text', 'How do you prefer to open a formal letter?')->first();
        $this->assertNotNull($question);
        $this->assertEquals(5, $question->options()->count());

        $this->assertDatabaseHas('admin_audit_logs', [
            'admin_id' => $admin->id,
            'action' => 'question.create',
            'target_type' => 'Question',
            'target_id' => $question->id,
        ]);
    }

    public function test_admin_can_update_application_settings_and_view_audit_trail(): void
    {
        $admin = User::factory()->create(['role' => 'super_admin']);
        UserProfile::factory()->create(['user_id' => $admin->id, 'is_onboarded' => true]);

        $this->actingAs($admin);

        $response = $this->put('/admin/settings', [
            'site_name' => 'Dearly Correspondence Archive',
            'site_tagline' => 'Handcrafted Letters Across Distance',
            'match_threshold' => 92,
            'max_letters_per_day' => 7,
            'max_first_contacts_per_day' => 3,
            'default_language' => 'en',
            'contact_email' => 'postmaster@dearlyletters.com',
            'registration_enabled' => 1,
            'maintenance_mode' => 0,
        ]);

        $response->assertRedirect();

        $this->assertEquals('Dearly Correspondence Archive', ApplicationSetting::get('site_name'));
        $this->assertEquals('92', ApplicationSetting::get('match_threshold'));
        $this->assertEquals('7', ApplicationSetting::get('max_letters_per_day'));

        // View audit logs
        $auditResponse = $this->get('/admin/audit-logs');
        $auditResponse->assertStatus(200);
        $auditResponse->assertSee('settings.update');
    }

    public function test_user_can_submit_safety_report_and_admin_can_review(): void
    {
        $reporter = User::factory()->create();
        UserProfile::factory()->create(['user_id' => $reporter->id, 'is_onboarded' => true]);

        $reported = User::factory()->create();
        UserProfile::factory()->create(['user_id' => $reported->id, 'is_onboarded' => true]);

        $this->actingAs($reporter);

        // Submit report
        $reportResponse = $this->post('/safety/report', [
            'reported_user_id' => $reported->id,
            'reason' => 'inappropriate_content',
            'details' => 'Received unsolicited spam marketing message.',
        ]);

        $reportResponse->assertRedirect();

        $this->assertDatabaseHas('user_reports', [
            'reporter_id' => $reporter->id,
            'reported_user_id' => $reported->id,
            'reason' => 'inappropriate_content',
            'status' => 'open',
        ]);

        $report = UserReport::where('reporter_id', $reporter->id)->first();

        // Admin reviews and resolves report
        $admin = User::factory()->create(['role' => 'admin']);
        UserProfile::factory()->create(['user_id' => $admin->id, 'is_onboarded' => true]);

        $this->actingAs($admin);

        $reportsIndex = $this->get('/admin/reports');
        $reportsIndex->assertStatus(200);
        $reportsIndex->assertSee('inappropriate content');

        $actionResponse = $this->post("/admin/reports/{$report->id}/action", [
            'action' => 'suspend',
            'admin_notes' => 'Suspended due to spamming behavior.',
        ]);

        $actionResponse->assertRedirect();
        $report->refresh();
        $this->assertEquals('action_taken', $report->status);
        $this->assertEquals('Suspended due to spamming behavior.', $report->admin_notes);
        $this->assertTrue($reported->fresh()->is_suspended);
    }
}
