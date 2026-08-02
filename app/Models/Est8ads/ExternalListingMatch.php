<?php

namespace App\Models\Est8ads;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExternalListingMatch extends Model
{
    protected $table = 'est8ads_external_listing_matches';
    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'confidence_score' => 'decimal:4', 'deterministic_score' => 'decimal:2',
            'semantic_score' => 'decimal:2', 'final_score' => 'decimal:2',
            'data_confidence' => 'decimal:2', 'reasons' => 'array',
            'hard_conflicts' => 'array', 'tolerance' => 'array',
            'is_manual' => 'boolean', 'reviewed_at' => 'datetime',
        ];
    }

    public function externalListing(): BelongsTo { return $this->belongsTo(ExternalListing::class); }
    public function property(): BelongsTo { return $this->belongsTo(Property::class); }
    public function propertyMove(): BelongsTo { return $this->belongsTo(PropertyMove::class); }
    public function discoveryJob(): BelongsTo { return $this->belongsTo(DiscoveryJob::class); }
    public function connectedProperty(): BelongsTo { return $this->belongsTo(Property::class, 'connected_property_id'); }
    public function reviewedBy(): BelongsTo { return $this->belongsTo(User::class, 'reviewed_by_user_id'); }
}
