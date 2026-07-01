<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use App\Models\GeneratedPage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

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

    public function create()
    {
        return view('agency.generated-pages.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content_html' => 'required|string',
            'seo_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'feature_key' => 'required|string|max:100',
        ]);

        $profile = Auth::user()->agencyProfile;

        if (!$profile) {
            return back()->with('error', 'Agency profile not found.');
        }

        $page = GeneratedPage::create([
            'agency_profile_id' => $profile->id,
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'content_html' => $request->content_html,
            'seo_title' => $request->seo_title ?? $request->title,
            'meta_description' => $request->meta_description,
            'feature_key' => $request->feature_key,
            'status' => 'draft',
            'content_uniqueness_status' => 'pending',
            'publish_workflow' => 'manual',
        ]);

        return redirect()->route('agency.generated-pages.show', $page)
            ->with('success', 'Article created successfully.');
    }

    public function show(GeneratedPage $page)
    {
        return view('agency.generated-pages.show', compact('page'));
    }

    public function edit(GeneratedPage $page)
    {
        return view('agency.generated-pages.edit', compact('page'));
    }

    public function update(Request $request, GeneratedPage $page)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content_html' => 'required|string',
            'seo_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'feature_key' => 'required|string|max:100',
        ]);

        $page->update([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'content_html' => $request->content_html,
            'seo_title' => $request->seo_title ?? $request->title,
            'meta_description' => $request->meta_description,
            'feature_key' => $request->feature_key,
        ]);

        return redirect()->route('agency.generated-pages.show', $page)
            ->with('success', 'Article updated successfully.');
    }

    public function publish(GeneratedPage $page)
    {
        $page->update([
            'status' => 'published',
            'published_at' => now(),
            'approved_by_user_id' => Auth::id(),
        ]);

        return back()->with('success', 'Article published successfully.');
    }

    public function unpublish(GeneratedPage $page)
    {
        $page->update([
            'status' => 'draft',
            'published_at' => null,
        ]);

        return back()->with('success', 'Article unpublished.');
    }

    public function destroy(GeneratedPage $page)
    {
        $page->delete();

        return redirect()->route('agency.generated-pages.index')
            ->with('success', 'Article deleted.');
    }

    public function preview(GeneratedPage $page)
    {
        $profile = Auth::user()->agencyProfile;

        return view('public-pages.article', [
            'page' => $page,
            'profile' => $profile,
        ]);
    }
}
