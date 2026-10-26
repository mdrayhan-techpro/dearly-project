<?php

namespace App\Services;

use App\Mail\MatchFoundMail;
use App\Models\ApplicationSetting;
use App\Models\MatchResult;
use App\Models\Notification;
use App\Models\Question;
use App\Models\User;
use App\Models\UserQuestionnaireAttempt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CompatibilityScoringService
{
    /**
     * Compute compatibility score between two users based on their latest completed questionnaires.
     * Returns an array with ['score' => float, 'shared_dimensions' => array, 'is_qualifying' => bool]
     */
    public function compute(User $userA, User $userB): ?array
    {
        if ($userA->id === $userB->id) {
            return null;
        }

        // Check if either user is paused from matching or suspended
        if ($userA->is_suspended || $userB->is_suspended) {
            return null;
        }

        if ($userA->profile?->is_matching_paused || $userB->profile?->is_matching_paused) {
            return null;
        }

        // Get latest completed attempts
        $attemptA = UserQuestionnaireAttempt::with('answers.selectedOption', 'answers.question')
            ->where('user_id', $userA->id)
            ->where('is_completed', true)
            ->latest()
            ->first();

        $attemptB = UserQuestionnaireAttempt::with('answers.selectedOption', 'answers.question')
            ->where('user_id', $userB->id)
            ->where('is_completed', true)
            ->latest()
            ->first();

        if (!$attemptA || !$attemptB) {
            return null;
        }

        // Index answers by question_id or by question text / dimension
        $answersA = $attemptA->answers->keyBy('question_id');
        $answersB = $attemptB->answers->keyBy('question_id');

        $totalWeightedScore = 0.0;
        $totalMaxWeight = 0.0;
        $sharedDimensions = [];
        $dimensionScores = [];

        // Loop through questions that both users answered
        foreach ($answersA as $qId => $ansA) {
            if (!isset($answersB[$qId])) {
                continue;
            }

            $ansB = $answersB[$qId];
            $question = $ansA->question;
            if (!$question || !$question->is_active) {
                continue;
            }

            $weight = (float) $question->weight;
            $optA = $ansA->selectedOption;
            $optB = $ansB->selectedOption;

            if (!$optA || !$optB) {
                continue;
            }

            // Calculate similarity:
            // If identical option: 1.0 similarity
            // If adjacent score value (out of 5): distance penalty
            $valA = (int) $optA->score_value;
            $valB = (int) $optB->score_value;
            $maxDistance = 4; // Options 1 to 5
            $diff = abs($valA - $valB);
            $similarity = max(0.0, 1.0 - ($diff / $maxDistance));

            $totalWeightedScore += ($similarity * $weight);
            $totalMaxWeight += $weight;

            $dim = $question->dimension;
            if (!isset($dimensionScores[$dim])) {
                $dimensionScores[$dim] = ['sum' => 0.0, 'count' => 0];
            }
            $dimensionScores[$dim]['sum'] += $similarity;
            $dimensionScores[$dim]['count'] += 1;
        }

        if ($totalMaxWeight <= 0) {
            return null;
        }

        $finalPercentage = round(($totalWeightedScore / $totalMaxWeight) * 100, 2);

        // Identify high-compatibility dimensions (average >= 0.8)
        foreach ($dimensionScores as $dim => $data) {
            $avg = $data['sum'] / max(1, $data['count']);
            if ($avg >= 0.75) {
                $sharedDimensions[] = ucwords(str_replace('_', ' ', $dim));
            }
        }

        $threshold = (float) ApplicationSetting::get('match_threshold', config('dearly.default_match_threshold', 90));
        $isQualifying = $finalPercentage >= $threshold;

        return [
            'score' => $finalPercentage,
            'shared_dimensions' => $sharedDimensions,
            'is_qualifying' => $isQualifying,
            'threshold' => $threshold,
        ];
    }

    /**
     * Run matching for a user against all other completed users and persist qualifying matches
     */
    public function runDiscoveryForUser(User $user): int
    {
        $threshold = (float) ApplicationSetting::get('match_threshold', 90);

        $eligibleUsers = User::where('id', '!=', $user->id)
            ->where('is_suspended', false)
            ->whereHas('profile', function ($q) {
                $q->where('is_matching_paused', false)->where('is_onboarded', true);
            })
            ->whereHas('questionnaireAttempts', function ($q) {
                $q->where('is_completed', true);
            })
            ->get();

        $matchCount = 0;

        foreach ($eligibleUsers as $candidate) {
            // Check if already blocked
            if ($user->hasBlocked($candidate->id) || $candidate->hasBlocked($user->id)) {
                continue;
            }

            $result = $this->compute($user, $candidate);
            if ($result && $result['is_qualifying']) {
                // Upsert match for user -> candidate
                $matchA = MatchResult::updateOrCreate(
                    ['user_id' => $user->id, 'matched_user_id' => $candidate->id],
                    [
                        'compatibility_score' => $result['score'],
                        'shared_dimensions' => $result['shared_dimensions'],
                        'status' => 'active',
                    ]
                );

                // Upsert mirror match for candidate -> user
                $matchB = MatchResult::updateOrCreate(
                    ['user_id' => $candidate->id, 'matched_user_id' => $user->id],
                    [
                        'compatibility_score' => $result['score'],
                        'shared_dimensions' => $result['shared_dimensions'],
                        'status' => 'active',
                    ]
                );

                // Notify both users if recently created
                if ($matchA->wasRecentlyCreated) {
                    Notification::create([
                        'user_id' => $user->id,
                        'type' => 'match_found',
                        'title' => 'A Kindred Spirit Discovered',
                        'message' => "You have a new pen-pal match with {$candidate->nickname} (".round($result['score'])."% compatible).",
                        'action_url' => route('discovery.matches'),
                    ]);

                    try {
                        Mail::to($user->email)->queue(new MatchFoundMail($user, $matchA, $candidate));
                    } catch (\Throwable $e) {
                        Log::warning("Failed to queue MatchFoundMail for user {$user->id}: {$e->getMessage()}");
                    }
                }

                if ($matchB->wasRecentlyCreated) {
                    Notification::create([
                        'user_id' => $candidate->id,
                        'type' => 'match_found',
                        'title' => 'A Kindred Spirit Discovered',
                        'message' => "You have a new pen-pal match with {$user->nickname} (".round($result['score'])."% compatible).",
                        'action_url' => route('discovery.matches'),
                    ]);

                    try {
                        Mail::to($candidate->email)->queue(new MatchFoundMail($candidate, $matchB, $user));
                    } catch (\Throwable $e) {
                        Log::warning("Failed to queue MatchFoundMail for user {$candidate->id}: {$e->getMessage()}");
                    }
                }

                $matchCount++;
            }
        }

        return $matchCount;
    }
}
