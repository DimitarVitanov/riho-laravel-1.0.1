<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VillaReadyProperty;
use App\Models\VillaReadyPropertyImage;
use App\Models\VillaReadyPropertyUnit;
use App\Models\VillaReadyAgencyPublication;
use App\Models\AgencyProfile;
use App\Services\PageSftpUploader;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class AdminVillaReadyPropertyController extends Controller
{
    public function index()
    {
        $properties = VillaReadyProperty::withCount(['images', 'units', 'referrals', 'publications'])
            ->latest()
            ->paginate(20);
        
        return view('admin.villabit.villa-ready.properties.index', compact('properties'));
    }

    public function create()
    {
        $agencies = AgencyProfile::with('user')->get();
        return view('admin.villabit.villa-ready.properties.create', compact('agencies'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'property_id' => 'required|string|unique:villa_ready_properties',
            'title' => 'required|string|max:255',
            'short_title' => 'nullable|string|max:255',
            'location' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'price_display' => 'nullable|string|max:255',
            'property_type' => 'nullable|string|max:255',
            'intro' => 'nullable|string',
            'description' => 'nullable|string',
            'location_description' => 'nullable|string',
            'disclaimer' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'buildings_count' => 'nullable|integer',
            'structure' => 'nullable|string',
            'price_per_m2' => 'nullable|numeric',
            'ground_floor_range' => 'nullable|string',
            'first_floor_range' => 'nullable|string',
            'attic_range' => 'nullable|string',
            'payment_structure' => 'nullable|string',
            'vat_info' => 'nullable|string',
            'use_options' => 'nullable|string',
            'management_service' => 'nullable|string',
            'commission_percent' => 'nullable|numeric|min:0|max:100',
            'cookie_duration_days' => 'nullable|integer|min:1',
            'source_url' => 'nullable|url',
            'status' => 'required|in:draft,published,reserved,sold',
        ]);

        $validated['slug'] = Str::slug($validated['title']);
        $validated['agency_can_edit'] = $request->boolean('agency_can_edit');

        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')->store('villa-ready/properties', 'public');
        }

        $property = VillaReadyProperty::create($validated);

        // Handle gallery images
        $this->handleImageUploads($request, $property);

        // Handle units
        $this->handleUnits($request, $property);

        // Handle agency publications
        $this->handlePublications($request, $property);

        return redirect()->route('admin.villabit.villa-ready.properties.edit', $property)
            ->with('success', 'Property created successfully.');
    }

    public function edit(VillaReadyProperty $property)
    {
        $property->load(['images', 'units', 'publications.agencyProfile.user']);
        $agencies = AgencyProfile::with('user')->get();
        
        return view('admin.villabit.villa-ready.properties.edit', compact('property', 'agencies'));
    }

    public function update(Request $request, VillaReadyProperty $property)
    {
        $validated = $request->validate([
            'property_id' => 'required|string|unique:villa_ready_properties,property_id,' . $property->id,
            'title' => 'required|string|max:255',
            'short_title' => 'nullable|string|max:255',
            'location' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'price_display' => 'nullable|string|max:255',
            'property_type' => 'nullable|string|max:255',
            'intro' => 'nullable|string',
            'description' => 'nullable|string',
            'location_description' => 'nullable|string',
            'disclaimer' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'buildings_count' => 'nullable|integer',
            'structure' => 'nullable|string',
            'price_per_m2' => 'nullable|numeric',
            'ground_floor_range' => 'nullable|string',
            'first_floor_range' => 'nullable|string',
            'attic_range' => 'nullable|string',
            'payment_structure' => 'nullable|string',
            'vat_info' => 'nullable|string',
            'use_options' => 'nullable|string',
            'management_service' => 'nullable|string',
            'commission_percent' => 'nullable|numeric|min:0|max:100',
            'cookie_duration_days' => 'nullable|integer|min:1',
            'source_url' => 'nullable|url',
            'status' => 'required|in:draft,published,reserved,sold',
        ]);

        $validated['slug'] = Str::slug($validated['title']);
        $validated['agency_can_edit'] = $request->boolean('agency_can_edit');

        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            // Delete old image
            if ($property->featured_image) {
                Storage::disk('public')->delete($property->featured_image);
            }
            $validated['featured_image'] = $request->file('featured_image')->store('villa-ready/properties', 'public');
        }

        $property->update($validated);

        // Handle gallery images
        $this->handleImageUploads($request, $property);

        // Handle units
        $this->handleUnits($request, $property);

        // Handle agency publications
        $this->handlePublications($request, $property);

        return redirect()->route('admin.villabit.villa-ready.properties.edit', $property)
            ->with('success', 'Property updated successfully.');
    }

    public function destroy(VillaReadyProperty $property)
    {
        // Delete all images from storage
        foreach ($property->images as $image) {
            if (!str_starts_with($image->image_path, 'http')) {
                Storage::disk('public')->delete($image->image_path);
            }
        }
        
        if ($property->featured_image) {
            Storage::disk('public')->delete($property->featured_image);
        }

        $property->delete();

        return redirect()->route('admin.villabit.villa-ready.properties.index')
            ->with('success', 'Property deleted successfully.');
    }

    protected function handleImageUploads(Request $request, VillaReadyProperty $property): void
    {
        // Handle new image uploads
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $index => $file) {
                $path = $file->store('villa-ready/gallery', 'public');
                $type = $request->input('gallery_types.' . $index, 'gallery');
                
                VillaReadyPropertyImage::create([
                    'villa_ready_property_id' => $property->id,
                    'image_path' => $path,
                    'image_type' => $type,
                    'sort_order' => $property->images()->count(),
                ]);
            }
        }

        // Handle image URLs (for external images)
        if ($request->has('image_urls')) {
            foreach ($request->input('image_urls', []) as $index => $url) {
                if (!empty($url)) {
                    $type = $request->input('image_url_types.' . $index, 'gallery');
                    
                    VillaReadyPropertyImage::create([
                        'villa_ready_property_id' => $property->id,
                        'image_path' => $url,
                        'image_type' => $type,
                        'sort_order' => $property->images()->count(),
                    ]);
                }
            }
        }

        // Handle image deletions
        if ($request->has('delete_images')) {
            $imagesToDelete = VillaReadyPropertyImage::whereIn('id', $request->input('delete_images'))->get();
            foreach ($imagesToDelete as $image) {
                if (!str_starts_with($image->image_path, 'http')) {
                    Storage::disk('public')->delete($image->image_path);
                }
                $image->delete();
            }
        }
    }

    protected function handleUnits(Request $request, VillaReadyProperty $property): void
    {
        // Delete existing units if replacing
        if ($request->boolean('replace_units')) {
            $property->units()->delete();
        }

        // Add new units
        if ($request->has('units')) {
            foreach ($request->input('units', []) as $unitData) {
                if (!empty($unitData['unit_code']) && !empty($unitData['size_m2'])) {
                    VillaReadyPropertyUnit::updateOrCreate(
                        [
                            'villa_ready_property_id' => $property->id,
                            'unit_code' => $unitData['unit_code'],
                        ],
                        [
                            'building_number' => $unitData['building_number'] ?? 1,
                            'floor' => $unitData['floor'] ?? 'Ground Floor',
                            'size_m2' => $unitData['size_m2'],
                            'net_price' => $unitData['net_price'] ?? 0,
                            'status' => $unitData['status'] ?? 'available',
                        ]
                    );
                }
            }
        }
    }

    protected function handlePublications(Request $request, VillaReadyProperty $property): void
    {
        if ($request->has('publish_agencies')) {
            $publishAgencies = $request->input('publish_agencies', []);
            
            // Remove publications for unchecked agencies
            $property->publications()
                ->whereNotIn('agency_profile_id', $publishAgencies)
                ->delete();
            
            // Add publications for newly checked agencies
            foreach ($publishAgencies as $agencyId) {
                $agency = AgencyProfile::find($agencyId);
                if ($agency) {
                    VillaReadyAgencyPublication::firstOrCreate(
                        [
                            'villa_ready_property_id' => $property->id,
                            'agency_profile_id' => $agencyId,
                        ],
                        [
                            'affiliate_code' => VillaReadyAgencyPublication::generateAffiliateCode($agency),
                            'page_slug' => '/properties/' . $property->slug,
                            'is_published' => true,
                        ]
                    );
                }
            }
        }
    }

    public function deleteImage(VillaReadyPropertyImage $image)
    {
        if (!str_starts_with($image->image_path, 'http')) {
            Storage::disk('public')->delete($image->image_path);
        }
        $image->delete();

        return response()->json(['success' => true]);
    }

    public function deleteUnit(VillaReadyPropertyUnit $unit)
    {
        $unit->delete();
        return response()->json(['success' => true]);
    }

    /**
     * Publish property page to agency's server via SFTP.
     */
    public function publishToAgency(Request $request, VillaReadyProperty $property, AgencyProfile $agency)
    {
        $publication = VillaReadyAgencyPublication::where('villa_ready_property_id', $property->id)
            ->where('agency_profile_id', $agency->id)
            ->first();

        if (!$publication) {
            return back()->with('error', 'Property is not assigned to this agency.');
        }

        $uploader = new PageSftpUploader();
        $result = $uploader->uploadVillaReadyPropertyPage($property, $agency, $publication);

        if ($result['success']) {
            return back()->with('success', "Property page published to {$agency->agency_name}. URL: {$result['url']}");
        }

        return back()->with('error', $result['message']);
    }

    /**
     * Unpublish property page from agency's server via SFTP.
     */
    public function unpublishFromAgency(Request $request, VillaReadyProperty $property, AgencyProfile $agency)
    {
        $publication = VillaReadyAgencyPublication::where('villa_ready_property_id', $property->id)
            ->where('agency_profile_id', $agency->id)
            ->first();

        if (!$publication) {
            return back()->with('error', 'Property is not assigned to this agency.');
        }

        $uploader = new PageSftpUploader();
        $result = $uploader->deleteVillaReadyPropertyPage($property, $agency);

        if ($result['success']) {
            $publication->update([
                'is_published' => false,
                'published_url' => null,
            ]);
            return back()->with('success', "Property page removed from {$agency->agency_name}.");
        }

        return back()->with('error', $result['message']);
    }
}
