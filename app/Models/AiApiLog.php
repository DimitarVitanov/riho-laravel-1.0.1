<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiApiLog extends Model
{
    protected $fillable = [
        'provider',
        'feature_key',
        'agency_profile_id',
        'tokens_input',
        'tokens_output',
        'api_calls_count',
        'cost_estimate_usd',
        'model_name',
        'status',
    ];

    public function agencyProfile()
    {
        return $this->belongsTo(AgencyProfile::class);
    }

    public static function record(string $provider, array $data = []): self
    {
        return static::create(array_merge(['provider' => $provider], $data));
    }

    public static function monthlySummary(): array
    {
        return static::selectRaw('provider, SUM(api_calls_count) as total_calls, SUM(tokens_input) as total_tokens_in, SUM(tokens_output) as total_tokens_out, SUM(cost_estimate_usd) as total_cost')
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->groupBy('provider')
            ->get()
            ->keyBy('provider')
            ->toArray();
    }
}
