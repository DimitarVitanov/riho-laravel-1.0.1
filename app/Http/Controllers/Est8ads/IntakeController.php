<?php

namespace App\Http\Controllers\Est8ads;

use App\Http\Controllers\Controller;
use App\Models\Est8ads\Profile;
use App\Models\Est8ads\Property;
use App\Models\Est8ads\PropertyMove;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class IntakeController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_type' => ['required', 'string', 'max:100'],
            'move_type' => ['required', 'string', 'max:100'],
            'current_stage' => ['required', 'string', 'max:100'],
            'sell_title' => ['nullable', 'required_unless:move_type,Only buy a property', 'string', 'max:255'],
            'sell_type' => ['nullable', 'required_unless:move_type,Only buy a property', 'string', 'max:100'],
            'sell_country' => ['nullable', 'required_unless:move_type,Only buy a property', 'string', 'max:100'],
            'sell_city' => ['nullable', 'required_unless:move_type,Only buy a property', 'string', 'max:150'],
            'sell_address' => ['nullable', 'string', 'max:500'],
            'sell_price' => ['nullable', 'required_unless:move_type,Only buy a property', 'numeric', 'min:0', 'max:9999999999999.99'],
            'sell_currency' => ['nullable', 'string', 'size:3'],
            'sell_min_price' => ['nullable', 'numeric', 'min:0', 'lte:sell_price'],
            'sell_size' => ['nullable', 'numeric', 'min:0'],
            'sell_land_size' => ['nullable', 'numeric', 'min:0'],
            'sell_bedrooms' => ['nullable', 'integer', 'min:0', 'max:100'],
            'sell_bathrooms' => ['nullable', 'integer', 'min:0', 'max:100'],
            'sell_condition' => ['nullable', 'string', 'max:100'],
            'sell_ownership' => ['nullable', 'string', 'max:100'],
            'sell_features' => ['nullable', 'string', 'max:1000'],
            'sell_description' => ['nullable', 'required_unless:move_type,Only buy a property', 'string', 'max:10000'],
            'sell_url' => ['nullable', 'url:http,https', 'max:2000'],
            'sell_photos' => ['nullable', 'array', 'max:20'],
            'sell_photos.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
            'buy_type' => ['nullable', 'required_unless:move_type,Only sell a property', 'string', 'max:100'],
            'buy_country' => ['nullable', 'required_unless:move_type,Only sell a property', 'string', 'max:100'],
            'buy_city' => ['nullable', 'required_unless:move_type,Only sell a property', 'string', 'max:255'],
            'buy_budget_min' => ['nullable', 'numeric', 'min:0'],
            'buy_budget_max' => ['nullable', 'required_unless:move_type,Only sell a property', 'numeric', 'min:0', 'gte:buy_budget_min'],
            'buy_currency' => ['nullable', 'string', 'size:3'],
            'buy_size_min' => ['nullable', 'numeric', 'min:0'],
            'buy_size_max' => ['nullable', 'numeric', 'min:0', 'gte:buy_size_min'],
            'buy_bedrooms' => ['nullable', 'integer', 'min:0', 'max:100'],
            'buy_bathrooms' => ['nullable', 'integer', 'min:0', 'max:100'],
            'buy_condition' => ['nullable', 'string', 'max:100'],
            'financing' => ['nullable', 'string', 'max:100'],
            'buy_must_have' => ['nullable', 'string', 'max:1000'],
            'buy_flexible' => ['nullable', 'string', 'max:1000'],
            'buy_description' => ['nullable', 'required_unless:move_type,Only sell a property', 'string', 'max:10000'],
            'must_sell_first' => ['sometimes', 'accepted'],
            'temporary_housing' => ['sometimes', 'accepted'],
            'direct_exchange' => ['sometimes', 'accepted'],
            'price_flexible' => ['sometimes', 'accepted'],
            'agent_status' => ['nullable', 'string', 'max:100'],
            'chain_conditions' => ['nullable', 'string', 'max:5000'],
            'chain_notes' => ['nullable', 'string', 'max:5000'],
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email:rfc', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'language' => ['nullable', 'string', 'max:50'],
            'terms' => ['accepted'],
            'service_terms' => ['accepted'],
            'agent_contact' => ['sometimes', 'accepted'],
        ]);

        $storedPaths = [];

        try {
            DB::transaction(function () use ($request, $validated, &$storedPaths) {
                $profile = Profile::create([
                    'type' => $validated['user_type'] === 'Real estate agency or agent' ? 'agency_contact' : 'individual',
                    'status' => 'pending',
                    'first_name' => $validated['first_name'],
                    'last_name' => $validated['last_name'],
                    'email' => strtolower($validated['email']),
                    'phone' => $validated['phone'],
                    'preferred_language' => $validated['language'] ?? 'English',
                    'public_reference' => 'EST-' . strtoupper(Str::random(12)),
                    'consent_at' => now(),
                    'preferences' => ['agency_contact_allowed' => $request->boolean('agent_contact')],
                    'metadata' => ['source' => 'est8ads.com', 'user_type' => $validated['user_type']],
                ]);

                $move = PropertyMove::create([
                    'uuid' => (string) Str::uuid(),
                    'profile_id' => $profile->id,
                    'move_type' => $validated['move_type'],
                    'status' => 'submitted',
                    'title' => $validated['sell_title'] ?? ('Wanted ' . ($validated['buy_type'] ?? 'property')),
                    'current_location' => $this->location($validated['sell_city'] ?? null, $validated['sell_country'] ?? null),
                    'target_location' => $this->location($validated['buy_city'] ?? null, $validated['buy_country'] ?? null),
                    'budget_min' => $validated['buy_budget_min'] ?? null,
                    'budget_max' => $validated['buy_budget_max'] ?? null,
                    'currency' => $validated['buy_currency'] ?? $validated['sell_currency'] ?? 'EUR',
                    'notes' => $validated['chain_notes'] ?? null,
                    'requirements' => [
                        'current_stage' => $validated['current_stage'],
                        'must_sell_first' => $request->boolean('must_sell_first'),
                        'temporary_housing' => $request->boolean('temporary_housing'),
                        'direct_exchange' => $request->boolean('direct_exchange'),
                        'price_flexible' => $request->boolean('price_flexible'),
                        'agent_status' => $validated['agent_status'] ?? null,
                        'chain_conditions' => $validated['chain_conditions'] ?? null,
                    ],
                    'metadata' => ['source' => 'public_intake'],
                    'submitted_at' => now(),
                ]);

                if ($validated['move_type'] !== 'Only buy a property') {
                    $property = Property::create([
                        'uuid' => (string) Str::uuid(),
                        'property_move_id' => $move->id,
                        'reference' => 'SELL-' . strtoupper(Str::random(12)),
                        'status' => 'submitted',
                        'listing_type' => 'sell',
                        'property_type' => $validated['sell_type'],
                        'title' => $validated['sell_title'],
                        'description' => $validated['sell_description'],
                        'address' => $validated['sell_address'] ?? null,
                        'city' => $validated['sell_city'],
                        'asking_price' => $validated['sell_price'],
                        'currency' => $validated['sell_currency'] ?? 'EUR',
                        'floor_area' => $validated['sell_size'] ?? null,
                        'land_area' => $validated['sell_land_size'] ?? null,
                        'bedrooms' => $validated['sell_bedrooms'] ?? null,
                        'bathrooms' => $validated['sell_bathrooms'] ?? null,
                        'metadata' => [
                            'country' => $validated['sell_country'],
                            'minimum_price' => $validated['sell_min_price'] ?? null,
                            'condition' => $validated['sell_condition'] ?? null,
                            'ownership' => $validated['sell_ownership'] ?? null,
                            'features' => $validated['sell_features'] ?? null,
                            'listing_url' => $validated['sell_url'] ?? null,
                        ],
                    ]);

                    foreach ($request->file('sell_photos', []) as $index => $photo) {
                        $path = $photo->store('est8ads/properties/' . $property->uuid, 'public');
                        $storedPaths[] = $path;
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
                }

                if ($validated['move_type'] !== 'Only sell a property') {
                    Property::create([
                        'uuid' => (string) Str::uuid(),
                        'property_move_id' => $move->id,
                        'reference' => 'BUY-' . strtoupper(Str::random(12)),
                        'status' => 'submitted',
                        'listing_type' => 'wanted',
                        'property_type' => $validated['buy_type'],
                        'title' => 'Wanted ' . $validated['buy_type'] . ' in ' . $validated['buy_city'],
                        'description' => $validated['buy_description'],
                        'city' => $validated['buy_city'],
                        'asking_price' => $validated['buy_budget_max'],
                        'currency' => $validated['buy_currency'] ?? 'EUR',
                        'floor_area' => $validated['buy_size_min'] ?? null,
                        'bedrooms' => $validated['buy_bedrooms'] ?? null,
                        'bathrooms' => $validated['buy_bathrooms'] ?? null,
                        'metadata' => [
                            'country' => $validated['buy_country'],
                            'budget_min' => $validated['buy_budget_min'] ?? null,
                            'size_max' => $validated['buy_size_max'] ?? null,
                            'condition' => $validated['buy_condition'] ?? null,
                            'financing' => $validated['financing'] ?? null,
                            'must_have' => $validated['buy_must_have'] ?? null,
                            'flexible_preferences' => $validated['buy_flexible'] ?? null,
                        ],
                    ]);
                }
            });
        } catch (\Throwable $exception) {
            foreach ($storedPaths as $path) {
                Storage::disk('public')->delete($path);
            }

            throw $exception;
        }

        return back()->with('est8ads_success', 'Your property move has been submitted for EST8ADS analysis.');
    }

    private function location(?string $city, ?string $country): ?string
    {
        $location = implode(', ', array_filter([$city, $country]));

        return $location !== '' ? $location : null;
    }
}
