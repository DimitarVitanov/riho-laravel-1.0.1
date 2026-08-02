<?php

namespace App\Services\Est8ads;

use App\Models\AgencyListing;
use App\Models\AgencyProfile;
use App\Models\Est8ads\Chain;
use App\Models\Est8ads\ExternalListingMatch;
use App\Models\Est8ads\Payment;
use App\Models\User;
use App\Services\Est8ads\Discovery\ChainDiscoveryDispatcher;
use App\Services\Est8ads\Discovery\DiscoveryPresenter;
use App\Services\Est8ads\Discovery\DiscoverySettings;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class PanelData
{
    public function __construct(
        private DiscoveryPresenter $discoveryPresenter,
        private DiscoverySettings $discoverySettings,
    ) {
    }

    public function forUser(User $user): array
    {
        $agencyProfile = $user->getEffectiveAgencyProfile();
        $listings = $agencyProfile
            ? $this->listingQuery()->where('agency_profile_id', $agencyProfile->id)->get()
            : collect();

        $profile = \App\Models\Est8ads\Profile::where('user_id', $user->id)->first();
        $moves = $profile ? $profile->propertyMoves()->latest()->get() : collect();

        $payload = $this->payload($listings, collect(), $agencyProfile ? collect([$agencyProfile]) : collect(), $moves);
        $payload['chainMatches'] = $this->chainMatches($moves->pluck('id')->all());
        $payload['chainTolerance'] = ChainDiscoveryDispatcher::defaultTolerance();
        $payload['latestDiscoveryJob'] = $this->latestDiscoveryActivity($moves->pluck('id')->all());

        return $payload;
    }

    public function forAdmin(): array
    {
        $moves = \App\Models\Est8ads\PropertyMove::with('profile')->latest()->limit(100)->get();

        $payload = $this->payload(
            $this->listingQuery()->get(),
            User::where('has_est8ads_access', true)->get(),
            AgencyProfile::with('user')->whereHas('user', fn (Builder $query) => $query->where('has_est8ads_access', true))->get(),
            $moves,
        );

        $discovery = $this->discoveryPresenter->panelState($this->discoverySettings);
        $discovery['requests'] = $payload['requests'];

        $payload['discovery'] = $discovery;
        $payload['discoveryJobs'] = $discovery['jobs'];
        $payload['discoveryResults'] = $discovery['matches'];
        $payload['chainMatches'] = $this->chainMatches($moves->pluck('id')->all());
        $payload['chainTolerance'] = ChainDiscoveryDispatcher::defaultTolerance();

        return $payload;
    }

    /**
     * Internet listings discovered for the given property moves, exact matches
     * first and near matches afterwards, each ordered by score.
     *
     * @param  array<int, int>  $moveIds
     * @return array<int, array<string, mixed>>
     */
    private function chainMatches(array $moveIds, int $limit = 30): array
    {
        if ($moveIds === []) {
            return [];
        }

        return ExternalListingMatch::with('externalListing')
            ->whereIn('property_move_id', $moveIds)
            ->whereIn('match_type', ['exact', 'tolerance'])
            ->orderByRaw("FIELD(match_type, 'exact', 'tolerance')")
            ->orderByDesc('final_score')
            ->limit($limit)
            ->get()
            ->map(function (ExternalListingMatch $match) {
                $listing = $match->externalListing;
                $tolerance = $match->tolerance ?? [];

                $media = $listing?->media ? json_decode($listing->media, true) : null;
                $firstImage = null;
                if (is_array($media) && isset($media['images']) && is_array($media['images']) && count($media['images']) > 0) {
                    $firstImage = $media['images'][0]['url'] ?? $media['images'][0] ?? null;
                }

                return [
                    'id' => $match->id,
                    'kind' => $match->match_type,
                    'title' => $listing?->title ?: 'Discovered listing',
                    'city' => $listing?->city,
                    'size' => $listing?->size_m2,
                    'price' => $listing?->price,
                    'currency' => $listing?->currency ?: 'EUR',
                    'score' => (float) ($match->final_score ?? 0),
                    'url' => $listing?->canonical_url,
                    'image' => $firstImage,
                    'explanation' => $match->explanation,
                    'sizeNote' => $this->deviationNote($tolerance['size_status'] ?? null, $tolerance['size_deviation'] ?? null),
                    'priceNote' => $this->deviationNote($tolerance['price_status'] ?? null, $tolerance['price_deviation'] ?? null),
                ];
            })
            ->all();
    }

    private function deviationNote(?string $status, mixed $deviation): ?string
    {
        if ($status !== 'tolerance' || $deviation === null) {
            return null;
        }

        return sprintf('%+.1f%% vs requested', (float) $deviation);
    }

    private function listingQuery(): Builder
    {
        return AgencyListing::query()
            ->with('agencyProfile.user')
            ->latest();
    }

    private function payload($listings, $users, $agencies, $moves): array
    {
        return [
            'requests' => $moves->map(fn ($move) => [
                'id' => 'MV-' . $move->id,
                'user' => $move->profile?->company_name ?: $move->profile?->email ?: 'Private user',
                'type' => Str::headline((string) $move->move_type),
                'sellPrice' => (float) ($move->metadata['sell_price'] ?? 0),
                'buyBudget' => (float) ($move->budget_max ?? 0),
                'status' => ucfirst($move->status),
                'created' => $move->created_at?->toDateString(),
            ])->values(),
            'discoveryJobs' => [],
            'discoveryResults' => [],
            'missingLinks' => [],
            'properties' => $listings->map(fn (AgencyListing $listing) => $this->listing($listing))->values(),
            'users' => $users->map(fn (User $user) => [
                'id' => 'U-' . $user->id,
                'name' => $user->full_name,
                'email' => $user->email,
                'phone' => $user->phone,
                'role' => $user->isAgency() ? 'Agency' : 'Private user',
                'status' => ucfirst($user->status),
                'moves' => $user->agencyProfile?->agencyListings()->count() ?? 0,
                'joined' => $user->created_at?->toDateString(),
            ])->values(),
            'agencies' => $agencies->map(fn (AgencyProfile $profile) => [
                'id' => 'A-' . $profile->id,
                'name' => $profile->agency_name,
                'city' => $profile->city ?: $profile->target_city,
                'country' => $profile->country,
                'contact' => $profile->user?->full_name,
                'email' => $profile->contact_email ?: $profile->user?->email,
                'listings' => $profile->agencyListings()->count(),
                'chains' => 0,
                'commission' => 0,
                'viewStatus' => true,
                'paidStatus' => $profile->subscription_status === 'active',
                'status' => ucfirst($profile->user?->status ?? 'pending'),
            ])->values(),
            'chains' => Chain::latest()->limit(25)->get()->map(fn (Chain $chain) => [
                'id' => 'C-' . $chain->id,
                'title' => $chain->name ?: 'Property chain ' . $chain->id,
                'score' => (int) round(((float) $chain->confidence_score) * 100),
                'status' => ucfirst($chain->status),
                'value' => (float) ($chain->total_value ?? 0),
                'nodes' => [],
                'missing' => $chain->summary ?: '',
                'owner' => 'EST8ADS',
            ])->values(),
            'payments' => Payment::latest()->limit(25)->get()->map(fn (Payment $payment) => [
                'id' => 'TX-' . $payment->id,
                'date' => $payment->created_at?->toDateString(),
                'customer' => $payment->profile?->company_name ?: $payment->profile?->email,
                'item' => 'EST8ADS service',
                'amount' => (float) $payment->amount,
                'currency' => $payment->currency,
                'status' => ucfirst($payment->status),
            ])->values(),
            'messages' => [],
        ];
    }

    private function listing(AgencyListing $listing): array
    {
        $wantedLocations = $listing->looking_locations ?: array_filter([$listing->looking_location]);

        return [
            'id' => 'P-' . $listing->id,
            'side' => 'sell',
            'title' => $listing->title,
            'type' => Str::headline((string) $listing->property_type),
            'country' => $listing->country,
            'city' => $listing->primary_city ?: $listing->location,
            'area' => $listing->location,
            'price' => (float) ($listing->price ?? 0),
            'currency' => $listing->currency ?: 'EUR',
            'size' => (float) ($listing->living_area ?: $listing->size ?: 0),
            'beds' => (int) ($listing->bedrooms ?? 0),
            'baths' => (int) ($listing->bathrooms ?? 0),
            'status' => ucfirst($listing->status),
            'owner' => $listing->agencyProfile?->user?->full_name,
            'agency' => $listing->agencyProfile?->agency_name,
            'flexibility' => 0,
            'url' => $listing->external_url,
            'views' => 0,
            'verified' => $listing->status === 'active',
            'description' => $listing->description,
            'lookingToBuy' => (bool) $listing->looking_to_buy,
            'wantedType' => Str::headline((string) $listing->looking_property_type),
            'wantedLocations' => array_values($wantedLocations),
            'wantedBudgetMin' => (float) ($listing->looking_budget_min ?? 0),
            'wantedBudgetMax' => (float) ($listing->looking_budget_max ?? 0),
            'wantedCurrency' => $listing->looking_currency ?: 'EUR',
            'wantedBedrooms' => (int) ($listing->looking_min_bedrooms ?? 0),
            'wantedSize' => (float) ($listing->looking_min_size ?? 0),
            'wantedTimeline' => $listing->looking_timeline,
            'wantedNotes' => $listing->looking_notes,
        ];
    }

    /**
     * Build an activity feed showing what the AI discovered most recently.
     *
     * @param  array<int, int>  $moveIds
     * @return array<string, mixed>|null
     */
    private function latestDiscoveryActivity(array $moveIds): ?array
    {
        if ($moveIds === []) {
            return null;
        }

        $job = \App\Models\Est8ads\DiscoveryJob::whereIn('property_move_id', $moveIds)
            ->latest()
            ->first();

        if (! $job) {
            return null;
        }

        $activity = [];

        // AI query generation
        $activity[] = [
            'type' => 'search',
            'icon' => '🔍',
            'title' => 'AI generated search queries',
            'message' => 'Created natural-language queries in English and Croatian for your requirements',
            'time' => $job->created_at->diffForHumans(),
        ];

        // Search engine results
        if ($job->status !== 'queued') {
            $activity[] = [
                'type' => 'search',
                'icon' => '🌐',
                'title' => 'Search engine returned URLs',
                'message' => 'Found property portals and listing pages from the open internet',
                'time' => $job->started_at?->diffForHumans() ?? 'recently',
            ];
        }

        // Scraping activity
        $listings = \App\Models\Est8ads\ExternalListing::where('discovery_job_id', $job->id)->get();

        if ($listings->isNotEmpty()) {
            foreach ($listings->take(4) as $listing) {
                $activity[] = [
                    'type' => 'scrape',
                    'icon' => '📄',
                    'title' => 'Scraped ' . parse_url($listing->canonical_url, PHP_URL_HOST),
                    'message' => sprintf(
                        'Extracted %s, %s m², %s %s',
                        $listing->city ?: 'location',
                        number_format((float) $listing->size_m2),
                        number_format((float) $listing->price),
                        $listing->currency
                    ),
                    'time' => $listing->created_at->diffForHumans(),
                ];
            }
        }

        // Matching activity
        $matches = ExternalListingMatch::where('discovery_job_id', $job->id)
            ->whereIn('match_type', ['exact', 'tolerance'])
            ->orderByDesc('final_score')
            ->get();

        foreach ($matches->take(3) as $match) {
            $activity[] = [
                'type' => 'match',
                'icon' => $match->match_type === 'exact' ? '✓' : '≈',
                'title' => sprintf('%s match found', ucfirst($match->match_type)),
                'message' => sprintf(
                    '%s%% score · %s',
                    number_format((float) $match->final_score),
                    $match->externalListing->title ?? 'Property'
                ),
                'time' => $match->created_at->diffForHumans(),
            ];
        }

        // Completion
        if ($job->status === 'completed') {
            $activity[] = [
                'type' => 'match',
                'icon' => '🎯',
                'title' => 'Discovery completed',
                'message' => sprintf(
                    'Found %d listings, %d matched your criteria',
                    $job->listings_found ?? 0,
                    $matches->count()
                ),
                'time' => $job->finished_at?->diffForHumans() ?? 'just now',
            ];
        }

        return [
            'status' => $job->status,
            'activity' => $activity,
        ];
    }
}
