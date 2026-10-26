<?php

use App\Http\Controllers\Admin\AdminAuditLogController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminQuestionnaireController;
use App\Http\Controllers\Admin\AdminReportController;
use App\Http\Controllers\Admin\AdminSettingController;
use App\Http\Controllers\Admin\AdminStationeryController;
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

    // User Management
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/{id}', [AdminUserController::class, 'show'])->name('users.show');
    Route::post('/users/{id}/suspend', [AdminUserController::class, 'suspend'])->name('users.suspend');
    Route::post('/users/{id}/reinstate', [AdminUserController::class, 'reinstate'])->name('users.reinstate');
    Route::put('/users/{id}/role', [AdminUserController::class, 'updateRole'])->name('users.role');

    // Questions & Matching
    Route::get('/questions', [AdminQuestionnaireController::class, 'index'])->name('questions.index');
    Route::get('/questions/create', [AdminQuestionnaireController::class, 'create'])->name('questions.create');
    Route::post('/questions', [AdminQuestionnaireController::class, 'store'])->name('questions.store');
    Route::get('/questions/{id}/edit', [AdminQuestionnaireController::class, 'edit'])->name('questions.edit');
    Route::put('/questions/{id}', [AdminQuestionnaireController::class, 'update'])->name('questions.update');
    Route::post('/questions/{id}/toggle', [AdminQuestionnaireController::class, 'toggleActive'])->name('questions.toggle');

    // Stationery & Stamps
    Route::get('/stationery', [AdminStationeryController::class, 'index'])->name('stationery.index');
    Route::post('/stationery/themes', [AdminStationeryController::class, 'storeTheme'])->name('stationery.theme_store');
    Route::post('/stationery/stamps', [AdminStationeryController::class, 'storeStamp'])->name('stationery.stamp_store');
    Route::post('/stationery/seals', [AdminStationeryController::class, 'storeSeal'])->name('stationery.seal_store');

    // Moderation & Reports
    Route::get('/reports', [AdminReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/{id}', [AdminReportController::class, 'show'])->name('reports.show');
    Route::post('/reports/{id}/action', [AdminReportController::class, 'takeAction'])->name('reports.action');

    // Platform Settings & Audit Logs (Super Admin & Admin only)
    Route::middleware(['role:super_admin,admin'])->group(function () {
        Route::get('/settings', [AdminSettingController::class, 'index'])->name('settings.index');
        Route::put('/settings', [AdminSettingController::class, 'update'])->name('settings.update');
        Route::get('/audit-logs', [AdminAuditLogController::class, 'index'])->name('audit-logs.index');
    });
});
