<?php

namespace App\Models\Est8ads;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DiscoveryJob extends Model
{
    protected $table = 'est8ads_discovery_jobs';
    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'parameters' => 'array', 'save_threshold' => 'decimal:2',
            'auto_connect_threshold' => 'decimal:2', 'started_at' => 'datetime', 'finished_at' => 'datetime',
        ];
    }

    public function internetSource(): BelongsTo { return $this->belongsTo(InternetSource::class); }
    public function agency(): BelongsTo { return $this->belongsTo(Agency::class); }
    public function propertyMove(): BelongsTo { return $this->belongsTo(PropertyMove::class); }
    public function requestedBy(): BelongsTo { return $this->belongsTo(User::class, 'requested_by_user_id'); }
    public function externalListings(): HasMany { return $this->hasMany(ExternalListing::class); }
    public function listingMatches(): HasMany { return $this->hasMany(ExternalListingMatch::class); }
}
