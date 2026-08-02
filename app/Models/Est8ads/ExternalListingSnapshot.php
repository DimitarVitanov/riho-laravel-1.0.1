<?php

namespace App\Models\Est8ads;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExternalListingSnapshot extends Model
{
    protected $table = 'est8ads_external_listing_snapshots';
    protected $guarded = ['id'];

    protected function casts(): array
    {
        return ['payload' => 'array', 'price' => 'decimal:2', 'captured_at' => 'datetime'];
    }

    public function externalListing(): BelongsTo { return $this->belongsTo(ExternalListing::class); }
}
