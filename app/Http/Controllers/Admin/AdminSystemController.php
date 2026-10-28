<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminAuditLog;
use App\Models\Letter;
use App\Models\MailingCode;
use App\Models\MatchResult;
use App\Models\Notification;
use App\Models\Question;
use App\Models\QuestionnaireAnswer;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AdminSystemController extends Controller
{
    public function index(): View
    {
        // Diagnostics
        $dbPath = database_path('database.sqlite');
        $dbSizeMb = file_exists($dbPath) ? round(filesize($dbPath) / 1024 / 1024, 2) : 0;

        $diagnostics = [
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'environment' => app()->environment(),
            'debug_mode' => config('app.debug') ? 'Enabled' : 'Disabled',
            'database_driver' => config('database.default'),
            'sqlite_size_mb' => $dbSizeMb,
            'queue_connection' => config('queue.default'),
        ];

        // Database Table Census
        $tableCensus = [
            'Users' => User::count(),
            'Postal Mailing Codes' => MailingCode::count(),
            'Letters & Dispatches' => Letter::count(),
            'Kindred Matches' => MatchResult::count(),
            'Questionnaire Answers' => QuestionnaireAnswer::count(),
            'Discovery Questions' => Question::count(),
            'Postal Notifications' => Notification::count(),
            'Audit Trail Records' => AdminAuditLog::count(),
        ];

        // Queue Health
        $pendingJobs = DB::table('jobs')->count();
        $failedJobs = DB::table('failed_jobs')->count();
        $failedJobsList = DB::table('failed_jobs')->latest('failed_at')->take(5)->get();

        return view('admin.system.index', compact('diagnostics', 'tableCensus', 'pendingJobs', 'failedJobs', 'failedJobsList'));
    }

    public function clearCache(): RedirectResponse
    {
        Artisan::call('cache:clear');
        Artisan::call('view:clear');
        Artisan::call('route:clear');
        Artisan::call('config:clear');

        AdminAuditLog::log('system.clear_cache');

        return back()->with('status', 'Application cache, compiled views, routes, and config cleared successfully.');
    }

    public function optimize(): RedirectResponse
    {
        Artisan::call('optimize');

        AdminAuditLog::log('system.optimize');

        return back()->with('status', 'Framework optimization and route caching completed.');
    }

    public function retryJobs(): RedirectResponse
    {
        Artisan::call('queue:retry', ['id' => ['all']]);

        AdminAuditLog::log('system.queue_retry_all');

        return back()->with('status', 'All failed background jobs have been pushed back into the processing queue.');
    }

    public function flushJobs(): RedirectResponse
    {
        Artisan::call('queue:flush');

        AdminAuditLog::log('system.queue_flush_failed');

        return back()->with('status', 'Failed queue jobs log has been purged.');
    }
}
