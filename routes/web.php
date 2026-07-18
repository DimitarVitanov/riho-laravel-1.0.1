<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
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
use App\Http\Controllers\Agency\LocalSeoCampaignController;
use App\Http\Controllers\Agency\DailyAiEmployeeController;
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
use App\Http\Controllers\Investor\InvestorAffiliateController;
use App\Http\Controllers\Investor\InvestorProfileController;
use App\Http\Controllers\Investor\InvestorSupportController;
use App\Http\Controllers\Admin\AdminGlobalAiPromptController;
use App\Http\Controllers\Admin\AdminContentReviewController;
use App\Http\Controllers\Manager\ManagerContentReviewController;
use App\Http\Controllers\Manager\ManagerCapitalCallController;
use App\Http\Controllers\Manager\ManagerPayoutPreparationController;
use App\Http\Controllers\Agency\AgencyGeneratedPageController;
use App\Http\Controllers\Agency\AgencyCompetitorController;
use App\Http\Controllers\Agency\AgencyLeadController;
use App\Http\Controllers\Admin\AdminSupportTicketController;
use App\Http\Controllers\Agency\AgencySupportController;
use App\Http\Controllers\Admin\AdminAiSettingsController;
use App\Http\Controllers\Agency\AgencySitemapController;
use App\Http\Controllers\RealEstateTaxiController;
use App\Http\Controllers\PublicPageController;

// Public Agency Pages (Authority Builder) — served on agency custom domains
Route::get('/blog', [PublicPageController::class, 'index'])->name('public.blog.index');
Route::get('/blog/{slug}', [PublicPageController::class, 'show'])->name('public.blog.show');

// Real Estate Taxi domain homepage
Route::domain('realestate.taxi')->group(function () {
    Route::get('/{any?}', [RealEstateTaxiController::class, 'home'])->name('realestatetaxi.home')
        ->where('any', '.*');
});

// Real Estate Taxi fallback via prefix (for local/testing)
Route::prefix('realestate')->group(function () {
    Route::get('/', [RealEstateTaxiController::class, 'home']);
});

// Home page redirects to marketing website
Route::get('/', function () {
    return redirect('https://villabit.ai/');
});

// Public Lead Magnet Capture Form
Route::get('/lm/{agency}', [App\Http\Controllers\Frontend\LeadMagnetController::class, 'show'])->name('lead-magnet.show');
Route::post('/lm/{agency}', [App\Http\Controllers\Frontend\LeadMagnetController::class, 'store'])->name('lead-magnet.store');

// Public Agency Sitemap XML — by ID
Route::get('/sitemap/agency/{agencyId}.xml', [AgencySitemapController::class, 'show'])->name('agency.sitemap');
// Public Agency Sitemap XML — by custom domain (e.g. yourdomain.com/sitemap.xml)
Route::get('/sitemap.xml', [AgencySitemapController::class, 'showByDomain']);
// Public Agency Sitemap XML — folder-based (e.g. yourdomain.com/blog/sitemap.xml)
Route::get('/{folder}/sitemap.xml', [AgencySitemapController::class, 'showByDomainFolder'])->where('folder', '[a-zA-Z0-9_\-]+');

// Public legal pages redirect to marketing site
Route::redirect('/terms', 'https://villabit.ai/terms/', 301)->name('terms');
Route::redirect('/privacy', 'https://villabit.ai/privacy/', 301)->name('privacy');

Auth::routes(['verify' => true]);

// Generic dashboard redirect (for agency/investor users)
Route::get('/dashboard', function () {
    $user = auth()->user();
    if ($user->isOnWaitlist()) return view('dashboards.waitlist', compact('user'));
    if ($user->isAdmin()) return redirect()->route('admin.villabit.dashboard');
    if ($user->isManager()) {
        if ($user->managerProfile?->can_view_agency_readonly) {
            return redirect()->route('agency.dashboard');
        }
        return redirect()->route('manager.dashboard');
    }
    if ($user->isAgency()) return redirect()->route('agency.dashboard');
    if ($user->isInvestor()) return redirect()->route('investor.dashboard');
    return redirect()->route('login');
})->middleware(['auth'])->name('dashboard');

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
    Route::delete('users/{user}', [UserManagementController::class, 'destroy'])->name('users.destroy');

    // Managers
    Route::get('managers', [AdminManagerController::class, 'index'])->name('managers.index');
    Route::get('managers/{user}', [AdminManagerController::class, 'show'])->name('managers.show');
    Route::put('managers/{user}', [AdminManagerController::class, 'update'])->name('managers.update');
    
    // Manager URLs
    Route::get('managers/{user}/urls', [AdminManagerController::class, 'urls'])->name('managers.urls.show');
    Route::post('managers/{user}/urls', [AdminManagerController::class, 'storeUrls'])->name('managers.urls.store');
    Route::put('managers/{user}/urls/{url}', [AdminManagerController::class, 'updateUrl'])->name('managers.urls.update');
    Route::delete('managers/{user}/urls/{url}', [AdminManagerController::class, 'destroyUrl'])->name('managers.urls.destroy');

    // Agencies
    Route::get('agencies', [AdminAgencyController::class, 'index'])->name('agencies.index');
    Route::get('agencies/{user}', [AdminAgencyController::class, 'show'])->name('agencies.show');
    Route::post('agencies/{user}/toggle-status', [AdminAgencyController::class, 'toggleStatus'])->name('agencies.toggle-status');
    Route::post('agencies/{user}/create-usage-limits', [AdminAgencyController::class, 'createUsageLimits'])->name('agencies.create-usage-limits');
    Route::post('agencies/{user}/domain-settings', [AdminAgencyController::class, 'updateDomainSettings'])->name('agencies.domain-settings.update');
    Route::post('agencies/{user}/upload-sitemap', [AdminAgencyController::class, 'uploadSitemap'])->name('agencies.upload-sitemap');
    Route::post('agencies/{user}/assign-view-only-manager', [AdminAgencyController::class, 'assignViewOnlyManager'])->name('agencies.assign-view-only-manager');
    Route::post('agencies/{user}/remove-view-only-manager', [AdminAgencyController::class, 'removeViewOnlyManager'])->name('agencies.remove-view-only-manager');

    // Investors
    Route::get('investors', [AdminInvestorController::class, 'index'])->name('investors.index');
    Route::get('investors/{user}', [AdminInvestorController::class, 'show'])->name('investors.show');
    Route::post('investors/{user}/reseller', [AdminInvestorController::class, 'updateReseller'])->name('investors.update-reseller');
    Route::post('investors/{user}/kyc-status', [AdminInvestorController::class, 'updateKycStatus'])->name('investors.update-kyc-status');

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
    Route::post('usage-limits/bulk-create', [AdminUsageLimitController::class, 'bulkCreate'])->name('usage-limits.bulk-create');
    Route::post('usage-limits/bulk-apply-preset', [AdminUsageLimitController::class, 'bulkApplyPreset'])->name('usage-limits.bulk-apply-preset');
    Route::post('usage-limits/{usageLimit}/apply-preset', [AdminUsageLimitController::class, 'applyPreset'])->name('usage-limits.apply-preset');
    Route::get('usage-limits/{usageLimit}/edit', [AdminUsageLimitController::class, 'edit'])->name('usage-limits.edit');
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

    // AI API Settings
    Route::get('ai-settings', [AdminAiSettingsController::class, 'index'])->name('ai-settings.index');
    Route::put('ai-settings', [AdminAiSettingsController::class, 'update'])->name('ai-settings.update');

    // Content Review
    Route::get('content-review', [AdminContentReviewController::class, 'index'])->name('content-review.index');
    Route::get('content-review/{page}', [AdminContentReviewController::class, 'show'])->name('content-review.show');
    Route::post('content-review/{page}/approve', [AdminContentReviewController::class, 'approve'])->name('content-review.approve');
    Route::post('content-review/{page}/reject', [AdminContentReviewController::class, 'reject'])->name('content-review.reject');

    // Support Tickets
    Route::get('support-tickets', [AdminSupportTicketController::class, 'index'])->name('support-tickets.index');
    Route::get('support-tickets/{ticket}', [AdminSupportTicketController::class, 'show'])->name('support-tickets.show');
    Route::post('support-tickets/{ticket}/reply', [AdminSupportTicketController::class, 'reply'])->name('support-tickets.reply');
    Route::post('support-tickets/{ticket}/update-status', [AdminSupportTicketController::class, 'updateStatus'])->name('support-tickets.update-status');

    // Impersonation
    Route::post('impersonate/{user}', [ImpersonationController::class, 'start'])->name('impersonate.start');
    Route::post('impersonate/{user}/token', [ImpersonationController::class, 'generateToken'])->name('impersonate.token');
    Route::get('impersonate/stop', [ImpersonationController::class, 'stop'])->name('impersonate.stop');
});

// Impersonation login (no auth required - uses token)
Route::get('impersonate/login/{token}', [ImpersonationController::class, 'loginWithToken'])->name('impersonate.login');

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
Route::prefix('agency')->middleware(['auth', 'verified', 'role:real_estate_agency', 'view_only'])->name('agency.')->group(function () {
    Route::get('dashboard', [AgencyDashboardController::class, 'index'])->name('dashboard');

    // Settings
    Route::get('settings', [AgencySettingsController::class, 'languageSettings'])->name('settings');
    Route::get('settings/language', [AgencySettingsController::class, 'languageSettings'])->name('settings.language');
    Route::post('settings/language', [AgencySettingsController::class, 'updateLanguageSettings'])->name('settings.language.update');
    Route::get('settings/features', [AgencySettingsController::class, 'featureToggles'])->name('settings.features');
    Route::post('settings/features/toggle', [AgencySettingsController::class, 'updateFeatureToggle'])->name('settings.features.toggle');
    Route::get('settings/domain', [AgencySettingsController::class, 'domainSettings'])->name('settings.domain');
    Route::post('settings/domain', [AgencySettingsController::class, 'updateDomainSettings'])->name('settings.domain.update');
    Route::get('settings/website-design', [AgencySettingsController::class, 'websiteDesign'])->name('settings.website-design');
    Route::post('settings/website-design', [AgencySettingsController::class, 'updateWebsiteDesign'])->name('settings.website-design.update');
    Route::get('settings/integrations', [AgencySettingsController::class, 'integrations'])->name('settings.integrations');
    Route::put('settings/integrations', [AgencySettingsController::class, 'updateIntegrations'])->name('settings.integrations.update');
    Route::get('settings/brand', [AgencySettingsController::class, 'brandSettings'])->name('settings.brand');
    Route::put('settings/brand', [AgencySettingsController::class, 'updateBrandSettings'])->name('settings.brand.update');

    // Features
    Route::get('features/{feature}', [AgencyFeatureController::class, 'show'])->name('features.show');

    // Daily AI Employee - Unified Content Inbox
    Route::get('daily-ai-employee', [DailyAiEmployeeController::class, 'index'])->name('daily-ai-employee.index');
    Route::post('daily-ai-employee/settings', [DailyAiEmployeeController::class, 'saveSettings'])->name('daily-ai-employee.save-settings');
    Route::get('daily-ai-employee/logs', [DailyAiEmployeeController::class, 'viewLogs'])->name('daily-ai-employee.logs');
    Route::get('daily-ai-employee/prompt', [DailyAiEmployeeController::class, 'openPrompt'])->name('daily-ai-employee.prompt');
    Route::patch('daily-ai-employee/suggestions/{suggestion}/accept', [DailyAiEmployeeController::class, 'acceptSuggestion'])->name('daily-ai-employee.accept');
    Route::patch('daily-ai-employee/suggestions/{suggestion}/skip', [DailyAiEmployeeController::class, 'skipSuggestion'])->name('daily-ai-employee.skip');
    Route::patch('daily-ai-employee/suggestions/{suggestion}/remove', [DailyAiEmployeeController::class, 'removeSuggestion'])->name('daily-ai-employee.remove');
    Route::patch('daily-ai-employee/suggestions/{suggestion}/review', [DailyAiEmployeeController::class, 'markAsReviewed'])->name('daily-ai-employee.review');
    Route::patch('daily-ai-employee/pages/{page}/publish', [DailyAiEmployeeController::class, 'publishContent'])->name('daily-ai-employee.publish');

    // Invisible Lead Magnet
    Route::post('invisible-lead-magnet/settings', [AgencyFeatureController::class, 'saveSettings'])->name('invisible-lead-magnet.save-settings');
    Route::get('invisible-lead-magnet/logs', [AgencyFeatureController::class, 'viewLogs'])->name('invisible-lead-magnet.logs');
    Route::get('invisible-lead-magnet/prompt', [AgencyFeatureController::class, 'openPrompt'])->name('invisible-lead-magnet.prompt');
    Route::get('invisible-lead-magnet/export', [AgencyFeatureController::class, 'exportLeads'])->name('invisible-lead-magnet.export');

    // Local SEO Presence Boost
    Route::post('local-seo-presence-boost/settings', [AgencyFeatureController::class, 'saveSettings'])->name('local-seo.save-settings');
    Route::get('local-seo-presence-boost/logs', [AgencyFeatureController::class, 'viewLogs'])->name('local-seo.logs');
    Route::get('local-seo-presence-boost/prompt', [AgencyFeatureController::class, 'openPrompt'])->name('local-seo.prompt');
    Route::post('local-seo-presence-boost/generate-targets', [AgencyFeatureController::class, 'generateLocalSeoTargets'])->name('local-seo.generate-targets');
    Route::post('local-seo-presence-boost/generate-pages', [AgencyFeatureController::class, 'generateLocalSeoPages'])->name('local-seo.generate-pages');
    Route::get('local-seo-presence-boost/pages/{page}/preview', [AgencyFeatureController::class, 'previewLocalSeoPage'])->name('local-seo.pages.preview');
    Route::get('local-seo-presence-boost/pages/{page}/edit', [AgencyFeatureController::class, 'editLocalSeoPage'])->name('local-seo.pages.edit');
    Route::put('local-seo-presence-boost/pages/{page}', [AgencyFeatureController::class, 'updateLocalSeoPage'])->name('local-seo.pages.update');
    Route::post('local-seo-presence-boost/pages/{page}/publish', [AgencyFeatureController::class, 'publishLocalSeoPage'])->name('local-seo.pages.publish');
    Route::delete('local-seo-presence-boost/pages/{page}', [AgencyFeatureController::class, 'destroyLocalSeoPage'])->name('local-seo.pages.destroy');

    // Local SEO Campaigns (multi-campaign builder)
    Route::post('local-seo-presence-boost/campaigns', [LocalSeoCampaignController::class, 'storeDraft'])->name('local-seo.campaigns.store');
    Route::post('local-seo-presence-boost/campaigns/suggest-places', [LocalSeoCampaignController::class, 'suggestPlaces'])->name('local-seo.campaigns.suggest-places');
    Route::get('local-seo-presence-boost/campaigns/{campaign}/preview', [LocalSeoCampaignController::class, 'preview'])->name('local-seo.campaigns.preview');
    Route::post('local-seo-presence-boost/campaigns/{campaign}/toggle', [LocalSeoCampaignController::class, 'toggleStatus'])->name('local-seo.campaigns.toggle');
    Route::post('local-seo-presence-boost/campaigns/{campaign}/publish', [LocalSeoCampaignController::class, 'publish'])->name('local-seo.campaigns.publish');
    Route::post('local-seo-presence-boost/campaigns/{campaign}/settings', [LocalSeoCampaignController::class, 'updateSettings'])->name('local-seo.campaigns.update-settings');
    Route::delete('local-seo-presence-boost/campaigns/{campaign}', [LocalSeoCampaignController::class, 'destroy'])->name('local-seo.campaigns.destroy');

    // AI Content Generation
    Route::post('local-seo-presence-boost/campaigns/{campaign}/generate-content', [LocalSeoCampaignController::class, 'generateContent'])->name('local-seo.campaigns.generate-content');
    Route::post('local-seo-presence-boost/campaigns/{campaign}/check-and-publish', [LocalSeoCampaignController::class, 'checkAndPublish'])->name('local-seo.campaigns.check-and-publish');

    // Uniqueness Checking
    Route::post('local-seo-presence-boost/check-uniqueness', [LocalSeoCampaignController::class, 'checkUniqueness'])->name('local-seo.check-uniqueness');
    Route::get('local-seo-presence-boost/copyscape-status', [LocalSeoCampaignController::class, 'copyscapeStatus'])->name('local-seo.copyscape-status');
    Route::get('local-seo-presence-boost/campaigns/{campaign}/content', [LocalSeoCampaignController::class, 'getCampaignContent'])->name('local-seo.campaigns.content');
    Route::get('local-seo-presence-boost/place-autocomplete', [LocalSeoCampaignController::class, 'placeAutocomplete'])->name('local-seo.place-autocomplete');

    // Agency Listings
    Route::post('local-seo-presence-boost/listings', [AgencyFeatureController::class, 'storeListing'])->name('local-seo.listings.store');
    Route::post('local-seo-presence-boost/listings/{listing}/campaign', [AgencyFeatureController::class, 'assignListingCampaign'])->name('local-seo.listings.assign-campaign');
    Route::post('local-seo-presence-boost/listings/{listing}/campaigns', [AgencyFeatureController::class, 'assignListingCampaigns'])->name('local-seo.listings.assign-campaigns');
    Route::put('local-seo-presence-boost/listings/{listing}', [AgencyFeatureController::class, 'updateListing'])->name('local-seo.listings.update');
    Route::delete('local-seo-presence-boost/listings/{listing}', [AgencyFeatureController::class, 'destroyListing'])->name('local-seo.listings.destroy');

    // AI Search Ranking (Legacy)
    Route::post('ai-search-ranking/settings', [AgencyFeatureController::class, 'saveSettings'])->name('ai-search.save-settings');
    Route::post('ai-search-ranking/generate-authority-pages', [AgencyFeatureController::class, 'generateAuthorityPages'])->name('ai-search.generate-authority-pages');
    Route::post('ai-search-ranking/generate-data-blocks', [AgencyFeatureController::class, 'generateDataBlocks'])->name('ai-search.generate-data-blocks');
    Route::get('ai-search-ranking/pages/{page}/preview', [AgencyFeatureController::class, 'previewAiSearchPage'])->name('ai-search.pages.preview');
    Route::get('ai-search-ranking/pages/{page}/edit', [AgencyFeatureController::class, 'editAiSearchPage'])->name('ai-search.pages.edit');
    Route::put('ai-search-ranking/pages/{page}', [AgencyFeatureController::class, 'updateAiSearchPage'])->name('ai-search.pages.update');
    Route::post('ai-search-ranking/pages/{page}/publish', [AgencyFeatureController::class, 'publishAiSearchPage'])->name('ai-search.pages.publish');
    Route::post('ai-search-ranking/pages/{page}/refresh', [AgencyFeatureController::class, 'refreshAiSearchPage'])->name('ai-search.pages.refresh');
    Route::delete('ai-search-ranking/pages/{page}', [AgencyFeatureController::class, 'destroyAiSearchPage'])->name('ai-search.pages.destroy');

    // AI Search Ranking - Authority Pages (New)
    Route::post('ai-search-ranking/authority-pages', [App\Http\Controllers\Agency\AiSearchRankingController::class, 'store'])->name('ai-search-ranking.store');
    Route::get('ai-search-ranking/authority-pages/{page}/generate', [App\Http\Controllers\Agency\AiSearchRankingController::class, 'generate'])->name('ai-search-ranking.generate');
    Route::get('ai-search-ranking/authority-pages/{page}/preview', [App\Http\Controllers\Agency\AiSearchRankingController::class, 'preview'])->name('ai-search-ranking.preview');
    Route::get('ai-search-ranking/authority-pages/{page}/edit', [App\Http\Controllers\Agency\AiSearchRankingController::class, 'edit'])->name('ai-search-ranking.edit');
    Route::put('ai-search-ranking/authority-pages/{page}/content', [App\Http\Controllers\Agency\AiSearchRankingController::class, 'updateContent'])->name('ai-search-ranking.update-content');
    Route::put('ai-search-ranking/authority-pages/{page}/listing', [App\Http\Controllers\Agency\AiSearchRankingController::class, 'updateListing'])->name('ai-search-ranking.update-listing');
    Route::post('ai-search-ranking/authority-pages/{page}/toggle', [App\Http\Controllers\Agency\AiSearchRankingController::class, 'toggleStatus'])->name('ai-search-ranking.toggle');
    Route::delete('ai-search-ranking/authority-pages/{page}', [App\Http\Controllers\Agency\AiSearchRankingController::class, 'destroy'])->name('ai-search-ranking.destroy');

    // Agency Agents
    Route::post('agents', [AgencyFeatureController::class, 'storeAgent'])->name('agents.store');
    Route::put('agents/{agent}', [AgencyFeatureController::class, 'updateAgent'])->name('agents.update');
    Route::delete('agents/{agent}', [AgencyFeatureController::class, 'destroyAgent'])->name('agents.destroy');

    // Daily Competitor Scan
    Route::post('daily-competitor-scan/competitors', [AgencyCompetitorController::class, 'storeCompetitor'])->name('competitor.store');
    Route::delete('daily-competitor-scan/competitors/{competitor}', [AgencyCompetitorController::class, 'destroyCompetitor'])->name('competitor.destroy');
    Route::post('daily-competitor-scan/run-scan', [AgencyCompetitorController::class, 'runScan'])->name('competitor.run-scan');
    Route::post('daily-competitor-scan/results/{result}/acted', [AgencyCompetitorController::class, 'markActed'])->name('competitor.results.acted');
    Route::post('daily-competitor-scan/results/{result}/dismissed', [AgencyCompetitorController::class, 'markDismissed'])->name('competitor.results.dismissed');
    Route::delete('daily-competitor-scan/results/{result}', [AgencyCompetitorController::class, 'destroyResult'])->name('competitor.results.destroy');

    // AI Authority Builder - All using AuthorityBuilderPage model
    Route::post('ai-authority-builder/generate', [AgencyFeatureController::class, 'generateAuthorityReview'])->name('authority.generate');
    Route::get('ai-authority-builder/{authorityBuilderPage}/preview', [AgencyFeatureController::class, 'previewScheduledAuthorityPage'])->name('authority.preview');
    Route::post('ai-authority-builder/{authorityBuilderPage}/publish', [AgencyFeatureController::class, 'publishAuthorityBuilderPage'])->name('authority.pages.publish');
    Route::post('ai-authority-builder/{authorityBuilderPage}/refresh', [AgencyFeatureController::class, 'refreshAuthorityBuilderPage'])->name('authority.pages.refresh');
    Route::delete('ai-authority-builder/{authorityBuilderPage}', [AgencyFeatureController::class, 'destroyAuthorityBuilderPage'])->name('authority.pages.destroy');
    
    // Suggestion Management for Individual Features
    Route::patch('ai-authority-builder/suggestions/{suggestion}/accept', [AgencyFeatureController::class, 'acceptAuthoritySuggestion'])->name('authority.suggestions.accept');
    Route::patch('ai-authority-builder/suggestions/{suggestion}/skip', [AgencyFeatureController::class, 'skipAuthoritySuggestion'])->name('authority.suggestions.skip');
    Route::patch('local-seo/suggestions/{suggestion}/accept', [AgencyFeatureController::class, 'acceptLocalSeoSuggestion'])->name('local-seo.suggestions.accept');
    Route::patch('local-seo/suggestions/{suggestion}/skip', [AgencyFeatureController::class, 'skipLocalSeoSuggestion'])->name('local-seo.suggestions.skip');

    // AI Reports
    Route::get('ai-reports', [AgencyAiReportController::class, 'index'])->name('ai-reports.index');

    // Usage Limits
    Route::get('usage-limits', [AgencyUsageLimitController::class, 'index'])->name('usage-limits.index');

    // Affiliate
    Route::get('affiliate', [AgencyAffiliateController::class, 'index'])->name('affiliate.index');

    // Billing
    Route::get('billing', [AgencyBillingController::class, 'index'])->name('billing.index');

    // Generated Pages (Authority Builder)
    Route::get('generated-pages', [AgencyGeneratedPageController::class, 'index'])->name('generated-pages.index');
    Route::get('generated-pages/create', [AgencyGeneratedPageController::class, 'create'])->name('generated-pages.create');
    Route::post('generated-pages', [AgencyGeneratedPageController::class, 'store'])->name('generated-pages.store');
    Route::get('generated-pages/{page}', [AgencyGeneratedPageController::class, 'show'])->name('generated-pages.show');
    Route::get('generated-pages/{page}/edit', [AgencyGeneratedPageController::class, 'edit'])->name('generated-pages.edit');
    Route::put('generated-pages/{page}', [AgencyGeneratedPageController::class, 'update'])->name('generated-pages.update');
    Route::post('generated-pages/{page}/publish', [AgencyGeneratedPageController::class, 'publish'])->name('generated-pages.publish');
    Route::post('generated-pages/{page}/unpublish', [AgencyGeneratedPageController::class, 'unpublish'])->name('generated-pages.unpublish');
    Route::delete('generated-pages/{page}', [AgencyGeneratedPageController::class, 'destroy'])->name('generated-pages.destroy');
    Route::get('generated-pages/{page}/preview', [AgencyGeneratedPageController::class, 'preview'])->name('generated-pages.preview');

    // Leads
    Route::get('leads', [AgencyLeadController::class, 'index'])->name('leads.index');
    Route::get('leads/{lead}', [AgencyLeadController::class, 'show'])->name('leads.show');
    Route::patch('leads/{lead}/status', [AgencyLeadController::class, 'updateStatus'])->name('leads.update-status');
});

/*
|--------------------------------------------------------------------------
| AGENCY Support Routes (accessible to agencies on the waitlist too)
|--------------------------------------------------------------------------
*/
Route::prefix('agency')->middleware(['auth', 'verified', 'agency'])->name('agency.')->group(function () {
    Route::get('support', [AgencySupportController::class, 'index'])->name('support.index');
    Route::get('support/create', [AgencySupportController::class, 'create'])->name('support.create');
    Route::post('support', [AgencySupportController::class, 'store'])->name('support.store');
    Route::get('support/{ticket}', [AgencySupportController::class, 'show'])->name('support.show');
    Route::post('support/{ticket}/reply', [AgencySupportController::class, 'reply'])->name('support.reply');
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

    // Affiliate
    Route::get('affiliate', [InvestorAffiliateController::class, 'index'])->name('affiliate.index');

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
