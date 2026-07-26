<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CompetitorSource extends Model
{
    use HasFactory;

    protected $fillable = [
        'competitor_id',
        'type',
        'url',
        'external_id',
        'status',
        'config_json',
        'last_checked_at',
        'last_error',
    ];

    protected $casts = [
        'config_json' => 'array',
        'last_checked_at' => 'datetime',
    ];

    public function competitor(): BelongsTo
    {
        return $this->belongsTo(Competitor::class);
    }

    public function urls(): HasMany
    {
        return $this->hasMany(CompetitorUrl::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(CompetitorEvent::class);
    }

    public function mentions(): HasMany
    {
        return $this->hasMany(CompetitorMention::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }
}
