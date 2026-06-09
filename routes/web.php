<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Admin\VillaBitDashboardController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\ImpersonationController;
use App\Http\Controllers\Admin\AdminAgencyController;
use App\Http\Controllers\Admin\AdminInvestorController;
use App\Http\Controllers\Admin\AdminManagerController;
use App\Http\Controllers\Admin\AdminInvestmentProjectController;
use App\Http\Controllers\Admin\AdminCapitalCallController;
use App\Http\Controllers\Admin\AdminInvestorPayoutController;
use App\Http\Controllers\Admin\AdminAiReportController;
use App\Http\Controllers\Admin\AdminUsageLimitController;
use App\Http\Controllers\Admin\AdminAffiliateController;
use App\Http\Controllers\Manager\ManagerDashboardController;
use App\Http\Controllers\Manager\ManagerAgencyController;
use App\Http\Controllers\Manager\ManagerInvestorController;
use App\Http\Controllers\Manager\ManagerAiReportController;
use App\Http\Controllers\Manager\ManagerSupportNoteController;
use App\Http\Controllers\Agency\AgencyDashboardController;
use App\Http\Controllers\Agency\AgencySettingsController;
use App\Http\Controllers\Agency\AgencyFeatureController;
use App\Http\Controllers\Agency\AgencyAiReportController;
use App\Http\Controllers\Agency\AgencyUsageLimitController;
use App\Http\Controllers\Agency\AgencyAffiliateController;
use App\Http\Controllers\Agency\AgencyBillingController;
use App\Http\Controllers\Investor\InvestorDashboardController;
use App\Http\Controllers\Investor\InvestorProjectController;
use App\Http\Controllers\Investor\InvestorInvestmentController;
use App\Http\Controllers\Investor\InvestorCapitalCallController;
use App\Http\Controllers\Investor\InvestorEarningsController;
use App\Http\Controllers\Investor\InvestorPayoutController;
use App\Http\Controllers\Investor\InvestorDocumentController;
use App\Http\Controllers\Investor\InvestorProfileController;
use App\Http\Controllers\Investor\InvestorSupportController;
use App\Http\Controllers\Admin\AdminGlobalAiPromptController;
use App\Http\Controllers\Admin\AdminContentReviewController;
use App\Http\Controllers\Manager\ManagerContentReviewController;
use App\Http\Controllers\Manager\ManagerCapitalCallController;
use App\Http\Controllers\Manager\ManagerPayoutPreparationController;
use App\Http\Controllers\Agency\AgencyGeneratedPageController;
use App\Http\Controllers\Agency\AgencyLeadController;

Route::get('/', function () {
    return redirect()->route('register');
});

Auth::routes(['verify' => true]);

// Generic dashboard redirect (for agency/investor users)
Route::get('/dashboard', function () {
    $user = auth()->user();
    if ($user->isOnWaitlist()) return view('dashboards.waitlist', compact('user'));
    if ($user->isAdmin()) return redirect()->route('admin.villabit.dashboard');
    if ($user->isManager()) return redirect()->route('manager.dashboard');
    if ($user->isAgency()) return redirect()->route('agency.dashboard');
    if ($user->isInvestor()) return redirect()->route('investor.dashboard');
    return redirect()->route('login');
})->middleware(['auth', 'verified'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| ADMIN VillaBit Routes (super_admin, admin)
|--------------------------------------------------------------------------
*/
Route::prefix('admin/villabit')->middleware(['auth', 'verified', 'role:admin'])->name('admin.villabit.')->group(function () {
    Route::get('dashboard', [VillaBitDashboardController::class, 'index'])->name('dashboard');

    // User management
    Route::get('users', [UserManagementController::class, 'index'])->name('users.index');
    Route::get('users/create-manager', [UserManagementController::class, 'createManager'])->name('users.create-manager');
    Route::post('users/store-manager', [UserManagementController::class, 'storeManager'])->name('users.store-manager');
    Route::get('users/create-agency', [UserManagementController::class, 'createAgency'])->name('users.create-agency');
    Route::post('users/store-agency', [UserManagementController::class, 'storeAgency'])->name('users.store-agency');
    Route::get('users/create-investor', [UserManagementController::class, 'createInvestor'])->name('users.create-investor');
    Route::post('users/store-investor', [UserManagementController::class, 'storeInvestor'])->name('users.store-investor');
    Route::post('users/{user}/toggle-status', [UserManagementController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::post('users/{user}/approve-waitlist', [UserManagementController::class, 'approveWaitlist'])->name('users.approve-waitlist');
    Route::post('users/{user}/enable-reseller', [UserManagementController::class, 'enableReseller'])->name('users.enable-reseller');

    // Managers
    Route::get('managers', [AdminManagerController::class, 'index'])->name('managers.index');
    Route::get('managers/{user}', [AdminManagerController::class, 'show'])->name('managers.show');

    // Agencies
    Route::get('agencies', [AdminAgencyController::class, 'index'])->name('agencies.index');
    Route::get('agencies/{user}', [AdminAgencyController::class, 'show'])->name('agencies.show');

    // Investors
    Route::get('investors', [AdminInvestorController::class, 'index'])->name('investors.index');
    Route::get('investors/{user}', [AdminInvestorController::class, 'show'])->name('investors.show');

    // Investment Projects
    Route::get('investment-projects', [AdminInvestmentProjectController::class, 'index'])->name('investment-projects.index');
    Route::get('investment-projects/create', [AdminInvestmentProjectController::class, 'create'])->name('investment-projects.create');
    Route::post('investment-projects', [AdminInvestmentProjectController::class, 'store'])->name('investment-projects.store');
    Route::get('investment-projects/{investmentProject}', [AdminInvestmentProjectController::class, 'show'])->name('investment-projects.show');

    // Capital Calls
    Route::get('capital-calls', [AdminCapitalCallController::class, 'index'])->name('capital-calls.index');
    Route::get('capital-calls/{capitalCall}', [AdminCapitalCallController::class, 'show'])->name('capital-calls.show');
    Route::post('capital-calls/{capitalCall}/mark-paid', [AdminCapitalCallController::class, 'markPaid'])->name('capital-calls.mark-paid');

    // Investor Payouts
    Route::get('investor-payouts', [AdminInvestorPayoutController::class, 'index'])->name('investor-payouts.index');
    Route::post('investor-payouts/{investorPayout}/approve', [AdminInvestorPayoutController::class, 'approve'])->name('investor-payouts.approve');
    Route::post('investor-payouts/{investorPayout}/mark-paid', [AdminInvestorPayoutController::class, 'markPaid'])->name('investor-payouts.mark-paid');

    // AI Reports
    Route::get('ai-reports', [AdminAiReportController::class, 'index'])->name('ai-reports.index');
    Route::get('ai-reports/{report}', [AdminAiReportController::class, 'show'])->name('ai-reports.show');

    // Usage Limits
    Route::get('usage-limits', [AdminUsageLimitController::class, 'index'])->name('usage-limits.index');
    Route::put('usage-limits/{usageLimit}', [AdminUsageLimitController::class, 'update'])->name('usage-limits.update');

    // Affiliate Tracking
    Route::get('affiliate-tracking', [AdminAffiliateController::class, 'tracking'])->name('affiliate-tracking');
    Route::get('affiliate-commissions', [AdminAffiliateController::class, 'commissions'])->name('affiliate-commissions');
    Route::get('affiliate-payouts', [AdminAffiliateController::class, 'payouts'])->name('affiliate-payouts');
    Route::post('affiliate-payouts/{payout}/approve', [AdminAffiliateController::class, 'approvePayout'])->name('affiliate-payouts.approve');
    Route::post('affiliate-payouts/{payout}/mark-paid', [AdminAffiliateController::class, 'markPayoutPaid'])->name('affiliate-payouts.mark-paid');

    // Global AI Prompts
    Route::get('ai-prompts', [AdminGlobalAiPromptController::class, 'index'])->name('ai-prompts.index');
    Route::get('ai-prompts/{prompt}/edit', [AdminGlobalAiPromptController::class, 'edit'])->name('ai-prompts.edit');
    Route::put('ai-prompts/{prompt}', [AdminGlobalAiPromptController::class, 'update'])->name('ai-prompts.update');

    // Content Review
    Route::get('content-review', [AdminContentReviewController::class, 'index'])->name('content-review.index');
    Route::get('content-review/{page}', [AdminContentReviewController::class, 'show'])->name('content-review.show');
    Route::post('content-review/{page}/approve', [AdminContentReviewController::class, 'approve'])->name('content-review.approve');
    Route::post('content-review/{page}/reject', [AdminContentReviewController::class, 'reject'])->name('content-review.reject');

    // Impersonation
    Route::post('impersonate/{user}', [ImpersonationController::class, 'start'])->name('impersonate.start');
    Route::get('impersonate/stop', [ImpersonationController::class, 'stop'])->name('impersonate.stop');
});

/*
|--------------------------------------------------------------------------
| MANAGER Routes
|--------------------------------------------------------------------------
*/
Route::prefix('manager')->middleware(['auth', 'verified', 'role:manager'])->name('manager.')->group(function () {
    Route::get('dashboard', [ManagerDashboardController::class, 'index'])->name('dashboard');
    Route::get('agencies', [ManagerAgencyController::class, 'index'])->name('agencies.index');
    Route::get('investors', [ManagerInvestorController::class, 'index'])->name('investors.index');
    Route::get('ai-reports', [ManagerAiReportController::class, 'index'])->name('ai-reports.index');
    Route::get('support-notes', [ManagerSupportNoteController::class, 'index'])->name('support-notes.index');
    Route::post('support-notes', [ManagerSupportNoteController::class, 'store'])->name('support-notes.store');

    // Content Review
    Route::get('content-review', [ManagerContentReviewController::class, 'index'])->name('content-review.index');
    Route::get('content-review/{page}', [ManagerContentReviewController::class, 'show'])->name('content-review.show');

    // Capital Call Follow-up
    Route::get('capital-calls', [ManagerCapitalCallController::class, 'index'])->name('capital-calls.index');

    // Payout Preparation
    Route::get('payout-preparation', [ManagerPayoutPreparationController::class, 'index'])->name('payout-preparation.index');
});

/*
|--------------------------------------------------------------------------
| AGENCY Routes
|--------------------------------------------------------------------------
*/
Route::prefix('agency')->middleware(['auth', 'verified', 'role:real_estate_agency'])->name('agency.')->group(function () {
    Route::get('dashboard', [AgencyDashboardController::class, 'index'])->name('dashboard');

    // Settings
    Route::get('settings', [AgencySettingsController::class, 'languageSettings'])->name('settings');
    Route::get('settings/language', [AgencySettingsController::class, 'languageSettings'])->name('settings.language');
    Route::post('settings/language', [AgencySettingsController::class, 'updateLanguageSettings'])->name('settings.language.update');
    Route::get('settings/features', [AgencySettingsController::class, 'featureToggles'])->name('settings.features');
    Route::post('settings/features/toggle', [AgencySettingsController::class, 'updateFeatureToggle'])->name('settings.features.toggle');

    // Features
    Route::get('features/{feature}', [AgencyFeatureController::class, 'show'])->name('features.show');

    // AI Reports
    Route::get('ai-reports', [AgencyAiReportController::class, 'index'])->name('ai-reports.index');

    // Usage Limits
    Route::get('usage-limits', [AgencyUsageLimitController::class, 'index'])->name('usage-limits.index');

    // Affiliate
    Route::get('affiliate', [AgencyAffiliateController::class, 'index'])->name('affiliate.index');

    // Billing
    Route::get('billing', [AgencyBillingController::class, 'index'])->name('billing.index');

    // Generated Pages
    Route::get('generated-pages', [AgencyGeneratedPageController::class, 'index'])->name('generated-pages.index');
    Route::get('generated-pages/{page}', [AgencyGeneratedPageController::class, 'show'])->name('generated-pages.show');

    // Leads
    Route::get('leads', [AgencyLeadController::class, 'index'])->name('leads.index');
    Route::get('leads/{lead}', [AgencyLeadController::class, 'show'])->name('leads.show');
    Route::patch('leads/{lead}/status', [AgencyLeadController::class, 'updateStatus'])->name('leads.update-status');
});

/*
|--------------------------------------------------------------------------
| INVESTOR Routes
|--------------------------------------------------------------------------
*/
Route::prefix('investor')->middleware(['auth', 'verified', 'role:investor'])->name('investor.')->group(function () {
    Route::get('dashboard', [InvestorDashboardController::class, 'index'])->name('dashboard');
    Route::get('projects', [InvestorProjectController::class, 'index'])->name('projects.index');
    Route::get('projects/{project}', [InvestorProjectController::class, 'show'])->name('projects.show');
    Route::get('investments', [InvestorInvestmentController::class, 'index'])->name('investments.index');
    Route::get('capital-calls', [InvestorCapitalCallController::class, 'index'])->name('capital-calls.index');
    Route::get('earnings', [InvestorEarningsController::class, 'index'])->name('earnings.index');
    Route::get('payouts', [InvestorPayoutController::class, 'index'])->name('payouts.index');
    Route::get('documents', [InvestorDocumentController::class, 'index'])->name('documents.index');
    Route::post('documents', [InvestorDocumentController::class, 'store'])->name('documents.store');
    Route::get('profile', [InvestorProfileController::class, 'show'])->name('profile.show');
    Route::put('profile', [InvestorProfileController::class, 'update'])->name('profile.update');

    // Support
    Route::get('support', [InvestorSupportController::class, 'index'])->name('support.index');
    Route::get('support/create', [InvestorSupportController::class, 'create'])->name('support.create');
    Route::post('support', [InvestorSupportController::class, 'store'])->name('support.store');
    Route::get('support/{ticket}', [InvestorSupportController::class, 'show'])->name('support.show');
    Route::post('support/{ticket}/reply', [InvestorSupportController::class, 'reply'])->name('support.reply');
});

// Referral tracking — set cookie (180 days)
Route::get('/ref/{code}', function ($code) {
    return redirect('/')->withCookie(cookie('referral_code', $code, 60 * 24 * 180));
})->name('referral.track');
