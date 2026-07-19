<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VillaReadyProperty;
use App\Models\VillaReadyPropertyContent;
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
            // Content fields (stored in villa_ready_property_content table)
            'hero_eyebrow' => 'nullable|string|max:255',
            'sidebar_price_label' => 'nullable|string|max:50',
            'sidebar_price_value' => 'nullable|string|max:50',
            'sidebar_price_note' => 'nullable|string',
            'contact_form_title' => 'nullable|string|max:255',
            'contact_form_subtitle' => 'nullable|string|max:255',
            'access_title' => 'nullable|string|max:255',
            'access_subtitle' => 'nullable|string|max:255',
            'access_intro' => 'nullable|string',
            'pricing_payment_text' => 'nullable|string',
            'tax_intro' => 'nullable|string',
            'non_eu_note' => 'nullable|string',
        ]);

        $validated['slug'] = Str::slug($validated['title']);
        $validated['agency_can_edit'] = $request->boolean('agency_can_edit');

        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')->store('villa-ready/properties', 'public');
        }

        // Separate content fields from property fields
        $contentFields = [
            'hero_eyebrow', 'sidebar_price_label', 'sidebar_price_value', 'sidebar_price_note',
            'contact_form_title', 'contact_form_subtitle', 'access_title', 'access_subtitle',
            'access_intro', 'pricing_payment_text', 'tax_intro', 'non_eu_note',
        ];
        
        $contentData = [];
        foreach ($contentFields as $field) {
            if (array_key_exists($field, $validated)) {
                $contentData[$field] = $validated[$field];
                unset($validated[$field]);
            }
        }

        // Handle hero_chips from comma-separated text
        if ($request->filled('hero_chips_text')) {
            $contentData['hero_chips'] = array_map('trim', explode(',', $request->input('hero_chips_text')));
        }

        // Handle JSON fields
        if ($request->filled('key_facts_json')) {
            $contentData['key_facts'] = json_decode($request->input('key_facts_json'), true);
        }
        if ($request->filled('access_cards_json')) {
            $contentData['access_cards'] = json_decode($request->input('access_cards_json'), true);
        }
        if ($request->filled('buildings_data_json')) {
            $contentData['buildings_data'] = json_decode($request->input('buildings_data_json'), true);
        }
        if ($request->filled('tax_groups_json')) {
            $contentData['tax_groups'] = json_decode($request->input('tax_groups_json'), true);
        }

        $property = VillaReadyProperty::create($validated);

        // Create content record
        if (!empty($contentData)) {
            $contentData['villa_ready_property_id'] = $property->id;
            VillaReadyPropertyContent::create($contentData);
        }

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
            // Content fields (stored in villa_ready_property_content table)
            'hero_eyebrow' => 'nullable|string|max:255',
            'sidebar_price_label' => 'nullable|string|max:50',
            'sidebar_price_value' => 'nullable|string|max:50',
            'sidebar_price_note' => 'nullable|string',
            'contact_form_title' => 'nullable|string|max:255',
            'contact_form_subtitle' => 'nullable|string|max:255',
            'access_title' => 'nullable|string|max:255',
            'access_subtitle' => 'nullable|string|max:255',
            'access_intro' => 'nullable|string',
            'pricing_payment_text' => 'nullable|string',
            'tax_intro' => 'nullable|string',
            'non_eu_note' => 'nullable|string',
        ]);

        $validated['slug'] = Str::slug($validated['title']);
        $validated['agency_can_edit'] = $request->boolean('agency_can_edit');

        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            if ($property->featured_image) {
                Storage::disk('public')->delete($property->featured_image);
            }
            $validated['featured_image'] = $request->file('featured_image')->store('villa-ready/properties', 'public');
        }

        // Separate content fields from property fields
        $contentFields = [
            'hero_eyebrow', 'sidebar_price_label', 'sidebar_price_value', 'sidebar_price_note',
            'contact_form_title', 'contact_form_subtitle', 'access_title', 'access_subtitle',
            'access_intro', 'pricing_payment_text', 'tax_intro', 'non_eu_note',
        ];
        
        $contentData = [];
        foreach ($contentFields as $field) {
            if (array_key_exists($field, $validated)) {
                $contentData[$field] = $validated[$field];
                unset($validated[$field]);
            }
        }

        // Handle hero_chips from comma-separated text
        if ($request->filled('hero_chips_text')) {
            $contentData['hero_chips'] = array_map('trim', explode(',', $request->input('hero_chips_text')));
        }

        // Handle JSON fields
        if ($request->filled('key_facts_json')) {
            $contentData['key_facts'] = json_decode($request->input('key_facts_json'), true);
        }
        if ($request->filled('access_cards_json')) {
            $contentData['access_cards'] = json_decode($request->input('access_cards_json'), true);
        }
        if ($request->filled('buildings_data_json')) {
            $contentData['buildings_data'] = json_decode($request->input('buildings_data_json'), true);
        }
        if ($request->filled('tax_groups_json')) {
            $contentData['tax_groups'] = json_decode($request->input('tax_groups_json'), true);
        }

        $property->update($validated);

        // Update or create content record
        if (!empty($contentData)) {
            VillaReadyPropertyContent::updateOrCreate(
                ['villa_ready_property_id' => $property->id],
                $contentData
            );
        }

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
                if ($file && $file->isValid()) {
                    $path = $file->store('villa-ready/gallery', 'public');
                    if ($path) {
                        $type = $request->input('gallery_types.' . $index, 'gallery');
                        
                        VillaReadyPropertyImage::create([
                            'villa_ready_property_id' => $property->id,
                            'image_path' => $path,
                            'image_type' => $type,
                            'sort_order' => $property->images()->count(),
                        ]);
                    }
                }
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
        // Get checked agencies (empty array if none checked)
        $publishAgencies = $request->input('publish_agencies', []);
        
        // Remove publications for unchecked agencies
        if (empty($publishAgencies)) {
            // If no agencies checked, delete all publications
            $property->publications()->delete();
        } else {
            // Delete only unchecked ones
            $property->publications()
                ->whereNotIn('agency_profile_id', $publishAgencies)
                ->delete();
        }
        
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
