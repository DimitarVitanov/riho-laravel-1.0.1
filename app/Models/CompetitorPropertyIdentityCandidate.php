<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompetitorPropertyIdentityCandidate extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_a_id',
        'property_b_id',
        'match_status',
        'ai_confidence',
        'ai_reason',
        'reviewed_at',
    ];

    protected $casts = [
        'ai_confidence' => 'integer',
        'reviewed_at' => 'datetime',
    ];

    public function propertyA(): BelongsTo
    {
        return $this->belongsTo(CompetitorProperty::class, 'property_a_id');
    }

    public function propertyB(): BelongsTo
    {
        return $this->belongsTo(CompetitorProperty::class, 'property_b_id');
    }

    public function scopePending($query)
    {
        return $query->where('match_status', 'pending');
    }

    public function scopeSame($query)
    {
        return $query->where('match_status', 'same');
    }

    public function markAsSame(): void
    {
        $this->update([
            'match_status' => 'same',
            'reviewed_at' => now(),
        ]);
    }

    public function markAsDifferent(): void
    {
        $this->update([
            'match_status' => 'different',
            'reviewed_at' => now(),
        ]);
    }
}
