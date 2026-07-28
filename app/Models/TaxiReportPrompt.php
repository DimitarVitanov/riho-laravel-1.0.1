<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaxiReportPrompt extends Model
{
    protected $fillable = [
        'key',
        'label',
        'placement',
        'section_id',
        'prompt_text',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public static function globalRules(): ?string
    {
        return static::where('key', 'GLOBAL-001')->value('prompt_text');
    }

    /**
     * Prompt matching a DOM section id (e.g. "prices"), or null.
     */
    public static function forSection(string $sectionId): ?self
    {
        return static::where('is_active', true)
            ->where('section_id', $sectionId)
            ->first();
    }
}
