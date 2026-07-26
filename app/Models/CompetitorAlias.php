<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompetitorAlias extends Model
{
    use HasFactory;

    protected $fillable = [
        'competitor_id',
        'alias',
        'evidence',
        'confidence',
        'is_user_confirmed',
    ];

    protected $casts = [
        'confidence' => 'integer',
        'is_user_confirmed' => 'boolean',
    ];

    public function competitor(): BelongsTo
    {
        return $this->belongsTo(Competitor::class);
    }
}
