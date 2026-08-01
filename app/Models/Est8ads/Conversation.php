<?php

namespace App\Models\Est8ads;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Conversation extends Model
{
    use SoftDeletes;

    protected $table = 'est8ads_conversations';
    protected $guarded = ['id'];

    protected function casts(): array
    {
        return ['metadata' => 'array', 'last_message_at' => 'datetime'];
    }

    public function agency(): BelongsTo { return $this->belongsTo(Agency::class); }
    public function propertyMove(): BelongsTo { return $this->belongsTo(PropertyMove::class); }
    public function matchResult(): BelongsTo { return $this->belongsTo(MatchResult::class); }
    public function messages(): HasMany { return $this->hasMany(Message::class)->orderBy('sent_at'); }
}
