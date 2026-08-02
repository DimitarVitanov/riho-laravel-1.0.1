<?php

namespace App\Models\Est8ads;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Property extends Model
{
    use SoftDeletes;

    protected $table = 'est8ads_properties';
    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'asking_price' => 'decimal:2', 'floor_area' => 'decimal:2', 'land_area' => 'decimal:2',
            'latitude' => 'decimal:7', 'longitude' => 'decimal:7', 'metadata' => 'array', 'published_at' => 'datetime',
        ];
    }

    public function agency(): BelongsTo { return $this->belongsTo(Agency::class); }
    public function propertyMove(): BelongsTo { return $this->belongsTo(PropertyMove::class); }
    public function matches(): HasMany { return $this->hasMany(MatchResult::class); }
    public function externalListingMatches(): HasMany { return $this->hasMany(ExternalListingMatch::class); }
    public function externalListings(): BelongsToMany
    {
        return $this->belongsToMany(ExternalListing::class, 'est8ads_external_listing_matches')->withPivot(['status', 'confidence_score', 'is_manual'])->withTimestamps();
    }
}
