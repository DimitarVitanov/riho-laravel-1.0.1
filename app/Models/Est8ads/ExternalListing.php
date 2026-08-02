<?php

namespace App\Models\Est8ads;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExternalListing extends Model
{
    use SoftDeletes;

    protected $table = 'est8ads_external_listings';
    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2', 'size_m2' => 'decimal:2', 'land_m2' => 'decimal:2',
            'latitude' => 'decimal:7', 'longitude' => 'decimal:7', 'media' => 'array',
            'attributes' => 'array', 'raw_payload' => 'array', 'source_published_at' => 'datetime',
            'source_updated_at' => 'datetime', 'first_seen_at' => 'datetime', 'last_seen_at' => 'datetime',
        ];
    }

    public function internetSource(): BelongsTo { return $this->belongsTo(InternetSource::class); }
    public function discoveryJob(): BelongsTo { return $this->belongsTo(DiscoveryJob::class); }
    public function listingMatches(): HasMany { return $this->hasMany(ExternalListingMatch::class); }
    public function snapshots(): HasMany { return $this->hasMany(ExternalListingSnapshot::class); }
    public function properties(): BelongsToMany
    {
        return $this->belongsToMany(Property::class, 'est8ads_external_listing_matches')->withPivot(['status', 'confidence_score', 'is_manual'])->withTimestamps();
    }
}
