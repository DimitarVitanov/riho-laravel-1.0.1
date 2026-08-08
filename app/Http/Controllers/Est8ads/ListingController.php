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

        $user = $request->user();
        $agencyProfile = $user->getEffectiveAgencyProfile();

        // A Villa Bit agency publishes its EST8ADS sell listing into the shared
        // agency_listings pool — that is the deliberate Villa Bit <-> EST8ADS
        // integration. Everyone else (private members and EST8ADS-only accounts)
        // stays entirely inside EST8ADS, so nothing they create ever leaks into
        // Villa Bit.
        $sharedWithVillabit = $validated['side'] === 'sell' && $agencyProfile && $user->has_villabit_access;

        if ($sharedWithVillabit) {
            $result = DB::transaction(function () use ($request, $validated, $agencyProfile) {
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
            });
        } else {
            $result = DB::transaction(function () use ($request, $validated, $user) {
                $profile = Profile::where('user_id', $user->id)->firstOrFail();
                $isSell = $validated['side'] === 'sell';

                $move = PropertyMove::create([
                    'uuid' => (string) Str::uuid(),
                    'profile_id' => $profile->id,
                    'agency_id' => $profile->agency_id,
                    'move_type' => $isSell ? 'sell' : 'buy',
                    'status' => 'active',
                    'title' => $validated['title'],
                    'current_location' => $isSell ? $validated['city'] . ', ' . $validated['country'] : null,
                    'target_location' => $isSell ? null : $validated['city'] . ', ' . $validated['country'],
                    'budget_max' => $isSell ? null : $validated['price'],
                    'currency' => $validated['currency'],
                    // Only a "buy" needs the internet searched; a sell just joins
                    // the pool so other members' buys can match against it.
                    'submitted_at' => $isSell ? null : now(),
                ]);

                $property = Property::create([
                    'uuid' => (string) Str::uuid(),
                    'agency_id' => $profile->agency_id,
                    'property_move_id' => $move->id,
                    'reference' => ($isSell ? 'SELL-' : 'BUY-') . strtoupper(Str::random(12)),
                    'status' => 'active',
                    'listing_type' => $isSell ? 'sell' : 'wanted',
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
                    'metadata' => [
                        'country' => $validated['country'],
                        'source' => 'est8ads_panel',
                        'listing_url' => $validated['url'] ?? null,
                    ],
                ]);

                foreach ($request->file('images', []) as $index => $photo) {
                    $path = $photo->store('est8ads/properties/' . $property->uuid, 'public');
                    DB::table('est8ads_property_media')->insert([
                        'property_id' => $property->id,
                        'type' => 'image',
                        'disk' => 'public',
                        'path' => $path,
                        'title' => $photo->getClientOriginalName(),
                        'mime_type' => $photo->getMimeType(),
                        'size_bytes' => $photo->getSize(),
                        'sort_order' => $index,
                        'is_primary' => $index === 0,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                return ['id' => ($isSell ? 'S-' : 'R-') . $property->id, 'type' => $isSell ? 'sell' : 'wanted'];
            });
        }

        $message = match ($result['type']) {
            'wanted' => 'Property saved — our AI is now searching the market. Potential matches will appear here in a few minutes.',
            'sell' => 'Property saved — it is now published on EST8ADS and matched against interested buyers.',
            default => 'Property saved successfully.',
        };

        return response()->json(['message' => $message, ...$result], 201);
    }
}
