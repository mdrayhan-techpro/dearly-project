<?php

namespace Database\Seeders;

use App\Models\MailingCode;
use App\Models\Question;
use App\Models\Questionnaire;
use App\Models\QuestionnaireAnswer;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\UserQuestionnaireAttempt;
use App\Services\CompatibilityScoringService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->isProduction()) {
            $this->command?->warn('AdminUserSeeder is disabled in production to protect credentials. Use `php artisan dearly:create-admin` to create administrators.');
            return;
        }

        // 1. Create Super Administrator
        $admin = User::updateOrCreate(
            ['email' => 'admin@dearlyletters.com'],
            [
                'name' => 'Chief Postmaster',
                'password' => Hash::make('DearlyAdmin2026!'),
                'role' => 'super_admin',
                'email_verified_at' => now(),
            ]
        );

        UserProfile::updateOrCreate(
            ['user_id' => $admin->id],
            [
                'nickname' => 'ChiefPostmaster',
                'age' => 38,
                'gender' => 'prefer_not_to_say',
                'preferred_language' => 'en',
                'country' => 'United Kingdom',
                'bio' => 'Custodian of the Dearly postal sanctuary and overseer of correspondence.',
                'interests' => ['Antiquities', 'Postal History', 'Classical Literature', 'Bookbinding'],
                'is_onboarded' => true,
            ]
        );

        MailingCode::updateOrCreate(
            ['user_id' => $admin->id],
            [
                'code' => 'DEARLY-POST-MAST',
                'is_active' => true,
                'permissions_policy' => 'anyone',
            ]
        );

        // 2. Persona 1: Eleanor Vance
        $eleanor = User::updateOrCreate(
            ['email' => 'eleanor@dearlyletters.com'],
            [
                'name' => 'Eleanor Vance',
                'password' => Hash::make('Password123!'),
                'role' => 'user',
                'email_verified_at' => now(),
            ]
        );

        UserProfile::updateOrCreate(
            ['user_id' => $eleanor->id],
            [
                'nickname' => 'EleanorVance',
                'age' => 28,
                'gender' => 'female',
                'preferred_language' => 'en',
                'country' => 'Scotland',
                'bio' => 'Botanist and archivist. Lover of old rainstorms, dried lavender, and fountain pens.',
                'interests' => ['Reading', 'Antique Botany', 'Tea', 'Fountain Pens'],
                'communication_preferences' => ['frequency' => 'biweekly', 'length' => 'detailed'],
                'is_onboarded' => true,
            ]
        );

        MailingCode::updateOrCreate(
            ['user_id' => $eleanor->id],
            [
                'code' => 'DEARLY-ELNR-7K9M',
                'is_active' => true,
                'permissions_policy' => 'anyone',
            ]
        );

        // 3. Persona 2: Julian Cross
        $julian = User::updateOrCreate(
            ['email' => 'julian@dearlyletters.com'],
            [
                'name' => 'Julian Cross',
                'password' => Hash::make('Password123!'),
                'role' => 'user',
                'email_verified_at' => now(),
            ]
        );

        UserProfile::updateOrCreate(
            ['user_id' => $julian->id],
            [
                'nickname' => 'JulianCross',
                'age' => 31,
                'gender' => 'male',
                'preferred_language' => 'en',
                'country' => 'Ireland',
                'bio' => 'Photographer working with 35mm black-and-white film and quiet coastal walks.',
                'interests' => ['Analog Photography', 'Tea', 'Reading', 'Vinyl Records'],
                'communication_preferences' => ['frequency' => 'biweekly', 'length' => 'detailed'],
                'is_onboarded' => true,
            ]
        );

        MailingCode::updateOrCreate(
            ['user_id' => $julian->id],
            [
                'code' => 'DEARLY-JULN-2Q8P',
                'is_active' => true,
                'permissions_policy' => 'anyone',
            ]
        );

        // 4. Persona 3: Sophie Bordeaux
        $sophie = User::updateOrCreate(
            ['email' => 'sophie@dearlyletters.com'],
            [
                'name' => 'Sophie Bordeaux',
                'password' => Hash::make('Password123!'),
                'role' => 'user',
                'email_verified_at' => now(),
            ]
        );

        UserProfile::updateOrCreate(
            ['user_id' => $sophie->id],
            [
                'nickname' => 'SophieBordeaux',
                'age' => 26,
                'gender' => 'female',
                'preferred_language' => 'fr',
                'country' => 'France',
                'bio' => 'Astronomy student painting watercolors of evening constellations.',
                'interests' => ['Astronomy', 'Travel', 'Watercolor', 'Reading'],
                'communication_preferences' => ['frequency' => 'monthly', 'length' => 'reflective'],
                'is_onboarded' => true,
            ]
        );

        MailingCode::updateOrCreate(
            ['user_id' => $sophie->id],
            [
                'code' => 'DEARLY-SOPH-9X4W',
                'is_active' => true,
                'permissions_policy' => 'anyone',
            ]
        );

        // 5. Seed completed 20-question questionnaire attempts for Eleanor and Julian with highly compatible answers
        $q20 = Questionnaire::where('length', 20)->first();
        if ($q20) {
            $questions = Question::with('options')->take(20)->get();

            // Eleanor attempt
            $attemptE = UserQuestionnaireAttempt::firstOrCreate(
                ['user_id' => $eleanor->id, 'questionnaire_id' => $q20->id],
                ['questionnaire_length' => 20, 'is_completed' => true, 'completed_at' => now()]
            );

            // Julian attempt
            $attemptJ = UserQuestionnaireAttempt::firstOrCreate(
                ['user_id' => $julian->id, 'questionnaire_id' => $q20->id],
                ['questionnaire_length' => 20, 'is_completed' => true, 'completed_at' => now()]
            );

            foreach ($questions as $q) {
                $opt = $q->options->first();
                if ($opt) {
                    QuestionnaireAnswer::updateOrCreate(
                        ['attempt_id' => $attemptE->id, 'question_id' => $q->id],
                        ['selected_option_id' => $opt->id]
                    );

                    // Julian selects same or adjacent option for high compatibility
                    QuestionnaireAnswer::updateOrCreate(
                        ['attempt_id' => $attemptJ->id, 'question_id' => $q->id],
                        ['selected_option_id' => $opt->id]
                    );
                }
            }

            // Run discovery service so Eleanor and Julian have an established >90% match!
            $scorer = new CompatibilityScoringService();
            $scorer->runDiscoveryForUser($eleanor);
        }
    }
}
