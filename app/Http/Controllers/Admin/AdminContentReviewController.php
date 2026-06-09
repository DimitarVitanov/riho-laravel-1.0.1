<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GeneratedPage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminContentReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = GeneratedPage::with('agencyProfile.user');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            $query->where('status', 'pending_review');
        }

        $pages = $query->latest()->paginate(20);
        return view('admin.villabit.content-review.index', compact('pages'));
    }

    public function show(GeneratedPage $page)
    {
        $page->load('agencyProfile.user');
        return view('admin.villabit.content-review.show', compact('page'));
    }

    public function approve(GeneratedPage $page)
    {
        $page->update([
            'status' => 'published',
            'published_at' => now(),
            'approved_by_user_id' => Auth::id(),
        ]);

        return redirect()->route('admin.villabit.content-review.index')
            ->with('success', 'Content approved and published.');
    }

    public function reject(GeneratedPage $page, Request $request)
    {
        $page->update([
            'status' => 'draft',
            'content_uniqueness_status' => 'failed',
        ]);

        return redirect()->route('admin.villabit.content-review.index')
            ->with('success', 'Content rejected and returned to draft.');
    }
}
