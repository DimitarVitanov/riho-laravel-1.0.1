<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompetitorUrlSnapshot extends Model
{
    use HasFactory;

    protected $fillable = [
        'competitor_url_id',
        'title',
        'title_hash',
        'meta_description',
        'meta_description_hash',
        'h1',
        'h1_hash',
        'content_hash',
        'schema_json',
        'schema_hash',
        'cta_text',
        'cta_hash',
        'word_count',
        'http_status',
        'captured_at',
    ];

    protected $casts = [
        'schema_json' => 'array',
        'word_count' => 'integer',
        'http_status' => 'integer',
        'captured_at' => 'datetime',
    ];

    public function url(): BelongsTo
    {
        return $this->belongsTo(CompetitorUrl::class, 'competitor_url_id');
    }

    public function hasChangedFrom(?CompetitorUrlSnapshot $previous): bool
    {
        if (!$previous) {
            return true;
        }

        return $this->title_hash !== $previous->title_hash
            || $this->meta_description_hash !== $previous->meta_description_hash
            || $this->h1_hash !== $previous->h1_hash
            || $this->content_hash !== $previous->content_hash
            || $this->schema_hash !== $previous->schema_hash
            || $this->cta_hash !== $previous->cta_hash;
    }

    public function getChangesFrom(?CompetitorUrlSnapshot $previous): array
    {
        if (!$previous) {
            return ['new_url'];
        }

        $changes = [];

        if ($this->title_hash !== $previous->title_hash) {
            $changes[] = 'title_changed';
        }
        if ($this->meta_description_hash !== $previous->meta_description_hash) {
            $changes[] = 'meta_description_changed';
        }
        if ($this->h1_hash !== $previous->h1_hash) {
            $changes[] = 'h1_changed';
        }
        if ($this->content_hash !== $previous->content_hash) {
            $changes[] = 'content_changed';
        }
        if ($this->schema_hash !== $previous->schema_hash) {
            $changes[] = 'schema_changed';
        }
        if ($this->cta_hash !== $previous->cta_hash) {
            $changes[] = 'cta_changed';
        }

        return $changes;
    }
}
