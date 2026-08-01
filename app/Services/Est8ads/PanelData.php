<?php

namespace App\Services\Est8ads;

use App\Models\AgencyListing;
use App\Models\AgencyProfile;
use App\Models\Est8ads\Chain;
use App\Models\Est8ads\Payment;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class PanelData
{
    public function forUser(User $user): array
    {
        $agencyProfile = $user->getEffectiveAgencyProfile();
        $listings = $agencyProfile
            ? $this->listingQuery()->where('agency_profile_id', $agencyProfile->id)->get()
            : collect();

        $profile = \App\Models\Est8ads\Profile::where('user_id', $user->id)->first();
        $moves = $profile ? $profile->propertyMoves()->latest()->get() : collect();

        return $this->payload($listings, collect(), $agencyProfile ? collect([$agencyProfile]) : collect(), $moves);
    }

    public function forAdmin(): array
    {
        return $this->payload(
            $this->listingQuery()->get(),
            User::where('has_est8ads_access', true)->get(),
            AgencyProfile::with('user')->whereHas('user', fn (Builder $query) => $query->where('has_est8ads_access', true))->get(),
            \App\Models\Est8ads\PropertyMove::with('profile')->latest()->limit(100)->get(),
        );
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
}
