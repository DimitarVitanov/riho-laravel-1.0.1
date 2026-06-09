<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\GeneratedPage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ManagerContentReviewController extends Controller
{
    public function index(Request $request)
    {
        $manager = Auth::user();
        $assignedProfileIds = $manager->managedAgencyProfiles()->pluck('id');

        $query = GeneratedPage::whereIn('agency_profile_id', $assignedProfileIds)
            ->with('agencyProfile.user');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            $query->where('status', 'pending_review');
        }

        $pages = $query->latest()->paginate(20);
        return view('manager.content-review.index', compact('pages'));
    }

    public function show(GeneratedPage $page)
    {
        $page->load('agencyProfile.user');
        return view('manager.content-review.show', compact('page'));
    }
}
