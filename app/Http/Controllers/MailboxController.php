<?php

namespace App\Http\Controllers;

use App\Models\Letter;
use App\Models\LetterThread;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MailboxController extends Controller
{
    /**
     * Received letters mailbox (Inbox)
     */
    public function inbox(Request $request): View
    {
        $user = $request->user();
        $filter = $request->query('filter', 'all'); // all, unread, read
        $search = $request->query('q');

        $query = Letter::inboxFor($user->id)
            ->with(['sender.profile', 'stationeryTheme', 'postageStamp', 'waxSeal']);

        if ($filter === 'unread') {
            $query->where('is_read', false);
        } elseif ($filter === 'read') {
            $query->where('is_read', true);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('body', 'like', "%{$search}%")
                    ->orWhereHas('sender.profile', function ($sub) use ($search) {
                        $sub->where('nickname', 'like', "%{$search}%");
                    });
            });
        }

        $letters = $query->paginate(12)->withQueryString();

        return view('mailbox.inbox', compact('letters', 'filter', 'search'));
    }

    /**
     * Sent letters mailbox
     */
    public function sent(Request $request): View
    {
        $user = $request->user();
        $search = $request->query('q');

        $query = Letter::sentBy($user->id)
            ->with(['recipient.profile', 'stationeryTheme', 'postageStamp', 'waxSeal']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('body', 'like', "%{$search}%")
                    ->orWhereHas('recipient.profile', function ($sub) use ($search) {
                        $sub->where('nickname', 'like', "%{$search}%");
                    });
            });
        }

        $letters = $query->paginate(12)->withQueryString();

        return view('mailbox.sent', compact('letters', 'search'));
    }

    /**
     * Drafts mailbox
     */
    public function drafts(Request $request): View
    {
        $user = $request->user();
        $letters = Letter::draftsFor($user->id)
            ->with('recipient.profile')
            ->paginate(12);

        return view('mailbox.drafts', compact('letters'));
    }

    /**
     * Read an individual letter
     * Automatically triggers authentic read receipt if accessed by intended recipient
     */
    public function show(int $id): View
    {
        $user = auth()->user();
        $letter = Letter::with(['sender.profile', 'recipient.profile', 'stationeryTheme', 'postageStamp', 'waxSeal', 'events'])
            ->findOrFail($id);

        if (!$letter->canBeAccessedBy($user->id)) {
            abort(403, 'You do not have postal authorization to read this letter.');
        }

        // Trigger authentic read receipt if opened by the recipient
        if ($letter->recipient_id === $user->id && !$letter->is_read) {
            $letter->markAsRead();
        }

        return view('mailbox.show', compact('letter', 'user'));
    }

    /**
     * View complete correspondence thread between users
     */
    public function thread(int $threadId): View
    {
        $user = auth()->user();
        $thread = LetterThread::with(['participantOne.profile', 'participantTwo.profile', 'letters.stationeryTheme', 'letters.sender.profile'])
            ->findOrFail($threadId);

        if ($thread->participant_one_id !== $user->id && $thread->participant_two_id !== $user->id) {
            abort(403, 'Unauthorized thread access.');
        }

        $otherParticipant = $thread->participant_one_id === $user->id ? $thread->participantTwo : $thread->participantOne;
        $letters = $thread->letters()->forUser($user->id)->get();

        return view('mailbox.thread', compact('thread', 'otherParticipant', 'letters'));
    }

    /**
     * Archive letter for user
     */
    public function archive(int $id): RedirectResponse
    {
        $user = auth()->user();
        $letter = Letter::findOrFail($id);

        if (!$letter->canBeAccessedBy($user->id)) {
            abort(403);
        }

        $letter->update(['status' => 'archived']);

        return back()->with('status', 'Letter archived.');
    }

    /**
     * Delete user's copy of a letter (does not delete recipient's received copy)
     */
    public function destroy(int $id): RedirectResponse
    {
        $user = auth()->user();
        $letter = Letter::findOrFail($id);

        if ($letter->sender_id === $user->id) {
            $letter->update(['sender_deleted' => true]);
        } elseif ($letter->recipient_id === $user->id) {
            $letter->update(['recipient_deleted' => true]);
        } else {
            abort(403);
        }

        return redirect()->route('mailbox.index')->with('status', 'Letter removed from your postbox.');
    }
}
