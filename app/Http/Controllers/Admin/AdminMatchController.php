<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminAuditLog;
use App\Models\MatchResult;
use App\Models\User;
use App\Services\CompatibilityScoringService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminMatchController extends Controller
{
    public function __construct(
        protected CompatibilityScoringService $scoringService
    ) {}

    public function index(Request $request): View
    {
        $query = MatchResult::with([
            'user.profile',
            'user.mailingCode',
            'matchedUser.profile',
            'matchedUser.mailingCode',
        ]);

        // Search by user pseudonym or mailing code
        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->whereHas('user.profile', function ($sub) use ($search) {
                    $sub->where('nickname', 'like', "%{$search}%");
                })->orWhereHas('user.mailingCode', function ($sub) use ($search) {
                    $sub->where('code', 'like', "%{$search}%");
                })->orWhereHas('matchedUser.profile', function ($sub) use ($search) {
                    $sub->where('nickname', 'like', "%{$search}%");
                })->orWhereHas('matchedUser.mailingCode', function ($sub) use ($search) {
                    $sub->where('code', 'like', "%{$search}%");
                });
            });
        }

        // Filter by minimum score
        if ($request->filled('min_score')) {
            $query->where('compatibility_score', '>=', (float) $request->input('min_score'));
        }

        // Filter by status
        if ($request->filled('status') && $request->input('status') !== 'all') {
            $query->where('status', $request->input('status'));
        }

        $matches = $query->orderBy('compatibility_score', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        // Calculate summary statistics
        $stats = [
            'total_matches' => MatchResult::count(),
            'high_compatibility' => MatchResult::where('compatibility_score', '>=', 90)->count(),
            'avg_score' => round((float) MatchResult::avg('compatibility_score'), 1),
            'connected_matches' => MatchResult::where('status', 'connected')->count(),
        ];

        return view('admin.matches.index', compact('matches', 'stats'));
    }

    public function rerun(Request $request): RedirectResponse
    {
        $userId = $request->input('user_id');

        if ($userId) {
            $user = User::findOrFail($userId);
            $newMatches = $this->scoringService->runDiscoveryForUser($user);

            AdminAuditLog::log(
                action: 'match_rerun_single',
                targetType: 'User',
                targetId: $user->id,
                changes: ['matches_found' => $newMatches]
            );

            return back()->with('status', "Kindred matchmaker completed for {$user->nickname}. Generated/refreshed {$newMatches} qualifying pairings.");
        }

        // Global rerun for all users with completed questionnaires
        $eligibleUsers = User::where('is_suspended', false)
            ->whereHas('questionnaireAttempts', function ($q) {
                $q->where('is_completed', true);
            })->get();

        $totalNew = 0;
        foreach ($eligibleUsers as $u) {
            $totalNew += $this->scoringService->runDiscoveryForUser($u);
        }

        AdminAuditLog::log(
            action: 'match_rerun_global',
            changes: ['users_evaluated' => $eligibleUsers->count(), 'total_matches_processed' => $totalNew]
        );

        return back()->with('status', "Global kindred matchmaking cycle complete. Processed {$eligibleUsers->count()} postal members.");
    }
}
