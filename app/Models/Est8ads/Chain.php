<?php

namespace App\Models\Est8ads;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Chain extends Model
{
    use SoftDeletes;

    protected $table = 'est8ads_chains';
    protected $guarded = ['id'];

    protected function casts(): array
    {
        return ['confidence_score' => 'decimal:4', 'summary' => 'array', 'locked_at' => 'datetime', 'completed_at' => 'datetime'];
    }

    public function agency(): BelongsTo { return $this->belongsTo(Agency::class); }
    public function matches(): HasMany { return $this->hasMany(MatchResult::class); }
}
