<?php

namespace App\Models\Est8ads;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MissingLink extends Model
{
    protected $table = 'est8ads_missing_links';
    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'unlock_value' => 'decimal:2',
            'blocking_conflicts' => 'array',
            'resolved_at' => 'datetime',
        ];
    }

    public function propertyMove(): BelongsTo { return $this->belongsTo(PropertyMove::class); }
    public function externalListingMatch(): BelongsTo { return $this->belongsTo(ExternalListingMatch::class); }
}
