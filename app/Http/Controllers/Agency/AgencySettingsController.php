<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use App\Models\AgencyProfile;
use App\Models\AiFeatureSetting;
use App\Models\GeneratedPage;
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
        $user    = Auth::user();
        $profile = $user->agencyProfile;

        return view('agency.settings.domain', compact('user', 'profile'));
    }

    public function updateDomainSettings(Request $request)
    {
        $request->validate([
            'custom_domain' => 'nullable|string|max:255|regex:/^([a-zA-Z0-9]([a-zA-Z0-9\-]{0,61}[a-zA-Z0-9])?\.)+[a-zA-Z]{2,}$/',
        ]);

        $user = Auth::user();
        if ($user->agencyProfile) {
            $domain = $request->custom_domain ? rtrim(strtolower($request->custom_domain), '/') : null;
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
