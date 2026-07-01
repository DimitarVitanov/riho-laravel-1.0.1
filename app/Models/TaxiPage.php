<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaxiPage extends Model
{
    protected $fillable = [
        'slug',
        'locale',
        'taxi_strip_badge',
        'taxi_strip_text',
        'taxi_hero_title',
        'taxi_hero_copy',
        'taxi_ask_title',
        'taxi_ask_placeholder',
        'taxi_ask_note',
        'taxi_why_paragraphs',
        'taxi_purpose_text',
        'taxi_focus_title',
        'taxi_focus_intro',
        'taxi_focus_areas_intro',
        'taxi_focus_areas',
        'taxi_focus_paragraphs',
        'taxi_topic1_title',
        'taxi_topic1_paragraphs',
        'taxi_topic2_title',
        'taxi_topic2_paragraphs',
        'taxi_topic3_title',
        'taxi_topic3_paragraphs',
        'taxi_topic3_question',
        'taxi_topic3_after_paragraphs',
        'taxi_topic4_title',
        'taxi_topic4_question',
        'taxi_topic4_paragraphs',
        'taxi_topic4_list_title',
        'taxi_topic4_list_items',
        'taxi_topic4_closing',
        'taxi_footer_description',
        'taxi_footer_subscribe_title',
        'taxi_footer_subscribe_text',
        'taxi_meta_title',
        'taxi_meta_description',
    ];

    protected function casts(): array
    {
        return [
            'taxi_why_paragraphs' => 'array',
            'taxi_focus_areas' => 'array',
            'taxi_focus_paragraphs' => 'array',
            'taxi_topic1_paragraphs' => 'array',
            'taxi_topic2_paragraphs' => 'array',
            'taxi_topic3_paragraphs' => 'array',
            'taxi_topic3_after_paragraphs' => 'array',
            'taxi_topic4_paragraphs' => 'array',
            'taxi_topic4_list_items' => 'array',
        ];
    }

    public static function forLocale(string $locale = 'en', string $slug = 'home'): ?self
    {
        return static::where('slug', $slug)
            ->where('locale', $locale)
            ->first();
    }
}
