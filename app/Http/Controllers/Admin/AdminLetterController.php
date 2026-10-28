<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminAuditLog;
use App\Models\Letter;
use App\Models\LetterEvent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminLetterController extends Controller
{
    public function index(Request $request): View
    {
        $query = Letter::with([
            'sender.profile',
            'sender.mailingCode',
            'recipient.profile',
            'recipient.mailingCode',
            'stationeryTheme',
            'postageStamp',
            'waxSeal',
        ]);

        // Search by sender/recipient pseudonym, mailing code, or letter title
        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhereHas('sender.profile', function ($sub) use ($search) {
                        $sub->where('nickname', 'like', "%{$search}%");
                    })->orWhereHas('sender.mailingCode', function ($sub) use ($search) {
                        $sub->where('code', 'like', "%{$search}%");
                    })->orWhereHas('recipient.profile', function ($sub) use ($search) {
                        $sub->where('nickname', 'like', "%{$search}%");
                    })->orWhereHas('recipient.mailingCode', function ($sub) use ($search) {
                        $sub->where('code', 'like', "%{$search}%");
                    });
            });
        }

        // Filter by status tab
        $currentStatus = $request->input('status', 'all');
        if ($currentStatus !== 'all') {
            $query->where('status', $currentStatus);
        }

        $letters = $query->latest('created_at')->paginate(15)->withQueryString();

        // Calculate counts for status tabs
        $counts = [
            'all' => Letter::count(),
            'delivered' => Letter::where('status', 'delivered')->count(),
            'read' => Letter::where('status', 'read')->orWhere('is_read', true)->count(),
            'sent' => Letter::where('status', 'sent')->count(),
            'draft' => Letter::where('status', 'draft')->count(),
            'quarantined' => Letter::where('status', 'quarantined')->count(),
        ];

        return view('admin.letters.index', compact('letters', 'counts', 'currentStatus'));
    }

    public function show(int $id): View
    {
        $letter = Letter::with([
            'sender.profile',
            'sender.mailingCode',
            'recipient.profile',
            'recipient.mailingCode',
            'stationeryTheme',
            'postageStamp',
            'waxSeal',
            'events' => function ($q) {
                $q->orderBy('recorded_at', 'asc');
            },
        ])->findOrFail($id);

        return view('admin.letters.show', compact('letter'));
    }

    public function quarantine(Request $request, int $id): RedirectResponse
    {
        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $letter = Letter::findOrFail($id);
        $oldStatus = $letter->status;

        $letter->update(['status' => 'quarantined']);

        LetterEvent::create([
            'letter_id' => $letter->id,
            'event_type' => 'quarantined',
            'recorded_at' => now(),
            'metadata' => [
                'moderator_id' => auth()->id(),
                'reason' => $request->input('reason'),
            ],
        ]);

        AdminAuditLog::log(
            action: 'letter_quarantined',
            targetType: 'Letter',
            targetId: $letter->id,
            changes: [
                'old_status' => $oldStatus,
                'new_status' => 'quarantined',
                'reason' => $request->input('reason'),
            ]
        );

        return back()->with('status', "Letter #{$letter->id} has been placed under postal quarantine.");
    }

    public function release(Request $request, int $id): RedirectResponse
    {
        $letter = Letter::findOrFail($id);
        $letter->update(['status' => 'delivered']);

        LetterEvent::create([
            'letter_id' => $letter->id,
            'event_type' => 'released_from_quarantine',
            'recorded_at' => now(),
            'metadata' => [
                'moderator_id' => auth()->id(),
            ],
        ]);

        AdminAuditLog::log(
            action: 'letter_released',
            targetType: 'Letter',
            targetId: $letter->id,
            changes: ['status' => 'delivered']
        );

        return back()->with('status', "Letter #{$letter->id} released from quarantine and returned to postal delivery.");
    }
}
