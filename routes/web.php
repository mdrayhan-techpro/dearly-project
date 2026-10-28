<?php

use App\Http\Controllers\Admin\AdminAnalyticsController;
use App\Http\Controllers\Admin\AdminAuditLogController;
use App\Http\Controllers\Admin\AdminBroadcastController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminLetterController;
use App\Http\Controllers\Admin\AdminMailingCodeController;
use App\Http\Controllers\Admin\AdminMatchController;
use App\Http\Controllers\Admin\AdminQuestionnaireController;
use App\Http\Controllers\Admin\AdminReportController;
use App\Http\Controllers\Admin\AdminSettingController;
use App\Http\Controllers\Admin\AdminStationeryController;
use App\Http\Controllers\Admin\AdminSystemController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Auth\AuthController;

use App\Http\Controllers\DiscoveryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LetterComposerController;
use App\Http\Controllers\MailboxController;
use App\Http\Controllers\MailingCodeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\PostOfficeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SafetyController;
use Illuminate\Support\Facades\Route;

// Public Informational Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/how-it-works', [HomeController::class, 'howItWorks'])->name('how-it-works');
Route::get('/privacy', [HomeController::class, 'privacy'])->name('privacy');
Route::get('/terms', [HomeController::class, 'terms'])->name('terms');
Route::get('/community-guidelines', [HomeController::class, 'guidelines'])->name('guidelines');
Route::get('/faq', [HomeController::class, 'faq'])->name('faq');

// Language Switcher
Route::get('/locale/{locale}', [ProfileController::class, 'switchLanguage'])->name('locale.switch');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.submit');

    // Google OAuth
    Route::get('/auth/google/redirect', [AuthController::class, 'googleRedirect'])->name('auth.google.redirect');
    Route::get('/auth/google/callback', [AuthController::class, 'googleCallback'])->name('auth.google.callback');

    // Developer / Test Persona Quick-Login
    Route::post('/dev-login', [AuthController::class, 'devLogin'])->name('dev.login');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Onboarding Routes (Auth required, but profile may be incomplete)
Route::middleware(['auth'])->group(function () {
    Route::get('/onboarding', [OnboardingController::class, 'index'])->name('onboarding.index');
    Route::post('/onboarding', [OnboardingController::class, 'store'])->name('onboarding.store');
});

// Authenticated Application Routes (Requires completed profile)
Route::middleware(['auth', 'profile.complete'])->group(function () {
    // Post Office Dashboard
    Route::get('/post-office', [PostOfficeController::class, 'index'])->name('post-office.index');

    // Discovery & Compatibility Questionnaires
    Route::get('/find-a-pen-friend', [DiscoveryController::class, 'chooseLength'])->name('discovery.choose-length');
    Route::get('/questionnaire/{length}', [DiscoveryController::class, 'showQuestionnaire'])->name('discovery.questionnaire');
    Route::post('/questionnaire/{length}/submit', [DiscoveryController::class, 'submitQuestionnaire'])->name('discovery.submit');
    Route::post('/discovery/save-answer', [DiscoveryController::class, 'saveAnswer'])->name('discovery.save-answer');
    Route::get('/matches', [DiscoveryController::class, 'index'])->name('discovery.matches');
    Route::get('/discovery', [DiscoveryController::class, 'index'])->name('discovery.index');
    Route::post('/matches/{id}/connect', [DiscoveryController::class, 'connect'])->name('discovery.connect');
    Route::post('/matches/{id}/dismiss', [DiscoveryController::class, 'dismiss'])->name('discovery.dismiss');

    // Mailing Code
    Route::get('/mailing-code', [MailingCodeController::class, 'show'])->name('mailing-code.show');
    Route::post('/mailing-code/rotate', [MailingCodeController::class, 'rotate'])->name('mailing-code.rotate');
    Route::post('/mailing-code/policy', [MailingCodeController::class, 'updatePolicy'])->name('mailing-code.policy');

    // Letter Writing Studio & Drafts
    Route::get('/write-letter', [LetterComposerController::class, 'create'])->name('letters.compose');
    Route::post('/drafts/save', [LetterComposerController::class, 'saveDraft'])->name('drafts.save');
    Route::get('/drafts/{id}/edit', [LetterComposerController::class, 'editDraft'])->name('drafts.edit');
    Route::post('/drafts/{id}/duplicate', [LetterComposerController::class, 'duplicateDraft'])->name('drafts.duplicate');
    Route::delete('/drafts/{id}', [LetterComposerController::class, 'destroyDraft'])->name('drafts.destroy');

    // Envelope Folding Studio & Dispatches
    Route::get('/letters/{id}/fold', [LetterComposerController::class, 'foldStudio'])->name('letters.fold');
    Route::post('/letters/{id}/send', [LetterComposerController::class, 'send'])->name('letters.send');

    // Mailbox Views
    Route::get('/letters/received', [MailboxController::class, 'inbox'])->name('mailbox.index');
    Route::get('/letters/sent', [MailboxController::class, 'sent'])->name('mailbox.sent');
    Route::get('/drafts', [MailboxController::class, 'drafts'])->name('mailbox.drafts');
    Route::get('/letters/{id}', [MailboxController::class, 'show'])->name('letters.show');
    Route::get('/mailbox/letters/{id}', [MailboxController::class, 'show'])->name('mailbox.show');
    Route::post('/letters/{id}/archive', [MailboxController::class, 'archive'])->name('letters.archive');
    Route::delete('/letters/{id}', [MailboxController::class, 'destroy'])->name('letters.destroy');
    Route::get('/threads/{id}', [MailboxController::class, 'thread'])->name('letters.thread');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/feed', [NotificationController::class, 'feed'])->name('notifications.feed');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');

    // Profile & Privacy
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile/delete-account', [ProfileController::class, 'destroyAccount'])->name('profile.destroy');

    // Safety & Moderation
    Route::post('/safety/block/{userId}', [SafetyController::class, 'blockUser'])->name('safety.block');
    Route::post('/safety/unblock/{userId}', [SafetyController::class, 'unblockUser'])->name('safety.unblock');
    Route::post('/safety/report', [SafetyController::class, 'report'])->name('safety.report');
});

// Admin Panel Routes
Route::prefix('admin')->middleware(['auth', 'role:super_admin,admin,moderator'])->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/analytics', [AdminAnalyticsController::class, 'index'])->name('analytics.index');

    // User Management
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/{id}', [AdminUserController::class, 'show'])->name('users.show');
    Route::post('/users/{id}/suspend', [AdminUserController::class, 'suspend'])->name('users.suspend');
    Route::post('/users/{id}/reinstate', [AdminUserController::class, 'reinstate'])->name('users.reinstate');
    Route::put('/users/{id}/role', [AdminUserController::class, 'updateRole'])->name('users.role');

    // Mailing Code Fleet
    Route::get('/mailing-codes', [AdminMailingCodeController::class, 'index'])->name('mailing-codes.index');
    Route::post('/mailing-codes/{id}/toggle', [AdminMailingCodeController::class, 'toggle'])->name('mailing-codes.toggle');
    Route::post('/mailing-codes/{id}/rotate', [AdminMailingCodeController::class, 'rotate'])->name('mailing-codes.rotate');
    Route::put('/mailing-codes/{id}/policy', [AdminMailingCodeController::class, 'updatePolicy'])->name('mailing-codes.policy');

    // Postal Traffic & Letter Ledger
    Route::get('/letters', [AdminLetterController::class, 'index'])->name('letters.index');
    Route::get('/letters/{id}', [AdminLetterController::class, 'show'])->name('letters.show');
    Route::post('/letters/{id}/quarantine', [AdminLetterController::class, 'quarantine'])->name('letters.quarantine');
    Route::post('/letters/{id}/release', [AdminLetterController::class, 'release'])->name('letters.release');

    // Kindred Matches & Compatibility Explorer
    Route::get('/matches', [AdminMatchController::class, 'index'])->name('matches.index');
    Route::post('/matches/rerun', [AdminMatchController::class, 'rerun'])->name('matches.rerun');

    // Questions & Matching Engine
    Route::get('/questions', [AdminQuestionnaireController::class, 'index'])->name('questions.index');
    Route::get('/questions/create', [AdminQuestionnaireController::class, 'create'])->name('questions.create');
    Route::post('/questions', [AdminQuestionnaireController::class, 'store'])->name('questions.store');
    Route::get('/questions/{id}/edit', [AdminQuestionnaireController::class, 'edit'])->name('questions.edit');
    Route::put('/questions/{id}', [AdminQuestionnaireController::class, 'update'])->name('questions.update');
    Route::post('/questions/{id}/order', [AdminQuestionnaireController::class, 'updateOrder'])->name('questions.order');
    Route::post('/questions/{id}/toggle', [AdminQuestionnaireController::class, 'toggleActive'])->name('questions.toggle');

    // Stationery, Stamps & Wax Seals (with Edit, Toggle, and Delete)
    Route::get('/stationery', [AdminStationeryController::class, 'index'])->name('stationery.index');
    Route::post('/stationery/themes', [AdminStationeryController::class, 'storeTheme'])->name('stationery.theme_store');
    Route::put('/stationery/themes/{id}', [AdminStationeryController::class, 'updateTheme'])->name('stationery.theme_update');
    Route::post('/stationery/themes/{id}/toggle', [AdminStationeryController::class, 'toggleTheme'])->name('stationery.theme_toggle');
    Route::delete('/stationery/themes/{id}', [AdminStationeryController::class, 'destroyTheme'])->name('stationery.theme_destroy');

    Route::post('/stationery/stamps', [AdminStationeryController::class, 'storeStamp'])->name('stationery.stamp_store');
    Route::put('/stationery/stamps/{id}', [AdminStationeryController::class, 'updateStamp'])->name('stationery.stamp_update');
    Route::post('/stationery/stamps/{id}/toggle', [AdminStationeryController::class, 'toggleStamp'])->name('stationery.stamp_toggle');
    Route::delete('/stationery/stamps/{id}', [AdminStationeryController::class, 'destroyStamp'])->name('stationery.stamp_destroy');

    Route::post('/stationery/seals', [AdminStationeryController::class, 'storeSeal'])->name('stationery.seal_store');
    Route::put('/stationery/seals/{id}', [AdminStationeryController::class, 'updateSeal'])->name('stationery.seal_update');
    Route::post('/stationery/seals/{id}/toggle', [AdminStationeryController::class, 'toggleSeal'])->name('stationery.seal_toggle');
    Route::delete('/stationery/seals/{id}', [AdminStationeryController::class, 'destroySeal'])->name('stationery.seal_destroy');

    // Postmaster Broadcast & Mailroom
    Route::get('/broadcast', [AdminBroadcastController::class, 'index'])->name('broadcast.index');
    Route::post('/broadcast/send', [AdminBroadcastController::class, 'send'])->name('broadcast.send');
    Route::get('/broadcast/preview-email', [AdminBroadcastController::class, 'previewEmail'])->name('broadcast.preview-email');

    // Moderation & Reports
    Route::get('/reports', [AdminReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/{id}', [AdminReportController::class, 'show'])->name('reports.show');
    Route::post('/reports/{id}/action', [AdminReportController::class, 'takeAction'])->name('reports.action');

    // Platform Settings, Health & Audit Logs (Super Admin & Admin only)
    Route::middleware(['role:super_admin,admin'])->group(function () {
        Route::get('/settings', [AdminSettingController::class, 'index'])->name('settings.index');
        Route::put('/settings', [AdminSettingController::class, 'update'])->name('settings.update');
        Route::post('/settings/auto-translate', [AdminSettingController::class, 'autoTranslate'])->name('settings.auto-translate');

        Route::get('/system-health', [AdminSystemController::class, 'index'])->name('system.index');
        Route::post('/system-health/clear-cache', [AdminSystemController::class, 'clearCache'])->name('system.clear-cache');
        Route::post('/system-health/optimize', [AdminSystemController::class, 'optimize'])->name('system.optimize');
        Route::post('/system-health/retry-jobs', [AdminSystemController::class, 'retryJobs'])->name('system.retry-jobs');
        Route::post('/system-health/flush-jobs', [AdminSystemController::class, 'flushJobs'])->name('system.flush-jobs');

        Route::get('/audit-logs', [AdminAuditLogController::class, 'index'])->name('audit-logs.index');
    });
});

