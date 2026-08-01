<?php

namespace App\Models\Est8ads;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MatchResult extends Model
{
    use SoftDeletes;

    protected $table = 'est8ads_match_results';
    protected $guarded = ['id'];

    protected function casts(): array
    {
        return ['score' => 'decimal:4', 'score_breakdown' => 'array', 'explanation' => 'array', 'expires_at' => 'datetime'];
    }

    public function propertyMove(): BelongsTo { return $this->belongsTo(PropertyMove::class); }
    public function property(): BelongsTo { return $this->belongsTo(Property::class); }
    public function chain(): BelongsTo { return $this->belongsTo(Chain::class); }
}
