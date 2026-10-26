<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminAuditLog;
use App\Models\UserReport;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminReportController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->query('status', 'open');

        $query = UserReport::with(['reporter.profile', 'reportedUser.profile', 'letter']);
        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $reports = $query->latest()->paginate(15)->withQueryString();

        return view('admin.reports.index', compact('reports', 'status'));
    }

    public function show(int $id): View
    {
        $report = UserReport::with(['reporter.profile', 'reportedUser.profile', 'letter.stationeryTheme'])->findOrFail($id);
        return view('admin.reports.show', compact('report'));
    }

    public function takeAction(Request $request, int $id): RedirectResponse
    {
        $report = UserReport::findOrFail($id);

        $validated = $request->validate([
            'action' => ['required', 'string', 'in:suspend,dismiss,review'],
            'admin_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        if ($validated['action'] === 'suspend') {
            $user = $report->reportedUser;
            $user->update([
                'is_suspended' => true,
                'suspension_reason' => 'Suspended due to safety report #' . $report->id . ': ' . $report->reason,
                'suspended_at' => now(),
            ]);

            if ($user->mailingCode) {
                $user->mailingCode->update(['is_active' => false]);
            }

            $report->update([
                'status' => 'action_taken',
                'admin_notes' => $validated['admin_notes'] ?: 'Reported user has been suspended.',
            ]);
        } elseif ($validated['action'] === 'dismiss') {
            $report->update([
                'status' => 'dismissed',
                'admin_notes' => $validated['admin_notes'] ?: 'Report reviewed and dismissed as non-actionable.',
            ]);
        } else {
            $report->update([
                'status' => 'reviewed',
                'admin_notes' => $validated['admin_notes'] ?: 'Report reviewed by moderator.',
            ]);
        }

        AdminAuditLog::log('report.action', 'UserReport', $report->id, [
            'action' => $validated['action'],
            'admin_notes' => $validated['admin_notes'],
        ]);

        return redirect()->route('admin.reports.index')->with('status', 'Report action completed successfully.');
    }
}
