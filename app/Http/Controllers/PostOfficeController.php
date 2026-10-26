<?php

namespace App\Http\Controllers;

use App\Models\Letter;
use App\Models\MatchResult;
use App\Models\Notification;
use App\Services\MailingCodeService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PostOfficeController extends Controller
{
    public function index(Request $request, MailingCodeService $mailingCodeService): View
    {
        $user = $request->user();
        $mailingCode = $mailingCodeService->getOrCreateForUser($user);

        // Mail statistics
        $unreadCount = Letter::inboxFor($user->id)->where('is_read', false)->count();
        $receivedCount = Letter::inboxFor($user->id)->count();
        $sentCount = Letter::sentBy($user->id)->count();
        $draftsCount = Letter::draftsFor($user->id)->count();

        // Recent letters
        $recentInbox = Letter::inboxFor($user->id)
            ->with(['sender.profile', 'stationeryTheme', 'postageStamp', 'waxSeal'])
            ->take(5)
            ->get();

        $recentDrafts = Letter::draftsFor($user->id)
            ->with('recipient.profile')
            ->take(3)
            ->get();

        // Qualifying Matches
        $matchesCount = MatchResult::where('user_id', $user->id)
            ->where('status', 'active')
            ->count();

        // Unread notifications
        $unreadNotifications = Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->latest()
            ->take(5)
            ->get();

        return view('post-office.index', compact(
            'user',
            'mailingCode',
            'unreadCount',
            'receivedCount',
            'sentCount',
            'draftsCount',
            'matchesCount',
            'recentInbox',
            'recentDrafts',
            'unreadNotifications'
        ));
    }
}
