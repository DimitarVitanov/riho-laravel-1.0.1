<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompetitorStrategySummary extends Model
{
    use HasFactory;

    protected $fillable = [
        'competitor_id',
        'summary_type',
        'observation',
        'confidence',
        'evidence_event_ids',
        'period_start',
        'period_end',
        'prompt_version',
        'ai_model',
    ];

    protected $casts = [
        'confidence' => 'integer',
        'evidence_event_ids' => 'array',
        'period_start' => 'date',
        'period_end' => 'date',
    ];

    public function competitor(): BelongsTo
    {
        return $this->belongsTo(Competitor::class);
    }

    public function scopeOfType($query, string $summaryType)
    {
        return $query->where('summary_type', $summaryType);
    }

    public function scopeLatest($query)
    {
        return $query->orderByDesc('period_end');
    }

    public function getSummaryTypeLabel(): string
    {
        return match($this->summary_type) {
            'property_strategy' => 'Property Strategy',
            'seo_strategy' => 'SEO Strategy',
            'market_focus' => 'Market Focus',
            'pricing_strategy' => 'Pricing Strategy',
            default => ucfirst(str_replace('_', ' ', $this->summary_type)),
        };
    }
}
