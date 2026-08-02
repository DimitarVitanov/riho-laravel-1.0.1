<?php

namespace App\Services\Est8ads\Discovery;

use App\Models\Est8ads\Setting;

class DiscoverySettings
{
    public const DEFAULTS = [
        'enabled' => true,
        'automation_running' => true,
        'trigger' => 'immediate',
        'refresh_interval' => '12_hours',
        'save_threshold' => 72,
        'auto_connect_threshold' => 88,
        'connect_threshold' => 88,
        'minimum_data_confidence' => 75,
        'freshness_days' => 7,
        'result_limit' => 100,
        'radius' => 'city_nearby',
        'sources' => [],
    ];

    public function get(?int $agencyId = null): array
    {
        $row = Setting::query()->where('scope', $agencyId ? 'agency' : 'global')->where('scope_id', $agencyId ?: 0)
            ->where('group', 'internet_discovery')->where('key', 'policy')->first();
        return array_replace(self::DEFAULTS, (array) $row?->value);
    }

    public function put(array $values, ?int $agencyId = null): array
    {
        $safe = array_intersect_key($values, self::DEFAULTS);
        $value = array_replace($this->get($agencyId), $safe);
        Setting::updateOrCreate(
            ['scope' => $agencyId ? 'agency' : 'global', 'scope_id' => $agencyId ?: 0, 'group' => 'internet_discovery', 'key' => 'policy'],
            ['agency_id' => $agencyId, 'type' => 'json', 'value' => $value, 'is_public' => false, 'description' => 'AI Internet Discovery policy; credentials are never stored here.']
        );
        return $value;
    }
}
