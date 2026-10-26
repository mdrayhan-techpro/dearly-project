<?php

namespace App\Http\Controllers;

use App\Models\BlockedUser;
use App\Models\MatchResult;
use App\Models\User;
use App\Models\UserReport;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SafetyController extends Controller
{
    public function blockUser(Request $request, int $userId): RedirectResponse
    {
        $user = auth()->user();

        if ($user->id === $userId) {
            return back()->withErrors(['error' => 'You cannot block your own account.']);
        }

        BlockedUser::firstOrCreate(
            ['user_id' => $user->id, 'blocked_user_id' => $userId],
            ['reason' => $request->input('reason', 'Blocked by user')]
        );

        // Remove active match results
        MatchResult::where(function ($q) use ($user, $userId) {
            $q->where('user_id', $user->id)->where('matched_user_id', $userId);
        })->orWhere(function ($q) use ($user, $userId) {
            $q->where('user_id', $userId)->where('matched_user_id', $user->id);
        })->update(['status' => 'dismissed']);

        return back()->with('status', 'User blocked. They will no longer be able to send letters or appear in your matches.');
    }

    public function unblockUser(int $userId): RedirectResponse
    {
        BlockedUser::where('user_id', auth()->id())
            ->where('blocked_user_id', $userId)
            ->delete();

        return back()->with('status', 'User unblocked.');
    }

    public function report(Request $request): RedirectResponse
    {
        $user = auth()->user();

        $validated = $request->validate([
            'reported_user_id' => ['required', 'exists:users,id'],
            'letter_id' => ['nullable', 'exists:letters,id'],
            'reason' => ['required', 'string', 'in:harassment,spam,inappropriate_content,underage,other'],
            'details' => ['nullable', 'string', 'max:2000'],
        ]);

        UserReport::create([
            'reporter_id' => $user->id,
            'reported_user_id' => $validated['reported_user_id'],
            'letter_id' => $validated['letter_id'] ?? null,
            'reason' => $validated['reason'],
            'details' => $validated['details'],
            'status' => 'open',
        ]);

        return back()->with('status', 'Your safety report has been submitted to the post office administration for urgent review.');
    }
}
