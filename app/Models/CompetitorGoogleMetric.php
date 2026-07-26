<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompetitorGoogleMetric extends Model
{
    use HasFactory;

    protected $fillable = [
        'competitor_id',
        'rating',
        'review_count',
        'business_name',
        'business_address',
        'business_phone',
        'business_website',
        'captured_at',
    ];

    protected $casts = [
        'rating' => 'decimal:1',
        'review_count' => 'integer',
        'captured_at' => 'datetime',
    ];

    public function competitor(): BelongsTo
    {
        return $this->belongsTo(Competitor::class);
    }

    public function getRatingChangeFrom(?CompetitorGoogleMetric $previous): ?array
    {
        if (!$previous || $this->rating === null || $previous->rating === null) {
            return null;
        }

        $diff = $this->rating - $previous->rating;
        if ($diff == 0) {
            return null;
        }

        return [
            'old_rating' => $previous->rating,
            'new_rating' => $this->rating,
            'difference' => round($diff, 1),
            'direction' => $diff > 0 ? 'increase' : 'decrease',
        ];
    }

    public function getReviewCountChangeFrom(?CompetitorGoogleMetric $previous): ?array
    {
        if (!$previous || $this->review_count === null || $previous->review_count === null) {
            return null;
        }

        $diff = $this->review_count - $previous->review_count;
        if ($diff == 0) {
            return null;
        }

        return [
            'old_count' => $previous->review_count,
            'new_count' => $this->review_count,
            'difference' => $diff,
            'direction' => $diff > 0 ? 'increase' : 'decrease',
        ];
    }
}
