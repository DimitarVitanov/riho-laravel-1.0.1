<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompetitorMention extends Model
{
    use HasFactory;

    protected $fillable = [
        'competitor_id',
        'competitor_source_id',
        'url',
        'title',
        'snippet',
        'source_type',
        'first_detected_at',
        'last_seen_at',
        'status',
    ];

    protected $casts = [
        'first_detected_at' => 'datetime',
        'last_seen_at' => 'datetime',
    ];

    public function competitor(): BelongsTo
    {
        return $this->belongsTo(Competitor::class);
    }

    public function source(): BelongsTo
    {
        return $this->belongsTo(CompetitorSource::class, 'competitor_source_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeOfType($query, string $sourceType)
    {
        return $query->where('source_type', $sourceType);
    }

    public function scopeNewToday($query)
    {
        return $query->whereDate('first_detected_at', today());
    }
}
