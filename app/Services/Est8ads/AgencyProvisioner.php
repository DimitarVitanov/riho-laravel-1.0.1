<?php

namespace App\Services\Est8ads;

use App\Models\AgencyProfile;
use App\Models\Est8ads\Agency;
use App\Models\Est8ads\Profile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AgencyProvisioner
{
    public function provision(AgencyProfile $agencyProfile): Agency
    {
        return DB::transaction(function () use ($agencyProfile) {
            $agencyProfile->loadMissing('user');
            $user = $agencyProfile->user;

            if (!$user) {
                throw new \RuntimeException('An EST8ADS agency requires a Villa Bit user identity.');
            }

            if (!$user->has_est8ads_access) {
                $user->update(['has_est8ads_access' => true]);
            }

            $agency = Agency::firstOrNew(['agency_profile_id' => $agencyProfile->id]);
            $agency->fill([
                'name' => $agencyProfile->agency_name ?: $user->company_name ?: $user->full_name,
                'slug' => $agency->slug ?: $this->uniqueSlug($agencyProfile->agency_name ?: $user->company_name ?: $user->full_name),
                'status' => $user->status === 'active' ? 'active' : 'pending',
                'email' => $agencyProfile->contact_email ?: $user->email,
                'phone' => $agencyProfile->contact_phone ?: $user->phone,
                'website' => $agencyProfile->official_website_url,
                'country_code' => strlen((string) $agencyProfile->country) === 2 ? strtoupper($agencyProfile->country) : null,
                'timezone' => $user->timezone ?: 'UTC',
                'default_currency' => 'EUR',
                'metadata' => ['source' => 'villabit', 'agency_profile_id' => $agencyProfile->id],
            ]);
            $agency->save();

            $profile = Profile::firstOrNew(['user_id' => $user->id]);
            $profile->fill([
                'agency_id' => $agency->id,
                'type' => 'agency',
                'status' => $user->status === 'active' ? 'active' : 'pending',
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'company_name' => $agency->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'country_code' => strlen((string) $user->country) === 2 ? strtoupper($user->country) : null,
                'preferred_language' => $user->preferred_language ?: 'en',
                'timezone' => $user->timezone ?: 'UTC',
                'public_reference' => $profile->public_reference ?: 'EST-' . strtoupper(Str::random(12)),
                'metadata' => ['source' => 'villabit_agency'],
            ]);
            $profile->save();

            DB::table('est8ads_agency_memberships')->updateOrInsert(
                ['agency_id' => $agency->id, 'user_id' => $user->id],
                [
                    'role' => 'owner',
                    'status' => $user->status === 'active' ? 'active' : 'pending',
                    'permissions' => json_encode(['listings' => 'manage', 'property_moves' => 'manage']),
                    'joined_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );

            return $agency;
        });
    }

    private function uniqueSlug(string $name): string
    {
        $base = Str::slug($name) ?: 'agency';
        $slug = $base;
        $suffix = 2;

        while (Agency::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $suffix++;
        }

        return $slug;
    }
}
