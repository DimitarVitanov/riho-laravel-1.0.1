<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use App\Models\AgencyProfile;
use App\Models\AiFeatureSetting;
use App\Models\GeneratedPage;
use App\Services\DomainVerificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgencySettingsController extends Controller
{
    public function languageSettings()
    {
        $user = Auth::user();
        $profile = $user->agencyProfile;

        return view('agency.settings.language', compact('user', 'profile'));
    }

    public function updateLanguageSettings(Request $request)
    {
        $request->validate([
            'panel_language' => 'required|string|max:10',
            'ai_content_language' => 'required|string|max:50',
        ]);

        $user = Auth::user();
        $user->update(['preferred_language' => $request->panel_language]);

        if ($user->agencyProfile) {
            $user->agencyProfile->update([
                'ai_content_language' => $request->ai_content_language,
            ]);
        }

        return back()->with('success', 'Language settings updated.');
    }

    public function featureToggles()
    {
        $user = Auth::user();
        $profile = $user->agencyProfile;
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

        $user = Auth::user();
        if ($user->agencyProfile && $feature->agency_profile_id === $user->agencyProfile->id) {
            $feature->update(['is_enabled' => $request->boolean('is_enabled')]);
        }

        return back()->with('success', 'Feature toggled.');
    }

    public function domainSettings()
    {
        $user = Auth::user();
        $profile = $user->agencyProfile;

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

        return view('agency.settings.domain', compact('user', 'profile', 'domainPart1', 'domainPart2'));
    }

    public function updateDomainSettings(Request $request)
    {
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

        if ($user->agencyProfile) {
            $user->agencyProfile->update(['custom_domain' => $domain]);
        }

        return back()->with('success', 'Domain settings saved.');
    }

    public function integrations()
    {
        $user      = Auth::user();
        $profile   = $user->agencyProfile;
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

        $user = Auth::user();
        if ($user->agencyProfile) {
            $user->agencyProfile->update($request->only('copyscape_username', 'copyscape_api_key'));
        }

        return back()->with('success', 'Integration settings saved.');
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
}
