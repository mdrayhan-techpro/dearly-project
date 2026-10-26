<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Letter;
use App\Models\MatchResult;
use App\Models\User;
use App\Models\UserQuestionnaireAttempt;
use App\Models\UserReport;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function index(): View
    {
        $metrics = [
            'total_users' => User::count(),
            'active_users' => User::where('is_suspended', false)->count(),
            'suspended_users' => User::where('is_suspended', true)->count(),
            'completed_questionnaires' => UserQuestionnaireAttempt::where('is_completed', true)->count(),
            'matches_generated' => MatchResult::count(),
            'letters_sent' => Letter::whereIn('status', ['sent', 'delivered', 'read'])->count(),
            'letters_read' => Letter::where('status', 'read')->count(),
            'drafts_in_progress' => Letter::where('status', 'draft')->count(),
            'open_reports' => UserReport::where('status', 'open')->count(),
        ];

        $recentUsers = User::with('profile', 'mailingCode')->latest()->take(6)->get();
        $recentReports = UserReport::with('reporter.profile', 'reportedUser.profile')->latest()->take(5)->get();

        return view('admin.dashboard', compact('metrics', 'recentUsers', 'recentReports'));
    }
}
