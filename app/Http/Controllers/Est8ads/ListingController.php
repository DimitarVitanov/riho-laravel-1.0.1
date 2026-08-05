<?php

namespace App\Http\Controllers\Est8ads;

use App\Http\Controllers\Controller;
use App\Models\Est8ads\Profile;
use App\Models\Est8ads\Property;
use App\Models\Est8ads\PropertyMove;
use App\Services\Est8ads\BillingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ListingController extends Controller
{
    public function store(Request $request, BillingService $billing): JsonResponse
    {
        // Defense in depth: an account still waiting for its first payment is
        // held on the "waiting for payment" screen and must not be able to
        // create listings by posting to this endpoint directly.
        $profile = Profile::where('user_id', $request->user()->id)->first();
        if ($profile && $billing->awaitingFirstPayment($profile)) {
            return response()->json([
                'message' => 'Please complete your first payment to activate your workspace.',
            ], 402);
        }

        $validated = $request->validate([
            'side' => ['required', 'in:sell,buy'],
            'type' => ['required', 'string', 'max:100'],
            'title' => ['required', 'string', 'max:255'],
            'country' => ['required', 'string', 'max:100'],
            'city' => ['required', 'string', 'max:255'],
            'area' => ['nullable', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'currency' => ['required', 'string', 'size:3'],
            'size' => ['nullable', 'numeric', 'min:0'],
            'beds' => ['nullable', 'integer', 'min:0'],
            'baths' => ['nullable', 'integer', 'min:0'],
            'description' => ['nullable', 'string', 'max:10000'],
            'url' => ['nullable', 'url:http,https', 'max:2000'],
            'images' => ['nullable', 'array', 'max:20'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
        ]);

        $result = DB::transaction(function () use ($request, $validated) {
            if ($validated['side'] === 'sell') {
                $agencyProfile = $request->user()->getEffectiveAgencyProfile();
                abort_unless($agencyProfile, 422, 'An agency profile is required to publish a sell listing.');

                $images = [];
                foreach ($request->file('images', []) as $image) {
                    $images[] = $image->store('agency-listings/' . $agencyProfile->id, 'public');
                }

                $listing = $agencyProfile->agencyListings()->create([
                    'title' => $validated['title'],
                    'property_type' => $validated['type'],
                    'location' => $validated['area'] ?: $validated['city'],
                    'primary_city' => $validated['city'],
                    'country' => $validated['country'],
                    'description' => $validated['description'] ?? null,
                    'price' => $validated['price'],
                    'currency' => $validated['currency'],
                    'images_json' => $images,
                    'status' => 'active',
                    'external_url' => $validated['url'] ?? null,
                    'living_area' => $validated['size'] ?? null,
                    'bedrooms' => $validated['beds'] ?? null,
                    'bathrooms' => $validated['baths'] ?? null,
                ]);

                return ['id' => 'P-' . $listing->id, 'type' => 'listing'];
            }

            $profile = Profile::where('user_id', $request->user()->id)->firstOrFail();
            $move = PropertyMove::create([
                'uuid' => (string) Str::uuid(),
                'profile_id' => $profile->id,
                'agency_id' => $profile->agency_id,
                'move_type' => 'buy',
                'status' => 'active',
                'title' => $validated['title'],
                'target_location' => $validated['city'] . ', ' . $validated['country'],
                'budget_max' => $validated['price'],
                'currency' => $validated['currency'],
                'submitted_at' => now(),
            ]);
            $property = Property::create([
                'uuid' => (string) Str::uuid(),
                'agency_id' => $profile->agency_id,
                'property_move_id' => $move->id,
                'reference' => 'BUY-' . strtoupper(Str::random(12)),
                'status' => 'active',
                'listing_type' => 'wanted',
                'property_type' => $validated['type'],
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'city' => $validated['city'],
                'region' => $validated['area'] ?? null,
                'asking_price' => $validated['price'],
                'currency' => $validated['currency'],
                'floor_area' => $validated['size'] ?? null,
                'bedrooms' => $validated['beds'] ?? null,
                'bathrooms' => $validated['baths'] ?? null,
                'metadata' => ['country' => $validated['country'], 'source' => 'est8ads_panel'],
            ]);

            return ['id' => 'R-' . $property->id, 'type' => 'wanted'];
        });

        return response()->json(['message' => 'Property saved successfully.', ...$result], 201);
    }
}
