<?php

namespace App\Http\Controllers;

use App\Models\AgencyProfile;
use App\Models\GeneratedPage;
use Illuminate\Http\Request;

class PublicPageController extends Controller
{
    public function show(Request $request, string $slug)
    {
        $domain = $request->getHost();

        $profile = AgencyProfile::where('custom_domain', $domain)->first();

        if (!$profile) {
            abort(404);
        }

        $page = GeneratedPage::where('agency_profile_id', $profile->id)
            ->where('slug', $slug)
            ->published()
            ->firstOrFail();

        return view('public-pages.article', [
            'page' => $page,
            'profile' => $profile,
        ]);
    }

    public function index(Request $request)
    {
        $domain = $request->getHost();

        $profile = AgencyProfile::where('custom_domain', $domain)->first();

        if (!$profile) {
            abort(404);
        }

        $pages = GeneratedPage::where('agency_profile_id', $profile->id)
            ->published()
            ->latest('published_at')
            ->paginate(12);

        return view('public-pages.index', [
            'pages' => $pages,
            'profile' => $profile,
        ]);
    }
}
