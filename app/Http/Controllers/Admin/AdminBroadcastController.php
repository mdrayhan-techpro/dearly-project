<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\LetterReadMail;
use App\Mail\MatchFoundMail;
use App\Mail\NewLetterMail;
use App\Models\AdminAuditLog;
use App\Models\Letter;
use App\Models\LetterThread;
use App\Models\MatchResult;
use App\Models\Notification;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class AdminBroadcastController extends Controller
{
    public function index(Request $request): View
    {
        $recentBroadcasts = AdminAuditLog::where('action', 'postmaster_broadcast')
            ->latest('created_at')
            ->take(10)
            ->get();

        $activeUsersCount = User::where('is_suspended', false)->count();

        return view('admin.broadcast.index', compact('recentBroadcasts', 'activeUsersCount'));
    }

    public function send(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'audience' => 'required|string', // 'all', or locale 'en', 'bn', etc.
            'dispatch_method' => 'required|in:notification,letter,both',
        ]);

        $query = User::where('is_suspended', false);

        if ($validated['audience'] !== 'all') {
            $lang = $validated['audience'];
            $query->whereHas('profile', function ($q) use ($lang) {
                $q->where('preferred_language', $lang);
            });
        }

        $recipients = $query->get();
        $sender = auth()->user();

        $sentCount = 0;

        foreach ($recipients as $recipient) {
            // Dispatch in-app notification
            if (in_array($validated['dispatch_method'], ['notification', 'both'])) {
                Notification::create([
                    'user_id' => $recipient->id,
                    'type' => 'broadcast',
                    'title' => $validated['title'],
                    'message' => $validated['body'],
                    'action_url' => route('post-office.index'),
                    'is_read' => false,
                ]);
            }

            // Dispatch official postal letter to inbox
            if (in_array($validated['dispatch_method'], ['letter', 'both'])) {
                $thread = LetterThread::findOrCreateBetween($sender->id, $recipient->id);
                $thread->update(['last_letter_at' => now()]);

                Letter::create([
                    'thread_id' => $thread->id,
                    'sender_id' => $sender->id,
                    'recipient_id' => $recipient->id,
                    'title' => $validated['title'],
                    'body' => $validated['body'],
                    'greeting' => 'To an Esteemed Member of the Dearly Sanctuary,',
                    'signoff' => 'Yours in slow correspondence, The Postmaster General',
                    'status' => 'delivered',
                    'is_read' => false,
                    'sent_at' => now(),
                    'delivered_at' => now(),
                ]);
            }

            $sentCount++;
        }

        AdminAuditLog::log(
            action: 'postmaster_broadcast',
            targetType: 'Broadcast',
            changes: [
                'title' => $validated['title'],
                'audience' => $validated['audience'],
                'method' => $validated['dispatch_method'],
                'recipients_count' => $sentCount,
            ]
        );

        return back()->with('status', "Postmaster announcement successfully broadcasted to {$sentCount} members.");
    }

    public function previewEmail(Request $request): Response
    {
        $type = $request->input('type', 'new_letter');
        $locale = $request->input('locale', 'en');

        app()->setLocale($locale);

        // Build mock user & letter for preview
        $mockRecipient = new User(['name' => 'Cordelia Grey', 'email' => 'cordelia@example.com']);
        $mockRecipient->id = 1;
        $mockRecipient->setRelation('profile', new UserProfile(['nickname' => 'Cordelia', 'preferred_language' => $locale]));

        $mockSender = new User(['name' => 'Arthur Pendelton', 'email' => 'arthur@example.com']);
        $mockSender->id = 2;
        $mockSender->setRelation('profile', new UserProfile(['nickname' => 'Arthur', 'preferred_language' => $locale]));

        $mockLetter = new Letter([
            'title' => 'An Autumn Evening in Oxford',
            'body' => 'The leaves have turned an exquisite amber across the quadrangle. I thought of your reflections on quiet literature.',
            'sent_at' => now()->subHours(4),
            'delivered_at' => now()->subMinutes(15),
            'read_at' => now(),
        ]);
        $mockLetter->id = 101;
        $mockLetter->setRelation('sender', $mockSender);
        $mockLetter->setRelation('recipient', $mockRecipient);


        $mailable = match ($type) {
            'letter_read' => new LetterReadMail($mockSender, $mockLetter),
            'match_found' => new MatchFoundMail($mockRecipient, new MatchResult([
                'user_id' => 1,
                'matched_user_id' => 2,
                'compatibility_score' => 93.4,
                'shared_dimensions' => ['Solitude & Contemplation', 'Epistolary Literature', 'Classical Music'],
            ])),
            default => new NewLetterMail($mockRecipient, $mockLetter),
        };

        return response($mailable->render());
    }
}
