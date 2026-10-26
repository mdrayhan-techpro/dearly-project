<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminAuditLog;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminUserController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->query('q');
        $role = $request->query('role');
        $status = $request->query('status');

        $query = User::with('profile', 'mailingCode');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhereHas('profile', function ($sub) use ($search) {
                        $sub->where('nickname', 'like', "%{$search}%");
                    })
                    ->orWhereHas('mailingCode', function ($sub) use ($search) {
                        $sub->where('code', 'like', "%{$search}%");
                    });
            });
        }

        if ($role) {
            $query->where('role', $role);
        }

        if ($status === 'suspended') {
            $query->where('is_suspended', true);
        } elseif ($status === 'active') {
            $query->where('is_suspended', false);
        }

        $users = $query->latest()->paginate(15)->withQueryString();

        return view('admin.users.index', compact('users', 'search', 'role', 'status'));
    }

    public function show(int $id): View
    {
        $user = User::with(['profile', 'mailingCode', 'questionnaireAttempts.questionnaire'])->findOrFail($id);
        $sentCount = $user->sentLetters()->count();
        $receivedCount = $user->receivedLetters()->count();
        $matchesCount = $user->matches()->count();

        return view('admin.users.show', compact('user', 'sentCount', 'receivedCount', 'matchesCount'));
    }

    public function suspend(Request $request, int $id): RedirectResponse
    {
        $targetUser = User::findOrFail($id);

        if ($targetUser->isSuperAdmin()) {
            return back()->withErrors(['error' => 'Super Administrator accounts cannot be suspended.']);
        }

        $reason = $request->input('reason', 'Administrative suspension for platform safety.');

        $before = ['is_suspended' => $targetUser->is_suspended, 'reason' => $targetUser->suspension_reason];
        $targetUser->update([
            'is_suspended' => true,
            'suspension_reason' => $reason,
            'suspended_at' => now(),
        ]);

        if ($targetUser->mailingCode) {
            $targetUser->mailingCode->update(['is_active' => false]);
        }

        AdminAuditLog::log('user.suspend', 'User', $targetUser->id, [
            'before' => $before,
            'after' => ['is_suspended' => true, 'reason' => $reason],
        ]);

        return back()->with('status', "User [{$targetUser->nickname}] has been suspended.");
    }

    public function reinstate(int $id): RedirectResponse
    {
        $targetUser = User::findOrFail($id);

        $before = ['is_suspended' => $targetUser->is_suspended];
        $targetUser->update([
            'is_suspended' => false,
            'suspension_reason' => null,
            'suspended_at' => null,
        ]);

        if ($targetUser->mailingCode) {
            $targetUser->mailingCode->update(['is_active' => true]);
        }

        AdminAuditLog::log('user.reinstate', 'User', $targetUser->id, [
            'before' => $before,
            'after' => ['is_suspended' => false],
        ]);

        return back()->with('status', "User [{$targetUser->nickname}] has been reinstated.");
    }

    public function updateRole(Request $request, int $id): RedirectResponse
    {
        $targetUser = User::findOrFail($id);

        $validated = $request->validate([
            'role' => ['required', 'string', 'in:user,moderator,admin,super_admin'],
        ]);

        if (!auth()->user()->isSuperAdmin() && in_array($validated['role'], ['admin', 'super_admin'])) {
            return back()->withErrors(['error' => 'Only Super Administrators can promote users to Administrator levels.']);
        }

        $beforeRole = $targetUser->role;
        $targetUser->update(['role' => $validated['role']]);

        AdminAuditLog::log('user.role_change', 'User', $targetUser->id, [
            'before' => ['role' => $beforeRole],
            'after' => ['role' => $validated['role']],
        ]);

        return back()->with('status', "User role updated to [{$validated['role']}].");
    }
}
