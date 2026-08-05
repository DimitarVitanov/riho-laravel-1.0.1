<?php

namespace App\Services\Est8ads;

use App\Models\AgencyListing;
use App\Models\AgencyProfile;
use App\Models\Est8ads\Chain;
use App\Models\Est8ads\ExternalListingMatch;
use App\Models\Est8ads\Invoice;
use App\Models\Est8ads\MissingLink;
use App\Models\Est8ads\Profile;
use App\Models\User;
use App\Services\Est8ads\Discovery\ChainDiscoveryDispatcher;
use App\Services\Est8ads\Discovery\DiscoveryPresenter;
use App\Services\Est8ads\Discovery\DiscoverySettings;
use App\Services\Est8ads\Discovery\MemberMatchFinder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class PanelData
{
    public function __construct(
        private DiscoveryPresenter $discoveryPresenter,
        private DiscoverySettings $discoverySettings,
        private BillingService $billing,
        private MemberMatchFinder $memberMatches,
    ) {
    }

    public function forUser(User $user): array
    {
        $agencyProfile = $user->getEffectiveAgencyProfile();
        $listings = $agencyProfile
            ? $this->listingQuery()->where('agency_profile_id', $agencyProfile->id)->get()
            : collect();

        $profile = Profile::where('user_id', $user->id)->first();
        $moves = $profile ? $profile->propertyMoves()->latest()->get() : collect();

        $payload = $this->payload($listings, collect(), $agencyProfile ? collect([$agencyProfile]) : collect(), $moves);
        $payload['chainMatches'] = $this->chainMatches($moves->pluck('id')->all());
        $payload['memberMatches'] = $this->memberMatches($moves);
        $payload['chainTolerance'] = ChainDiscoveryDispatcher::defaultTolerance();
        $payload['latestDiscoveryJob'] = $this->latestDiscoveryActivity($moves->pluck('id')->all());
        $payload['activeSubscription'] = $profile ? $this->billing->subscriptionSummary($profile) : null;
        $payload['accountSuspended'] = $profile ? $this->billing->isSuspended($profile) : false;
        $payload['payments'] = $profile ? $this->invoices(Invoice::where('profile_id', $profile->id)) : [];

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
        $payload['payments'] = $this->invoices(Invoice::query(), 50);
        $payload['billingStats'] = $this->billingStats();

        return $payload;
    }

    /**
     * Invoices rendered as "payments" rows for the admin/user billing
     * tables, newest first. Unpaid invoices carry an id so the admin panel
     * can offer a "Mark as paid" action.
     */
    private function invoices(Builder $query, int $limit = 25): array
    {
        return $query->with('profile')->latest('issued_on')->limit($limit)->get()
            ->map(function (Invoice $invoice) {
                $suspended = $invoice->profile && $this->billing->isSuspended($invoice->profile);

                return [
                    'id' => 'INV-' . $invoice->id,
                    'invoice_id' => $invoice->id,
                    'date' => $invoice->issued_on?->toDateString(),
                    'due_on' => $invoice->due_on?->toDateString(),
                    'customer' => $invoice->profile?->company_name ?: $invoice->profile?->email ?: 'Unknown',
                    'item' => 'EST8ADS 30-day subscription',
                    'amount' => (float) $invoice->total,
                    'currency' => $invoice->currency,
                    'status' => $invoice->status === 'paid' ? 'Paid' : ($suspended ? 'Suspended' : 'Open'),
                    'paid' => $invoice->status === 'paid',
                ];
            })
            ->values()
            ->all();
    }

    /**
     * Real replacement for the hardcoded billing KPIs on the admin panel.
     */
    private function billingStats(): array
    {
        $monthStart = now()->startOfMonth();

        return [
            'monthly_revenue' => (float) Invoice::where('status', 'paid')->where('paid_at', '>=', $monthStart)->sum('total'),
            'paid_invoices' => Invoice::where('status', 'paid')->where('paid_at', '>=', $monthStart)->count(),
            'active_subscriptions' => Invoice::where('status', 'paid')->distinct('profile_id')->count('profile_id'),
            'pending_amount' => (float) Invoice::where('status', '!=', 'paid')->sum('amount_due'),
        ];
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

    /**
     * Properties that other EST8ADS members (and mirrored Villa Bit listings)
     * are selling which match what this member is looking to buy, within the
     * configured price/size tolerance. The member's own listings are excluded.
     *
     * @param  Collection<int, PropertyMove>  $moves
     * @return array<int, array<string, mixed>>
     */
    private function memberMatches(Collection $moves, int $limit = 30): array
    {
        $buying = $moves->filter(fn ($move) => in_array($move->move_type, ['buy', 'sell_and_buy'], true)
            && in_array($move->status, ['active', 'submitted'], true));

        $matches = [];
        foreach ($buying as $move) {
            foreach ($this->memberMatches->forMove($move) as $match) {
                // Dedupe across moves: keep the highest-scoring appearance of a
                // given member property.
                if (! isset($matches[$match['id']]) || $match['score'] > $matches[$match['id']]['score']) {
                    $matches[$match['id']] = $match;
                }
            }
        }

        $matches = array_values($matches);
        usort($matches, fn ($a, $b) => $b['score'] <=> $a['score']);

        return array_slice($matches, 0, $limit);
    }

    /**
     * High-value requests the AI has flagged as blocked, most valuable and
     * most urgent first.
     *
     * @param  array<int, int>  $moveIds
     * @return array<int, array<string, mixed>>
     */
    private function missingLinks(array $moveIds, int $limit = 25): array
    {
        if ($moveIds === []) {
            return [];
        }

        return MissingLink::whereIn('property_move_id', $moveIds)
            ->where('status', 'open')
            ->orderByDesc('priority_rank')
            ->orderByDesc('unlock_value')
            ->limit($limit)
            ->get()
            ->map(fn (MissingLink $link) => [
                'id' => 'ML-' . $link->id,
                'title' => $link->title,
                'location' => $link->location,
                'impact' => $link->impact,
                'value' => $link->unlock_value !== null
                    ? number_format((float) $link->unlock_value) . ' ' . $link->unlock_value_currency
                    : null,
                'priority' => $link->priority,
                'reason' => $link->reason_type,
                'explanation' => $link->explanation,
            ])
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
            'missingLinks' => $this->missingLinks($moves->pluck('id')->all()),
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
            // Overwritten by forUser()/forAdmin() with real invoice data,
            // scoped to the current profile or to everyone for the admin.
            'payments' => [],
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
