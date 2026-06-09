<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use App\Models\GeneratedPage;
use Illuminate\Support\Facades\Auth;

class AgencyGeneratedPageController extends Controller
{
    public function index()
    {
        $profile = Auth::user()->agencyProfile;
        $pages = collect();

        if ($profile) {
            $pages = GeneratedPage::where('agency_profile_id', $profile->id)
                ->latest()
                ->paginate(20);
        }

        return view('agency.generated-pages.index', compact('pages'));
    }

    public function show(GeneratedPage $page)
    {
        return view('agency.generated-pages.show', compact('page'));
    }
}
