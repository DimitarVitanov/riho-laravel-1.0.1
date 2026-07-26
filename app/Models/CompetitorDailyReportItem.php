<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompetitorDailyReportItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'competitor_daily_report_id',
        'item_type',
        'content',
        'priority',
        'reason',
        'evidence_event_ids',
        'sort_order',
    ];

    protected $casts = [
        'evidence_event_ids' => 'array',
        'sort_order' => 'integer',
    ];

    public function report(): BelongsTo
    {
        return $this->belongsTo(CompetitorDailyReport::class, 'competitor_daily_report_id');
    }

    public function getPriorityBadgeClass(): string
    {
        return match($this->priority) {
            'high' => 'badge-danger',
            'medium' => 'badge-warning',
            'low' => 'badge-info',
            default => 'badge-secondary',
        };
    }
}
