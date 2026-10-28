<?php

namespace Tests\Feature;

use App\Mail\MatchFoundMail;
use App\Models\MatchResult;
use App\Models\Notification;
use App\Models\Question;
use App\Models\Questionnaire;
use App\Models\QuestionnaireAnswer;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\UserQuestionnaireAttempt;
use App\Services\CompatibilityScoringService;
use Database\Seeders\ApplicationSettingSeeder;
use Database\Seeders\QuestionnaireSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class QuestionnaireAndMatchingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(ApplicationSettingSeeder::class);
        $this->seed(QuestionnaireSeeder::class);
        $this->seed(\Database\Seeders\QuestionnaireTranslationSeeder::class);
    }

    public function test_user_can_view_questionnaire_length_choices(): void
    {
        $user = User::factory()->create();
        UserProfile::factory()->create(['user_id' => $user->id, 'is_onboarded' => true]);

        $this->actingAs($user);

        $response = $this->get('/find-a-pen-friend');
        $response->assertStatus(200);
        $response->assertSee('Choose Your Questionnaire Depth');
        $response->assertSee('20 Questions');
        $response->assertSee('30 Questions');
        $response->assertSee('40 Questions');
        $response->assertSee('50 Questions');
    }

    public function test_user_can_view_questionnaire_with_questions(): void
    {
        $user = User::factory()->create();
        UserProfile::factory()->create(['user_id' => $user->id, 'is_onboarded' => true]);

        $this->actingAs($user);

        $response = $this->get('/questionnaire/20');
        $response->assertStatus(200);
        $response->assertSee('Question 1 of 20');

        $this->assertDatabaseHas('user_questionnaire_attempts', [
            'user_id' => $user->id,
            'questionnaire_length' => 20,
            'is_completed' => false,
        ]);
    }

    public function test_user_can_view_localized_questionnaire_in_bengali_and_other_languages(): void
    {
        $user = User::factory()->create();
        UserProfile::factory()->create(['user_id' => $user->id, 'is_onboarded' => true]);

        $this->actingAs($user);

        // Switch locale to Bengali
        $this->get('/locale/bn');
        $response = $this->get('/questionnaire/20');
        $response->assertStatus(200);
        $response->assertSee('প্রশ্ন 1 এর মধ্যে 20');
        $response->assertSee('উত্তরগুলো সিলমোহর করতে প্রস্তুত?');
        $response->assertSee('জমা দিন ও বন্ধু খুঁজুন');

        // Switch locale to Hindi
        $this->get('/locale/hi');
        $responseHi = $this->get('/questionnaire/20');
        $responseHi->assertStatus(200);
        $responseHi->assertSee('प्रश्न 1 में से 20');
        $responseHi->assertSee('अपने उत्तरों को सील करने के लिए तैयार हैं?');
        $responseHi->assertSee('जमा करें और मित्र खोजें');
    }

    public function test_user_can_autosave_questionnaire_answer_via_ajax(): void
    {
        $user = User::factory()->create();
        UserProfile::factory()->create(['user_id' => $user->id, 'is_onboarded' => true]);

        $this->actingAs($user);

        // Access questionnaire to create attempt
        $this->get('/questionnaire/20');
        $attempt = UserQuestionnaireAttempt::where('user_id', $user->id)->first();

        $question = Question::with('options')->first();
        $option = $question->options->first();

        $response = $this->postJson('/discovery/save-answer', [
            'attempt_id' => $attempt->id,
            'question_id' => $question->id,
            'selected_option_id' => $option->id,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'total_answered' => 1,
                'percent' => 5,
            ]);

        $this->assertDatabaseHas('questionnaire_answers', [
            'attempt_id' => $attempt->id,
            'question_id' => $question->id,
            'selected_option_id' => $option->id,
        ]);
    }

    public function test_user_submits_questionnaire_and_triggers_discovery_engine(): void
    {
        Mail::fake();

        // Create User A
        $userA = User::factory()->create(['email' => 'authorA@dearlyletters.com']);
        UserProfile::factory()->create(['user_id' => $userA->id, 'nickname' => 'PenPalA', 'is_onboarded' => true]);

        // Create Candidate B who has already completed their questionnaire with matching options
        $userB = User::factory()->create(['email' => 'authorB@dearlyletters.com']);
        UserProfile::factory()->create(['user_id' => $userB->id, 'nickname' => 'PenPalB', 'is_onboarded' => true]);

        $questionnaire20 = Questionnaire::where('length', 20)->first();
        $attemptB = UserQuestionnaireAttempt::create([
            'user_id' => $userB->id,
            'questionnaire_id' => $questionnaire20->id,
            'questionnaire_length' => 20,
            'is_completed' => true,
            'completed_at' => now(),
        ]);

        $questions20 = Question::with('options')->take(20)->get();
        foreach ($questions20 as $q) {
            QuestionnaireAnswer::create([
                'attempt_id' => $attemptB->id,
                'question_id' => $q->id,
                'selected_option_id' => $q->options->first()->id,
            ]);
        }

        // Now User A takes the questionnaire and answers identical options (100% match)
        $this->actingAs($userA);
        $this->get('/questionnaire/20');
        $attemptA = UserQuestionnaireAttempt::where('user_id', $userA->id)->first();

        foreach ($questions20 as $q) {
            QuestionnaireAnswer::create([
                'attempt_id' => $attemptA->id,
                'question_id' => $q->id,
                'selected_option_id' => $q->options->first()->id,
            ]);
        }

        $response = $this->post("/questionnaire/20/submit");
        $response->assertRedirect('/matches');

        $attemptA->refresh();
        $this->assertTrue($attemptA->is_completed);

        // Assert qualifying match was saved for both users
        $this->assertDatabaseHas('match_results', [
            'user_id' => $userA->id,
            'matched_user_id' => $userB->id,
            'status' => 'active',
        ]);

        $this->assertDatabaseHas('match_results', [
            'user_id' => $userB->id,
            'matched_user_id' => $userA->id,
            'status' => 'active',
        ]);

        $matchResult = MatchResult::where('user_id', $userA->id)->where('matched_user_id', $userB->id)->first();
        $this->assertGreaterThanOrEqual(90.0, $matchResult->compatibility_score);

        // Assert notification was created
        $this->assertDatabaseHas('notifications', [
            'user_id' => $userA->id,
            'type' => 'match_found',
        ]);

        // Assert MatchFoundMail was queued for both users
        Mail::assertQueued(MatchFoundMail::class, 2);
    }

    public function test_compatibility_scoring_service_rejects_sub_90_percent_scores(): void
    {
        $userA = User::factory()->create();
        UserProfile::factory()->create(['user_id' => $userA->id, 'is_onboarded' => true]);

        $userB = User::factory()->create();
        UserProfile::factory()->create(['user_id' => $userB->id, 'is_onboarded' => true]);

        $questionnaire20 = Questionnaire::where('length', 20)->first();

        $attemptA = UserQuestionnaireAttempt::create([
            'user_id' => $userA->id,
            'questionnaire_id' => $questionnaire20->id,
            'questionnaire_length' => 20,
            'is_completed' => true,
        ]);

        $attemptB = UserQuestionnaireAttempt::create([
            'user_id' => $userB->id,
            'questionnaire_id' => $questionnaire20->id,
            'questionnaire_length' => 20,
            'is_completed' => true,
        ]);

        // Answer opposite options (option 0 vs option 4) to produce maximum distance penalty
        $questions = Question::with('options')->take(20)->get();
        foreach ($questions as $q) {
            QuestionnaireAnswer::create([
                'attempt_id' => $attemptA->id,
                'question_id' => $q->id,
                'selected_option_id' => $q->options->first()->id, // Value index 0
            ]);

            QuestionnaireAnswer::create([
                'attempt_id' => $attemptB->id,
                'question_id' => $q->id,
                'selected_option_id' => $q->options->last()->id, // Value index 4
            ]);
        }

        $service = new CompatibilityScoringService();
        $result = $service->compute($userA, $userB);

        $this->assertNotNull($result);
        $this->assertLessThan(90.0, $result['score']);
        $this->assertFalse($result['is_qualifying']);
    }

    public function test_user_can_view_matches_dashboard_and_dismiss_match(): void
    {
        $userA = User::factory()->create();
        UserProfile::factory()->create(['user_id' => $userA->id, 'is_onboarded' => true]);

        $userB = User::factory()->create();
        UserProfile::factory()->create(['user_id' => $userB->id, 'nickname' => 'RosalindFranklin', 'is_onboarded' => true]);

        $questionnaire = Questionnaire::first();
        UserQuestionnaireAttempt::create([
            'user_id' => $userA->id,
            'questionnaire_id' => $questionnaire->id,
            'questionnaire_length' => 20,
            'is_completed' => true,
        ]);

        $match = MatchResult::create([
            'user_id' => $userA->id,
            'matched_user_id' => $userB->id,
            'compatibility_score' => 94.5,
            'shared_dimensions' => ['literary_taste', 'intellectual_depth'],
            'status' => 'active',
        ]);

        $this->actingAs($userA);

        $response = $this->get('/matches');
        $response->assertStatus(200);
        $response->assertSee('RosalindFranklin');
        $response->assertSee('94.5% Match');

        // Dismiss match
        $dismissResponse = $this->post("/matches/{$match->id}/dismiss");
        $dismissResponse->assertRedirect();

        $match->refresh();
        $this->assertEquals('dismissed', $match->status);
    }
}
