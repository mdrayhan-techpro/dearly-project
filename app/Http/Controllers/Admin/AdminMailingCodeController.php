<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminAuditLog;
use App\Models\MailingCode;
use App\Models\User;
use App\Services\MailingCodeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminMailingCodeController extends Controller
{
    public function __construct(
        protected MailingCodeService $mailingCodeService
    ) {}

    public function index(Request $request): View
    {
        $query = MailingCode::with('user.profile');

        // Search by code or user
        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                    ->orWhereHas('user.profile', function ($sub) use ($search) {
                        $sub->where('nickname', 'like', "%{$search}%");
                    })
                    ->orWhereHas('user', function ($sub) use ($search) {
                        $sub->where('email', 'like', "%{$search}%");
                    });
            });
        }

        // Filter by active status
        if ($request->filled('status') && $request->input('status') !== 'all') {
            $isActive = $request->input('status') === 'active';
            $query->where('is_active', $isActive);
        }

        // Filter by policy
        if ($request->filled('policy') && $request->input('policy') !== 'all') {
            $query->where('permissions_policy', $request->input('policy'));
        }

        $codes = $query->latest('updated_at')->paginate(20)->withQueryString();

        $stats = [
            'total' => MailingCode::count(),
            'active' => MailingCode::where('is_active', true)->count(),
            'inactive' => MailingCode::where('is_active', false)->count(),
            'connections_only' => MailingCode::where('permissions_policy', 'accepted_connections')->count(),
        ];

        return view('admin.mailing-codes.index', compact('codes', 'stats'));
    }

    public function toggle(int $id): RedirectResponse
    {
        $code = MailingCode::findOrFail($id);
        $code->update(['is_active' => !$code->is_active]);

        AdminAuditLog::log(
            action: 'mailing_code_toggle',
            targetType: 'MailingCode',
            targetId: $code->id,
            changes: ['is_active' => $code->is_active]
        );

        $state = $code->is_active ? 'activated' : 'deactivated';
        return back()->with('status', "Mailing Code '{$code->code}' has been {$state}.");
    }

    public function rotate(Request $request, int $id): RedirectResponse
    {
        $oldCodeRecord = MailingCode::findOrFail($id);
        $user = $oldCodeRecord->user;

        if (!$user) {
            return back()->withErrors(['user' => 'No postal account associated with this code record.']);
        }

        $oldCodeString = $oldCodeRecord->code;
        $newCodeRecord = $this->mailingCodeService->rotateForUser($user);

        AdminAuditLog::log(
            action: 'mailing_code_rotate',
            targetType: 'User',
            targetId: $user->id,
            changes: [
                'old_code' => $oldCodeString,
                'new_code' => $newCodeRecord->code,
                'reason' => $request->input('reason', 'Administrative re-issuance'),
            ]
        );

        return back()->with('status', "Reissued fresh postal code '{$newCodeRecord->code}' for {$user->nickname}. Old address retired.");
    }

    public function updatePolicy(Request $request, int $id): RedirectResponse
    {
        $request->validate([
            'permissions_policy' => ['required', 'in:anyone,accepted_connections'],
        ]);

        $code = MailingCode::findOrFail($id);
        $oldPolicy = $code->permissions_policy;
        $code->update(['permissions_policy' => $request->input('permissions_policy')]);

        AdminAuditLog::log(
            action: 'mailing_code_policy_change',
            targetType: 'MailingCode',
            targetId: $code->id,
            changes: ['old' => $oldPolicy, 'new' => $code->permissions_policy]
        );

        return back()->with('status', "Permissions policy updated for code '{$code->code}'.");
    }
}
