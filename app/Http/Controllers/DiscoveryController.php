<?php

namespace App\Http\Controllers;

use App\Models\CorrespondenceConnection;
use App\Models\MatchResult;
use App\Models\Notification;
use App\Models\Question;
use App\Models\Questionnaire;
use App\Models\QuestionnaireAnswer;
use App\Models\User;
use App\Models\UserQuestionnaireAttempt;
use App\Services\CompatibilityScoringService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DiscoveryController extends Controller
{
    /**
     * Match Discovery Dashboard: displays qualifying matches (score >= 90%)
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        // Check if user has completed a questionnaire
        $latestAttempt = UserQuestionnaireAttempt::where('user_id', $user->id)
            ->where('is_completed', true)
            ->latest()
            ->first();

        $matches = collect();
        if ($latestAttempt) {
            $matches = MatchResult::with('matchedUser.profile', 'matchedUser.mailingCode')
                ->where('user_id', $user->id)
                ->where('status', '!=', 'dismissed')
                ->orderBy('compatibility_score', 'desc')
                ->get();
        }

        return view('discovery.index', compact('latestAttempt', 'matches', 'user'));
    }

    /**
     * Choose Questionnaire Length (20, 30, 40, 50)
     */
    public function chooseLength(): View
    {
        $questionnaires = Questionnaire::where('is_active', true)->orderBy('length')->get();
        return view('discovery.choose-length', compact('questionnaires'));
    }

    /**
     * Take Questionnaire of chosen length
     */
    public function showQuestionnaire(int $length): View|RedirectResponse
    {
        $user = auth()->user();

        $questionnaire = Questionnaire::where('length', $length)
            ->where('is_active', true)
            ->first();

        if (!$questionnaire) {
            return redirect()->route('discovery.choose-length')->withErrors(['length' => 'Invalid questionnaire length selected.']);
        }

        // Find or create in-progress attempt
        $attempt = UserQuestionnaireAttempt::firstOrCreate(
            ['user_id' => $user->id, 'questionnaire_id' => $questionnaire->id, 'is_completed' => false],
            ['questionnaire_length' => $length]
        );

        // Fetch questions for this questionnaire length
        $questions = Question::with('options')
            ->where('is_active', true)
            ->orderBy('order_index')
            ->take($length)
            ->get();

        // Fetch existing answered options
        $answers = QuestionnaireAnswer::where('attempt_id', $attempt->id)
            ->pluck('selected_option_id', 'question_id')
            ->toArray();

        return view('discovery.questionnaire', compact('questionnaire', 'attempt', 'questions', 'answers', 'length'));
    }

    /**
     * Autosave an individual answer
     */
    public function saveAnswer(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'attempt_id' => ['required', 'exists:user_questionnaire_attempts,id'],
            'question_id' => ['required', 'exists:questions,id'],
            'selected_option_id' => ['required', 'exists:question_options,id'],
        ]);

        $attempt = UserQuestionnaireAttempt::where('id', $validated['attempt_id'])
            ->where('user_id', auth()->id())
            ->firstOrFail();

        QuestionnaireAnswer::updateOrCreate(
            ['attempt_id' => $attempt->id, 'question_id' => $validated['question_id']],
            ['selected_option_id' => $validated['selected_option_id']]
        );

        $totalAnswered = QuestionnaireAnswer::where('attempt_id', $attempt->id)->count();

        return response()->json([
            'success' => true,
            'total_answered' => $totalAnswered,
            'percent' => round(($totalAnswered / $attempt->questionnaire_length) * 100),
        ]);
    }

    /**
     * Submit completed questionnaire and trigger compatibility scoring
     */
    public function submitQuestionnaire(Request $request, int $length, CompatibilityScoringService $scoringService): RedirectResponse
    {
        $user = auth()->user();

        $questionnaire = Questionnaire::where('length', $length)->firstOrFail();
        $attempt = UserQuestionnaireAttempt::where('user_id', $user->id)
            ->where('questionnaire_id', $questionnaire->id)
            ->where('is_completed', false)
            ->firstOrFail();

        $questionsCount = min($length, Question::where('is_active', true)->count());
        $answeredCount = QuestionnaireAnswer::where('attempt_id', $attempt->id)->count();

        if ($answeredCount < $questionsCount) {
            return back()->withErrors([
                'completion' => "Please answer all {$questionsCount} questions to complete your questionnaire. You have answered {$answeredCount}.",
            ]);
        }

        $attempt->update([
            'is_completed' => true,
            'completed_at' => now(),
        ]);

        // Run matching engine
        $newMatchesCount = $scoringService->runDiscoveryForUser($user);

        return redirect()->route('discovery.matches')
            ->with('status', "Your questionnaire is sealed! We found {$newMatchesCount} qualifying pen-pal matches with 90%+ compatibility.");
    }

    /**
     * Express interest or establish connection with a match
     */
    public function connect(int $matchId): RedirectResponse
    {
        $user = auth()->user();
        $match = MatchResult::where('id', $matchId)->where('user_id', $user->id)->firstOrFail();

        $otherUser = $match->matchedUser;

        // Establish connection
        CorrespondenceConnection::updateOrCreate(
            [
                'user_one_id' => min($user->id, $otherUser->id),
                'user_two_id' => max($user->id, $otherUser->id),
            ],
            [
                'status' => 'connected',
                'connected_at' => now(),
            ]
        );

        $match->update(['status' => 'connected']);

        // Notify the matched user
        Notification::create([
            'user_id' => $otherUser->id,
            'type' => 'match_found',
            'title' => 'New Pen-Pal Connection Established',
            'message' => "{$user->nickname} has accepted your correspondence match. You may now exchange letters freely!",
            'action_url' => route('letters.compose', ['code' => $user->mailingCode->code]),
        ]);

        return back()->with('status', "Correspondence connection established with {$otherUser->nickname}!");
    }

    /**
     * Dismiss a match suggestion
     */
    public function dismiss(int $matchId): RedirectResponse
    {
        $match = MatchResult::where('id', $matchId)->where('user_id', auth()->id())->firstOrFail();
        $match->update(['status' => 'dismissed']);

        return back()->with('status', 'Match suggestion dismissed.');
    }
}
