<?php

namespace App\Services\Est8ads\Discovery;

use App\Models\Est8ads\AuditLog;
use Illuminate\Database\Eloquent\Model;

class DiscoveryAudit
{
    public function record(string $event, Model $subject, array $newValues = [], array $metadata = [], ?int $userId = null): AuditLog
    {
        return AuditLog::create([
            'agency_id' => $subject->agency_id ?? null,
            'user_id' => $userId ?? auth()->id(),
            'event' => $event,
            'auditable_type' => $subject->getMorphClass(),
            'auditable_id' => $subject->getKey(),
            'new_values' => $newValues,
            'ip_address' => app()->runningInConsole() ? null : request()->ip(),
            'user_agent' => app()->runningInConsole() ? null : request()->userAgent(),
            'request_id' => request()?->header('X-Request-ID'),
            'metadata' => $metadata,
        ]);
    }
}
