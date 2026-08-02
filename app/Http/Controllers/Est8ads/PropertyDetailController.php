<?php

namespace App\Http\Controllers\Est8ads;

use App\Http\Controllers\Controller;
use App\Models\AgencyListing;
use App\Models\Est8ads\AuditLog;
use App\Models\Est8ads\MatchResult;
use App\Models\Est8ads\Property;
use App\Models\Est8ads\PropertyMove;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PropertyDetailController extends Controller
{
    public function show(Request $request, MatchResult $matchResult): View
    {
        $matchResult->load(['propertyMove.profile.user', 'propertyMove.agency', 'propertyMove.properties', 'property.agency', 'chain.agency']);
        $this->authorizeRecord($request->user(), $matchResult);

        $move = $matchResult->propertyMove;
        $sell = $move->properties->firstWhere('listing_type', 'sell') ?: $matchResult->property;
        $buy = $move->properties->firstWhere('listing_type', 'wanted');

        return $this->render($request, $move, $sell, $buy, $matchResult, ['type' => 'match', 'id' => $matchResult->id]);
    }

    public function showProperty(Request $request, Property $property): View
    {
        $property->load(['propertyMove.profile.user', 'propertyMove.agency', 'propertyMove.properties', 'agency', 'matches.chain']);
        $this->authorizeRecord($request->user(), $property);

        $move = $property->propertyMove;
        $properties = $move?->properties ?? collect([$property]);
        $sell = $properties->firstWhere('listing_type', 'sell');
        $buy = $properties->firstWhere('listing_type', 'wanted');
        $match = $property->matches->sortByDesc('score')->first();

        return $this->render($request, $move, $sell ?: ($property->listing_type !== 'wanted' ? $property : null), $buy ?: ($property->listing_type === 'wanted' ? $property : null), $match, ['type' => 'property', 'id' => $property->id]);
    }

    public function showAgencyListing(Request $request, AgencyListing $agencyListing): View
    {
        $agencyListing->load('agencyProfile.user');
        $this->authorizeRecord($request->user(), $agencyListing);

        $sell = $this->agencyListingData($agencyListing);
        $buy = $agencyListing->looking_to_buy ? $this->agencyListingWantedData($agencyListing) : null;

        return view('est8ads.details.show', [
            'detail' => [
                'kind' => 'agency-listing',
                'focus' => $request->string('focus')->value() === 'buy' ? 'buy' : 'sell',
                'title' => $agencyListing->title,
                'status' => $agencyListing->status,
                'ids' => ['listing' => 'P-' . $agencyListing->id],
                'about' => [
                    'Submission type' => 'Agency listing',
                    'Agency / account' => $agencyListing->agencyProfile?->agency_name,
                    'Owner' => $agencyListing->agencyProfile?->user?->full_name,
                ],
                'sell' => $sell,
                'buy' => $buy,
                'chain' => [],
                'contact' => [
                    'Account name' => $agencyListing->agencyProfile?->user?->full_name,
                    'Email address' => $agencyListing->agencyProfile?->contact_email ?: $agencyListing->agencyProfile?->user?->email,
                    'Phone number' => $agencyListing->agencyProfile?->contact_phone ?: $agencyListing->agencyProfile?->user?->phone,
                ],
                'consents' => [],
                'media' => collect($agencyListing->images)->map(fn ($image, $index) => [
                    'url' => is_array($image) ? ($image['url'] ?? $image['path'] ?? null) : $image,
                    'title' => is_array($image) ? ($image['title'] ?? 'Property photo ' . ($index + 1)) : 'Property photo ' . ($index + 1),
                ])->filter(fn ($image) => filled($image['url']))->values()->all(),
                'match' => null,
                'chainContext' => null,
                'agency' => $agencyListing->agencyProfile?->agency_name,
                'createdAt' => $agencyListing->created_at,
                'updatedAt' => $agencyListing->updated_at,
                'isAdmin' => $request->user()->isAdmin(),
                'action' => ['type' => 'agency-listing', 'id' => $agencyListing->id],
            ],
        ]);
    }

    public function approve(Request $request, string $type, int $id): RedirectResponse
    {
        return $this->changeStatus($request, $type, $id, true);
    }

    public function correction(Request $request, string $type, int $id): RedirectResponse
    {
        $request->validate(['note' => ['required', 'string', 'max:2000']]);

        return $this->changeStatus($request, $type, $id, false);
    }

    public function approveMatch(Request $request, MatchResult $matchResult): RedirectResponse
    {
        return $this->changeStatus($request, 'match', $matchResult->id, true);
    }

    public function correctMatch(Request $request, MatchResult $matchResult): RedirectResponse
    {
        $request->validate(['note' => ['required', 'string', 'max:2000']]);
        return $this->changeStatus($request, 'match', $matchResult->id, false);
    }

    public function approveProperty(Request $request, Property $property): RedirectResponse
    {
        return $this->changeStatus($request, 'property', $property->id, true);
    }

    public function correctProperty(Request $request, Property $property): RedirectResponse
    {
        $request->validate(['note' => ['required', 'string', 'max:2000']]);
        return $this->changeStatus($request, 'property', $property->id, false);
    }

    public function approveAgencyListing(Request $request, AgencyListing $agencyListing): RedirectResponse
    {
        return $this->changeStatus($request, 'agency-listing', $agencyListing->id, true);
    }

    public function correctAgencyListing(Request $request, AgencyListing $agencyListing): RedirectResponse
    {
        $request->validate(['note' => ['required', 'string', 'max:2000']]);
        return $this->changeStatus($request, 'agency-listing', $agencyListing->id, false);
    }

    private function render(Request $request, ?PropertyMove $move, ?Property $sell, ?Property $buy, ?MatchResult $match, array $action): View
    {
        $profile = $move?->profile;
        $media = $sell ? DB::table('est8ads_property_media')->where('property_id', $sell->id)->orderBy('sort_order')->get() : collect();
        $requirements = $move?->requirements ?? [];
        $preferences = $profile?->preferences ?? [];
        $chain = $match?->chain;

        return view('est8ads.details.show', [
            'detail' => [
                'kind' => $match ? 'match' : 'property',
                'focus' => in_array($request->string('focus')->value(), ['sell', 'buy'], true) ? $request->string('focus')->value() : ($sell ? 'sell' : 'buy'),
                'title' => $move?->title ?: $sell?->title ?: $buy?->title,
                'status' => $match?->status ?: $sell?->status ?: $buy?->status,
                'ids' => array_filter([
                    'match' => $match ? 'MT-' . $match->id : null,
                    'request' => $move ? 'MV-' . $move->id : null,
                    'sell' => $sell?->reference,
                    'buy' => $buy?->reference,
                    'chain' => $chain ? 'C-' . $chain->id : null,
                ]),
                'about' => array_filter([
                    'User type' => $profile?->metadata['user_type'] ?? $profile?->type,
                    'Move type' => $move?->move_type,
                    'Current stage' => $requirements['current_stage'] ?? null,
                    'Submission source' => $move?->metadata['source'] ?? null,
                    'Agency / account' => $move?->agency?->name,
                    'Target date' => $move?->target_date?->toDateString(),
                    'Current location' => $move?->current_location,
                    'Target location' => $move?->target_location,
                ], fn ($value) => $value !== null && $value !== ''),
                'sell' => $sell ? $this->propertyData($sell, false) : null,
                'buy' => $buy ? $this->propertyData($buy, true) : null,
                'chain' => [
                    'I must sell before I can buy' => (bool) ($requirements['must_sell_first'] ?? false),
                    'Temporary housing is possible' => (bool) ($requirements['temporary_housing'] ?? false),
                    'Open to a direct exchange' => (bool) ($requirements['direct_exchange'] ?? false),
                    'Selling price is flexible' => (bool) ($requirements['price_flexible'] ?? false),
                    'Current real estate agent' => $requirements['agent_status'] ?? null,
                    'Conditions that must be met' => $requirements['chain_conditions'] ?? null,
                    'Additional information for AI chain analysis' => $move?->notes,
                ],
                'contact' => array_filter([
                    'First name' => $profile?->first_name,
                    'Last name' => $profile?->last_name,
                    'Company name' => $profile?->company_name,
                    'Email address' => $profile?->email,
                    'Phone number' => $profile?->phone,
                    'Address' => $profile?->address,
                    'City' => $profile?->city,
                    'Postal code' => $profile?->postal_code,
                    'Country code' => $profile?->country_code,
                    'Preferred language' => $profile?->preferred_language,
                    'Public profile reference' => $profile?->public_reference,
                ], fn ($value) => $value !== null && $value !== ''),
                'consents' => [
                    'Terms of Use and Privacy Policy accepted' => (bool) $profile?->consent_at,
                    'Verified agencies may contact participant' => (bool) ($preferences['agency_contact_allowed'] ?? false),
                ],
                'media' => $media->map(fn ($item) => [
                    'url' => $item->url ?: Storage::disk($item->disk)->url($item->path),
                    'title' => $item->title ?: $item->alt_text ?: 'Property photo',
                    'type' => $item->type,
                ])->all(),
                'match' => $match ? [
                    'id' => 'MT-' . $match->id,
                    'uuid' => $match->uuid,
                    'type' => $match->match_type,
                    'status' => $match->status,
                    'score' => round((float) $match->score * 100, 2),
                    'breakdown' => $match->score_breakdown ?? [],
                    'explanation' => $match->explanation ?? [],
                    'algorithm' => $match->algorithm_version,
                    'expiresAt' => $match->expires_at,
                ] : null,
                'chainContext' => $chain ? [
                    'id' => 'C-' . $chain->id,
                    'uuid' => $chain->uuid,
                    'name' => $chain->name,
                    'status' => $chain->status,
                    'nodes' => $chain->node_count,
                    'confidence' => $chain->confidence_score !== null ? round((float) $chain->confidence_score * 100, 2) : null,
                    'summary' => $chain->summary,
                ] : null,
                'agency' => $move?->agency?->name ?: $sell?->agency?->name ?: $buy?->agency?->name,
                'createdAt' => $match?->created_at ?: $move?->created_at ?: $sell?->created_at ?: $buy?->created_at,
                'updatedAt' => $match?->updated_at ?: $move?->updated_at ?: $sell?->updated_at ?: $buy?->updated_at,
                'isAdmin' => $request->user()->isAdmin(),
                'action' => $action,
            ],
        ]);
    }

    private function propertyData(Property $property, bool $wanted): array
    {
        $metadata = $property->metadata ?? [];

        return array_filter([
            $wanted ? 'Wanted property type' : 'Listing title' => $wanted ? $property->property_type : $property->title,
            ...(!$wanted ? ['Property type' => $property->property_type] : []),
            'Country' => $metadata['country'] ?? $property->country_code,
            'City / area' => implode(', ', array_filter([$property->city, $property->region])),
            ...(!$wanted ? ['Address or micro-location' => $property->address] : []),
            $wanted ? 'Maximum budget' : 'Asking price' => $this->money($property->asking_price, $property->currency),
            ...($wanted ? ['Minimum budget' => $this->money($metadata['budget_min'] ?? null, $property->currency)] : ['Lowest acceptable price' => $this->money($metadata['minimum_price'] ?? null, $property->currency)]),
            $wanted ? 'Minimum interior size' : 'Interior size' => $property->floor_area !== null ? $property->floor_area . ' m²' : null,
            ...($wanted ? ['Maximum interior size' => isset($metadata['size_max']) ? $metadata['size_max'] . ' m²' : null] : ['Land size' => $property->land_area !== null ? $property->land_area . ' m²' : null]),
            $wanted ? 'Minimum bedrooms' : 'Bedrooms' => $property->bedrooms,
            $wanted ? 'Minimum bathrooms' : 'Bathrooms' => $property->bathrooms,
            'Property condition' => $metadata['condition'] ?? null,
            ...(!$wanted ? ['Ownership status' => $metadata['ownership'] ?? null, 'Main property features' => $metadata['features'] ?? null, 'Existing listing URL' => $metadata['listing_url'] ?? null] : []),
            ...($wanted ? ['Purchase financing' => $metadata['financing'] ?? null, 'Must-have features' => $metadata['must_have'] ?? null, 'Flexible preferences' => $metadata['flexible_preferences'] ?? null] : []),
            $wanted ? 'Ideal next property description' : 'Property description' => $property->description,
            'System reference' => $property->reference,
            'System UUID' => $property->uuid,
        ], fn ($value) => $value !== null && $value !== '');
    }

    private function agencyListingData(AgencyListing $listing): array
    {
        return array_filter([
            'Listing title' => $listing->title, 'Property type' => $listing->property_type, 'Country' => $listing->country,
            'City / area' => implode(', ', array_filter([$listing->primary_city, $listing->location])), 'Asking price' => $this->money($listing->price, $listing->currency),
            'Interior size' => ($listing->living_area ?: $listing->size) ? ($listing->living_area ?: $listing->size) . ' m²' : null,
            'Land size' => $listing->plot_size ? $listing->plot_size . ' m²' : null, 'Bedrooms' => $listing->bedrooms, 'Bathrooms' => $listing->bathrooms,
            'Property condition' => $listing->property_condition, 'Year built' => $listing->year_built, 'Main property features' => $listing->features,
            'Property description' => $listing->description, 'Existing listing URL' => $listing->external_url,
        ], fn ($value) => $value !== null && $value !== '');
    }

    private function agencyListingWantedData(AgencyListing $listing): array
    {
        return array_filter([
            'Wanted property type' => $listing->looking_property_type, 'Preferred locations' => implode(', ', $listing->looking_locations ?: array_filter([$listing->looking_location])),
            'Minimum budget' => $this->money($listing->looking_budget_min, $listing->looking_currency), 'Maximum budget' => $this->money($listing->looking_budget_max, $listing->looking_currency),
            'Minimum interior size' => $listing->looking_min_size ? $listing->looking_min_size . ' m²' : null, 'Minimum bedrooms' => $listing->looking_min_bedrooms,
            'Timeline' => $listing->looking_timeline, 'Requirements / notes' => $listing->looking_notes,
        ], fn ($value) => $value !== null && $value !== '');
    }

    private function authorizeRecord(?User $user, Model $record): void
    {
        abort_unless($user, 403);
        if ($user->isAdmin()) return;

        $allowed = match (true) {
            $record instanceof MatchResult => $this->canAccessMove($user, $record->propertyMove)
                || ($record->property && $this->canAccessProperty($user, $record->property))
                || ($record->chain?->agency_id && $this->isAgencyMember($user, (int) $record->chain->agency_id)),
            $record instanceof Property => $this->canAccessProperty($user, $record),
            $record instanceof AgencyListing => (int) $record->agencyProfile?->user_id === (int) $user->id,
            default => false,
        };

        abort_unless($allowed, 403);
    }

    private function canAccessProperty(User $user, Property $property): bool
    {
        if ($property->propertyMove && $this->canAccessMove($user, $property->propertyMove)) return true;
        if ($property->agency_id && $this->isAgencyMember($user, (int) $property->agency_id)) return true;

        return DB::table('est8ads_property_participants')->where('property_id', $property->id)->where('status', 'active')
            ->where(function ($query) use ($user) {
                $query->where('user_id', $user->id)->orWhereIn('profile_id', DB::table('est8ads_profiles')->select('id')->where('user_id', $user->id));
            })->exists();
    }

    private function canAccessMove(User $user, ?PropertyMove $move): bool
    {
        if (!$move) return false;

        return (int) $move->profile?->user_id === (int) $user->id
            || (int) $move->assigned_user_id === (int) $user->id
            || ($move->agency_id && $this->isAgencyMember($user, (int) $move->agency_id));
    }

    private function isAgencyMember(User $user, int $agencyId): bool
    {
        return DB::table('est8ads_agency_memberships')->where(['agency_id' => $agencyId, 'user_id' => $user->id, 'status' => 'active'])->exists();
    }

    private function changeStatus(Request $request, string $type, int $id, bool $approved): RedirectResponse
    {
        abort_unless($request->user()?->isAdmin(), 403);
        $models = ['match' => MatchResult::class, 'property' => Property::class, 'agency-listing' => AgencyListing::class];
        abort_unless(isset($models[$type]), 404);
        $record = $models[$type]::query()->findOrFail($id);
        $status = $approved ? ($type === 'match' ? 'approved' : 'active') : 'correction_requested';
        $old = ['status' => $record->status];

        DB::transaction(function () use ($request, $record, $status, $old, $approved) {
            $record->update(['status' => $status]);
            AuditLog::create([
                'agency_id' => $record instanceof MatchResult ? $record->propertyMove?->agency_id : ($record instanceof Property ? $record->agency_id : null),
                'user_id' => $request->user()->id,
                'event' => $approved ? 'record.approved' : 'record.correction_requested',
                'auditable_type' => $record->getMorphClass(),
                'auditable_id' => $record->getKey(),
                'old_values' => $old,
                'new_values' => ['status' => $status],
                'ip_address' => $request->ip(),
                'user_agent' => Str::limit((string) $request->userAgent(), 1000, ''),
                'request_id' => $request->header('X-Request-ID') ?: (string) Str::uuid(),
                'metadata' => ['note' => $request->input('note')],
            ]);
        });

        return back()->with('est8ads_success', $approved ? 'Record approved.' : 'Correction requested.');
    }

    private function money(mixed $amount, ?string $currency): ?string
    {
        return $amount === null || $amount === '' ? null : number_format((float) $amount, 0) . ' ' . ($currency ?: 'EUR');
    }
}
