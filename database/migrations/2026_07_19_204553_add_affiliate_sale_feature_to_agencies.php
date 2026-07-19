<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use App\Models\AgencyProfile;
use App\Models\AiFeatureSetting;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Add affiliate_sale feature to all existing agency profiles.
     */
    public function up(): void
    {
        $agencyProfiles = AgencyProfile::all();

        foreach ($agencyProfiles as $profile) {
            // Check if affiliate_sale already exists for this agency
            $exists = AiFeatureSetting::where('agency_profile_id', $profile->id)
                ->where('feature_key', 'affiliate_sale')
                ->exists();

            if (!$exists) {
                AiFeatureSetting::create([
                    'agency_profile_id' => $profile->id,
                    'feature_key' => 'affiliate_sale',
                    'is_enabled' => true,
                    'frequency' => 'daily',
                    'ai_model_provider' => 'openai',
                    'ai_model_name' => 'gpt-4',
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        AiFeatureSetting::where('feature_key', 'affiliate_sale')->delete();
    }
};
