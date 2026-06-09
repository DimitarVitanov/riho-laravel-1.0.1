<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiFeatureSetting extends Model
{
    protected $fillable = [
        'agency_profile_id', 'feature_key', 'is_enabled',
        'custom_prompt_override', 'ai_model_provider', 'ai_model_name',
        'frequency', 'last_run_at', 'next_run_at',
    ];

    protected function casts(): array
    {
        return [
            'is_enabled' => 'boolean',
            'last_run_at' => 'datetime',
            'next_run_at' => 'datetime',
        ];
    }

    public function agencyProfile()
    {
        return $this->belongsTo(AgencyProfile::class);
    }
}
