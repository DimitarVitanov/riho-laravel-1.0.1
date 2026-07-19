<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use App\Models\AgencyProfile;
use App\Models\AiFeatureSetting;
use App\Models\GeneratedPage;
use App\Services\DomainVerificationService;
use App\Services\ManagerUrlMatcher;
use App\Services\SitemapSftpUploader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AgencySettingsController extends Controller
{
    public function languageSettings()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $profile = $user->getEffectiveAgencyProfile();

        return view('agency.settings.language', compact('user', 'profile'));
    }

    public function updateLanguageSettings(Request $request)
    {
        $request->validate([
            'panel_language' => 'required|string|max:10',
            'ai_content_language' => 'required|string|max:50',
            'uniqueness_check_method' => 'required|string|in:' . implode(',', array_keys(self::uniquenessCheckMethods())),
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->update(['preferred_language' => $request->panel_language]);

        if ($user->getEffectiveAgencyProfile()) {
            $user->getEffectiveAgencyProfile()->update([
                'ai_content_language' => $request->ai_content_language,
                'uniqueness_check_method' => $request->uniqueness_check_method,
            ]);
        }

        return back()->with('success', 'Language settings updated.');
    }

    public function featureToggles()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $profile = $user->getEffectiveAgencyProfile();
        $features = collect();

        if ($profile) {
            $features = AiFeatureSetting::where('agency_profile_id', $profile->id)->get();
        }

        return view('agency.settings.features', compact('user', 'profile', 'features'));
    }

    public function updateFeatureToggle(Request $request)
    {
        $request->validate([
            'feature_id' => 'required|exists:ai_feature_settings,id',
            'is_enabled' => 'required|boolean',
        ]);

        $feature = AiFeatureSetting::findOrFail($request->feature_id);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $profile = $user->getEffectiveAgencyProfile();
        
        if (!$profile || $feature->agency_profile_id !== $profile->id) {
            return back()->with('error', 'Unauthorized.');
        }

        $isEnabling = $request->boolean('is_enabled');
        $featureKey = $feature->feature_key;

        // Handle affiliate_sale and invisible_lead_magnet dependency
        if (in_array($featureKey, ['affiliate_sale', 'invisible_lead_magnet'])) {
            $linkedFeatureKey = $featureKey === 'affiliate_sale' ? 'invisible_lead_magnet' : 'affiliate_sale';
            $linkedFeature = AiFeatureSetting::where('agency_profile_id', $profile->id)
                ->where('feature_key', $linkedFeatureKey)
                ->first();

            if (!$isEnabling) {
                // Turning OFF - both must be disabled together
                $feature->update(['is_enabled' => false]);
                if ($linkedFeature) {
                    $linkedFeature->update(['is_enabled' => false]);
                }

                // Delete property files from server
                $this->deleteVillaReadyPagesFromServer($profile);

                return back()->with('success', 'Affiliate Sale and Invisible Lead Magnet have been disabled. Property pages removed from your server.');
            } else {
                // Turning ON - both must be enabled together
                $feature->update(['is_enabled' => true]);
                if ($linkedFeature) {
                    $linkedFeature->update(['is_enabled' => true]);
                }

                // Re-upload property files to server
                $this->uploadVillaReadyPagesToServer($profile);

                return back()->with('success', 'Affiliate Sale and Invisible Lead Magnet have been enabled. Property pages uploaded to your server.');
            }
        }

        // Normal feature toggle
        $feature->update(['is_enabled' => $isEnabling]);

        return back()->with('success', 'Feature toggled.');
    }

    protected function deleteVillaReadyPagesFromServer(AgencyProfile $profile): void
    {
        if (!$profile->server_ip || !$profile->sftp_username || !$profile->sftp_password) {
            return;
        }

        try {
            $uploader = app(SitemapSftpUploader::class);
            $uploader->deleteVillaReadyPages($profile);
        } catch (\Exception $e) {
            Log::warning("Failed to delete Villa Ready pages: " . $e->getMessage());
        }
    }

    protected function uploadVillaReadyPagesToServer(AgencyProfile $profile): void
    {
        if (!$profile->server_ip || !$profile->sftp_username || !$profile->sftp_password) {
            return;
        }

        try {
            $uploader = app(SitemapSftpUploader::class);
            $uploader->upload($profile); // This uploads sitemap + property pages
        } catch (\Exception $e) {
            Log::warning("Failed to upload Villa Ready pages: " . $e->getMessage());
        }
    }

    public function domainSettings()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $profile = $user->getEffectiveAgencyProfile();

        if ($profile && $profile->custom_domain) {
            app(DomainVerificationService::class)->verify($profile);
            $profile->refresh();
        }

        $customDomain = $profile->custom_domain ?? '';
        $domainPart1 = '';
        $domainPart2 = '';

        if ($user->agency_server_type === 'subdomain_ai_server' && $customDomain) {
            $parts = explode('.', $customDomain, 2);
            $domainPart1 = $parts[0] ?? '';
            $domainPart2 = $parts[1] ?? '';
        } elseif ($user->agency_server_type === 'domain_folder_ai_server' && $customDomain) {
            $parts = explode('/', $customDomain, 2);
            $domainPart1 = $parts[0] ?? '';
            $domainPart2 = isset($parts[1]) ? trim($parts[1], '/') : '';
        } elseif ($customDomain) {
            $domainPart1 = $customDomain;
        }

        $hasDomain = !empty($customDomain);
        $isVerified = $profile && !empty($profile->dns_verified_at);

        return view('agency.settings.domain', compact('user', 'profile', 'domainPart1', 'domainPart2', 'hasDomain', 'isVerified'));
    }

    public function updateDomainSettings(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $domain = null;

        if ($user->agency_server_type === 'subdomain_ai_server') {
            $request->validate([
                'domain_part1' => 'required|string|max:63|regex:/^[a-zA-Z0-9]([a-zA-Z0-9\-]{0,61}[a-zA-Z0-9])?$/',
                'domain_part2' => 'required|string|max:255|regex:/^([a-zA-Z0-9]([a-zA-Z0-9\-]{0,61}[a-zA-Z0-9])?\.)+[a-zA-Z]{2,}$/',
            ]);
            $domain = strtolower($request->domain_part1 . '.' . $request->domain_part2);
        } elseif ($user->agency_server_type === 'domain_folder_ai_server') {
            $request->validate([
                'domain_part1' => 'required|string|max:255|regex:/^([a-zA-Z0-9]([a-zA-Z0-9\-]{0,61}[a-zA-Z0-9])?\.)+[a-zA-Z]{2,}$/',
                'domain_part2' => 'required|string|max:63|regex:/^[a-zA-Z0-9]([a-zA-Z0-9\-]{0,61}[a-zA-Z0-9])?$/',
            ]);
            $domain = strtolower($request->domain_part1 . '/' . trim($request->domain_part2, '/'));
        } else {
            $request->validate([
                'domain_part1' => 'nullable|string|max:255|regex:/^([a-zA-Z0-9]([a-zA-Z0-9\-]{0,61}[a-zA-Z0-9])?\.)+[a-zA-Z]{2,}$/',
            ]);
            $domain = $request->domain_part1 ? rtrim(strtolower($request->domain_part1), '/') : null;
        }

        if ($user->getEffectiveAgencyProfile()) {
            $profile = $user->getEffectiveAgencyProfile();
            $profile->update(['custom_domain' => $domain]);
            
            // Check if new domain matches any manager URL (auto-affiliate)
            if ($domain && !$profile->assigned_manager_id) {
                ManagerUrlMatcher::matchAgencyToManager($profile);
            }
        }

        return back()->with('success', 'Domain settings saved.');
    }

    public function integrations()
    {
        /** @var \App\Models\User $user */
        $user      = Auth::user();
        $profile   = $user->getEffectiveAgencyProfile();
        $pageCount = $profile
            ? GeneratedPage::where('agency_profile_id', $profile->id)->where('status', 'published')->count()
            : 0;

        return view('agency.settings.integrations', compact('user', 'profile', 'pageCount'));
    }

    public function updateIntegrations(Request $request)
    {
        $request->validate([
            'copyscape_username' => 'nullable|string|max:255',
            'copyscape_api_key'  => 'nullable|string|max:255',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user->getEffectiveAgencyProfile()) {
            $user->getEffectiveAgencyProfile()->update($request->only('copyscape_username', 'copyscape_api_key'));
        }

        return back()->with('success', 'Integration settings saved.');
    }

    public function brandSettings()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $profile = $user->getEffectiveAgencyProfile();

        return view('agency.settings.brand', compact('user', 'profile'));
    }

    public function updateBrandSettings(Request $request)
    {
        $request->validate([
            'brand_primary_color' => 'nullable|string|max:20',
            'brand_secondary_color' => 'nullable|string|max:20',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user->getEffectiveAgencyProfile()) {
            $user->getEffectiveAgencyProfile()->update($request->only('brand_primary_color', 'brand_secondary_color'));
        }

        return back()->with('success', 'Brand settings saved.');
    }

    public static function uniquenessCheckMethods(): array
    {
        return [
            'villabit_manual' => 'Villa Bit AI checked & manually approved',
            'villabit_ai'     => 'Villa Bit AI uniqueness check passed',
            'copyscape'       => 'Copyscape uniqueness check passed',
        ];
    }

    public static function supportedPanelLanguages(): array
    {
        return [
            'en' => 'English',
            'hr' => 'Croatian',
            'de' => 'German',
            'fr' => 'French',
            'es' => 'Spanish',
            'it' => 'Italian',
            'pt' => 'Portuguese',
            'nl' => 'Dutch',
            'sv' => 'Swedish',
            'da' => 'Danish',
            'no' => 'Norwegian',
            'fi' => 'Finnish',
            'pl' => 'Polish',
            'cs' => 'Czech',
            'sk' => 'Slovak',
            'hu' => 'Hungarian',
            'ro' => 'Romanian',
            'bg' => 'Bulgarian',
            'el' => 'Greek',
            'tr' => 'Turkish',
            'ar' => 'Arabic',
            'ja' => 'Japanese',
            'zh' => 'Chinese',
            'ko' => 'Korean',
            'ru' => 'Russian',
            'uk' => 'Ukrainian',
            'sl' => 'Slovenian',
            'sr' => 'Serbian',
            'bs' => 'Bosnian',
            'mk' => 'Macedonian',
            'sq' => 'Albanian',
        ];
    }

    public static function supportedAiContentLanguages(): array
    {
        return [
            'English', 'Croatian', 'German', 'French', 'Spanish', 'Italian',
            'Portuguese', 'Dutch', 'Swedish', 'Danish', 'Norwegian', 'Finnish',
            'Polish', 'Czech', 'Slovak', 'Hungarian', 'Romanian', 'Bulgarian',
            'Greek', 'Turkish', 'Arabic', 'Japanese', 'Chinese (Simplified)',
            'Chinese (Traditional)', 'Korean', 'Russian', 'Ukrainian',
            'Slovenian', 'Serbian', 'Bosnian', 'Macedonian', 'Albanian',
            'Thai', 'Vietnamese', 'Indonesian', 'Malay', 'Hindi', 'Bengali',
            'Tamil', 'Hebrew', 'Persian', 'Swahili',
        ];
    }

    public function websiteDesign()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $profile = $user->getEffectiveAgencyProfile();

        return view('agency.settings.website-design', compact('user', 'profile'));
    }

    public function updateWebsiteDesign(Request $request)
    {
        $request->validate([
            'primary_color'         => 'nullable|string|max:7',
            'secondary_color'       => 'nullable|string|max:7',
            'accent_color'          => 'nullable|string|max:7',
            'custom_css'            => 'nullable|string|max:10000',
            'header_topbar_text'    => 'nullable|string|max:255',
            'header_logo_url'       => 'nullable|url|max:255',
            'header_bg_color'       => 'nullable|string|max:7',
            'header_text_color'     => 'nullable|string|max:7',
            'header_cta_text'       => 'nullable|string|max:100',
            'header_cta_url'        => 'nullable|string|max:255',
            'header_cta_bg_color'   => 'nullable|string|max:7',
            'header_cta_text_color' => 'nullable|string|max:7',
            'footer_bg_color'       => 'nullable|string|max:7',
            'footer_text_color'     => 'nullable|string|max:7',
            'footer_col1_title'     => 'nullable|string|max:100',
            'footer_col2_title'     => 'nullable|string|max:100',
            'footer_col2_text'      => 'nullable|string|max:1000',
            'footer_copyright_text' => 'nullable|string|max:255',
            'footer_terms_url'      => 'nullable|string|max:255',
            'footer_privacy_url'    => 'nullable|string|max:255',
            'sidebar_enabled'       => 'nullable|boolean',
            'sidebar_title'         => 'nullable|string|max:100',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $profile = $user->getEffectiveAgencyProfile();

        if ($profile) {
            // Build nav items from parallel arrays
            $navLabels = $request->input('nav_label', []);
            $navUrls   = $request->input('nav_url', []);
            $navItems  = [];
            foreach ($navLabels as $i => $label) {
                if (!empty(trim($label))) {
                    $navItems[] = ['label' => trim($label), 'url' => trim($navUrls[$i] ?? '#')];
                }
            }

            // Build footer col1 links from parallel arrays
            $linkLabels = $request->input('footer_link_label', []);
            $linkUrls   = $request->input('footer_link_url', []);
            $col1Links  = [];
            foreach ($linkLabels as $i => $label) {
                if (!empty(trim($label))) {
                    $col1Links[] = ['label' => trim($label), 'url' => trim($linkUrls[$i] ?? '#')];
                }
            }

            // Handle logo upload
            $logoPath = $profile->header_logo_path;
            if ($request->hasFile('header_logo')) {
                $logoPath = $request->file('header_logo')->store('logos', 'public');
            }

            // Handle sidebar promo image upload
            $promoImagePath = $profile->sidebar_promo_image;
            if ($request->hasFile('sidebar_promo_image')) {
                $promoImagePath = $request->file('sidebar_promo_image')->store('promo-images', 'public');
            }

            $profile->update([
                'website_primary_color'   => $request->primary_color,
                'website_secondary_color' => $request->secondary_color,
                'website_accent_color'    => $request->accent_color,
                'website_custom_css'      => $request->custom_css,
                'header_topbar_text'      => $request->header_topbar_text,
                'header_topbar_color'     => $request->header_topbar_color,
                'header_topbar_bg_color'  => $request->header_topbar_bg_color,
                'header_topbar_enabled'   => $request->boolean('header_topbar_enabled'),
                'header_logo_path'        => $logoPath,
                'header_logo_url'         => $request->header_logo_url,
                'header_logo_type'        => $request->header_logo_type ?? 'image',
                'header_logo_text'        => $request->header_logo_text,
                'header_bg_color'         => $request->header_bg_color,
                'header_text_color'       => $request->header_text_color,
                'header_cta_enabled'      => $request->boolean('header_cta_enabled'),
                'header_cta_text'         => $request->header_cta_text,
                'header_cta_url'          => $request->header_cta_url,
                'header_cta_bg_color'     => $request->header_cta_bg_color,
                'header_cta_text_color'   => $request->header_cta_text_color,
                'header_nav_items'        => $navItems,
                'footer_bg_color'         => $request->footer_bg_color,
                'footer_text_color'       => $request->footer_text_color,
                'footer_col1_title'       => $request->footer_col1_title,
                'footer_col1_links'       => $col1Links,
                'footer_col2_title'       => $request->footer_col2_title,
                'footer_col2_text'        => $request->footer_col2_text,
                'footer_copyright_text'   => $request->footer_copyright_text,
                'footer_terms_url'        => $request->footer_terms_url,
                'footer_privacy_url'      => $request->footer_privacy_url,
                'sidebar_enabled'         => $request->boolean('sidebar_enabled'),
                'sidebar_title'           => $request->sidebar_title,
                'sidebar_show_last_updated' => $request->boolean('sidebar_show_last_updated'),
                'sidebar_promo_enabled'   => $request->boolean('sidebar_promo_enabled'),
                'sidebar_promo_image'     => $promoImagePath,
                'sidebar_promo_title'     => $request->sidebar_promo_title,
                'sidebar_promo_text'      => $request->sidebar_promo_text,
                'sidebar_promo_url'       => $request->sidebar_promo_url,
                'sidebar_promo_button_text' => $request->sidebar_promo_button_text,
                'ai_search_promo_enabled' => $request->boolean('ai_search_promo_enabled'),
                'ai_search_promo_title'   => $request->ai_search_promo_title,
                'ai_search_promo_text'    => $request->ai_search_promo_text,
                'ai_search_promo_url'     => $request->ai_search_promo_url,
            ]);
            
            // Handle AI search promo image upload
            if ($request->hasFile('ai_search_promo_image')) {
                $aiPromoImagePath = $request->file('ai_search_promo_image')->store('agency-promo/' . $profile->id, 'public');
                $profile->update(['ai_search_promo_image' => $aiPromoImagePath]);
            }
        }

        return back()->with('success', 'Website design settings updated.');
    }
}
