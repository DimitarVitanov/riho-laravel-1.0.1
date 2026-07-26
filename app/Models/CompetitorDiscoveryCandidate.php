<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompetitorDiscoveryCandidate extends Model
{
    use HasFactory;

    protected $fillable = [
        'competitor_id',
        'url',
        'title',
        'snippet',
        'source_type',
        'match_status',
        'ai_confidence',
        'ai_reason',
        'discovered_at',
        'reviewed_at',
    ];

    protected $casts = [
        'ai_confidence' => 'integer',
        'discovered_at' => 'datetime',
        'reviewed_at' => 'datetime',
    ];

    public function competitor(): BelongsTo
    {
        return $this->belongsTo(Competitor::class);
    }

    public function scopePending($query)
    {
        return $query->where('match_status', 'pending');
    }

    public function scopeMatched($query)
    {
        return $query->where('match_status', 'match');
    }

    public function scopeUnreviewed($query)
    {
        return $query->whereNull('reviewed_at');
    }

    public function markAsMatch(): void
    {
        $this->update([
            'match_status' => 'match',
            'reviewed_at' => now(),
        ]);
    }

    public function markAsNoMatch(): void
    {
        $this->update([
            'match_status' => 'no_match',
            'reviewed_at' => now(),
        ]);
    }
}
